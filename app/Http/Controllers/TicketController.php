<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $user = auth()->user();

    $query = Ticket::with(['category', 'user', 'assignee', 'department'])->latest();

    if ($user->role !== 'admin') {
        $query->where('user_id', $user->id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    if ($request->filled('site_type')) {
        $query->where('site_type', $request->site_type);
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('ticket_number', 'like', '%' . $search . '%')
              ->orWhere('title', 'like', '%' . $search . '%');
        });
    }

    $tickets = $query->paginate(10)->withQueryString();

    $departments = Department::orderBy('name')->get();

    return view('tickets.index', compact('tickets', 'departments'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

    return view('tickets.create', compact('categories', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'priority' => 'required|in:low,medium,high,critical',
        'department_id' => 'nullable|exists:departments,id',

        'site_type' => 'required|in:hq,branch',
        'source_name' => 'required|string|max:255',
        'extension_number' => 'required|string|max:10',
    ]);

    // Extra workflow validation for extension ranges
    if ($request->site_type === 'hq') {
        if (!preg_match('/^7\d{2}$/', $request->extension_number)) {
            return back()
                ->withErrors(['extension_number' => 'HQ extensions must be in the 700 series.'])
                ->withInput();
        }
    }

    if ($request->site_type === 'branch') {
        if (!preg_match('/^8\d{2}$/', $request->extension_number)) {
            return back()
                ->withErrors(['extension_number' => 'Branch extensions must be in the 800 series.'])
                ->withInput();
        }
    }

    $lastTicket = Ticket::latest('id')->first();
    $nextId = $lastTicket ? $lastTicket->id + 1 : 1;

    $ticket = Ticket::create([
        'ticket_number' => 'ICT-' . now()->format('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT),
        'title' => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'priority' => $request->priority,
        'status' => 'open',
        'user_id' => auth()->id(),
        'department_id' => $request->department_id,
        'site_type' => $request->site_type,
        'source_name' => $request->source_name,
        'extension_number' => $request->extension_number,
    ]);

    ActivityLog::create([
        'ticket_id' => $ticket->id,
        'user_id' => auth()->id(),
        'action' => 'created_ticket',
        'old_value' => null,
        'new_value' => json_encode([
            'message' => 'Ticket created',
            'ticket_number' => $ticket->ticket_number,
            'site_type' => $ticket->site_type,
            'source_name' => $ticket->source_name,
            'extension_number' => $ticket->extension_number,
        ]),
        'created_at' => now(),
    ]);

    return redirect()
        ->route('tickets.index')
        ->with('success', 'Ticket created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $this->authorizeTicket($ticket);

        $ticket->load(['category', 'user', 'assignee', 'department', 'comments.user', 'activityLogs.user']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(string $id)
{
    $ticket = Ticket::with(['category', 'department', 'user', 'assignee'])->findOrFail($id);

    $this->authorizeTicket($ticket);

    $categories = Category::orderBy('name')->get();
    $departments = Department::orderBy('name')->get();

    // Users who can be assigned tickets (admins / technicians)
    $admins = User::whereIn('role', ['admin', 'technician'])->orderBy('name')->get();

    return view('tickets.edit', compact(
        'ticket',
        'categories',
        'departments',
        'admins'
    ));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $ticket = Ticket::findOrFail($id);

    $this->authorizeTicket($ticket);

    $user = auth()->user();

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'department_id' => 'nullable|exists:departments,id',
        'site_type' => 'required|in:hq,branch',
        'source_name' => 'required|string|max:255',
        'extension_number' => 'required|string|max:10',

        'priority' => 'nullable|in:low,medium,high,critical',
        'status' => 'nullable|in:open,in_progress,resolved,closed',
        'assigned_to' => 'nullable|exists:users,id',
        'resolution_notes' => 'nullable|string',
    ]);

    // Validate extension logic
    if ($request->site_type === 'hq' && !preg_match('/^7\d{2}$/', $request->extension_number)) {
        return back()
            ->withErrors(['extension_number' => 'HQ extensions must be in the 700 series.'])
            ->withInput();
    }

    if ($request->site_type === 'branch' && !preg_match('/^8\d{2}$/', $request->extension_number)) {
        return back()
            ->withErrors(['extension_number' => 'Branch extensions must be in the 800 series.'])
            ->withInput();
    }

    $oldStatus = $ticket->status;
    $oldAssignee = $ticket->assigned_to;
    $oldPriority = $ticket->priority;
    $oldResolutionNotes = $ticket->resolution_notes;

    // Fields everyone allowed through authorizeTicket can edit
    $updateData = [
        'title' => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'department_id' => $request->department_id,
        'site_type' => $request->site_type,
        'source_name' => $request->source_name,
        'extension_number' => $request->extension_number,
    ];

    // Admin can control everything
    if ($user->role === 'admin') {
        $updateData['priority'] = $request->priority ?? $ticket->priority;
        $updateData['status'] = $request->status ?? $ticket->status;
        $updateData['assigned_to'] = $request->assigned_to;
        $updateData['resolution_notes'] = $request->resolution_notes;
    }

    // Assigned technician can work the ticket, but not reassign it
    elseif ($ticket->assigned_to === $user->id) {
        $updateData['status'] = $request->status ?? $ticket->status;
        $updateData['resolution_notes'] = $request->resolution_notes;
    }

    // Ticket owner / normal user: restricted edits only
    else {
        // Keep protected workflow fields unchanged
        $updateData['priority'] = $ticket->priority;
        $updateData['status'] = $ticket->status;
        $updateData['assigned_to'] = $ticket->assigned_to;
        $updateData['resolution_notes'] = $ticket->resolution_notes;
    }

    $ticket->update($updateData);

    // Log status change
    if ($oldStatus !== $ticket->status) {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'status_updated',
            'old_value' => $oldStatus,
            'new_value' => $ticket->status,
            'created_at' => now(),
        ]);
    }

    // Log technician assignment change
    if ($oldAssignee !== $ticket->assigned_to) {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'technician_assigned',
            'old_value' => $oldAssignee,
            'new_value' => $ticket->assigned_to,
            'created_at' => now(),
        ]);
    }

    // Log priority change
    if ($oldPriority !== $ticket->priority) {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'priority_updated',
            'old_value' => $oldPriority,
            'new_value' => $ticket->priority,
            'created_at' => now(),
        ]);
    }

    // Log resolution notes change
    if ($oldResolutionNotes !== $ticket->resolution_notes) {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'resolution_notes_updated',
            'old_value' => $oldResolutionNotes,
            'new_value' => $ticket->resolution_notes,
            'created_at' => now(),
        ]);
    }

    return redirect()
        ->route('tickets.show', $ticket)
        ->with('success', 'Ticket updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

private function authorizeTicket(Ticket $ticket)
{
    $user = auth()->user();

    // Admins can access all tickets
    if ($user->role === 'admin') {
        return;
    }

    // Ticket owner can access their ticket
    if ($ticket->user_id === $user->id) {
        return;
    }

    // Assigned technician can access the ticket
    if ($ticket->assigned_to === $user->id) {
        return;
    }

    // Otherwise deny access
    abort(403, 'Unauthorized access to this ticket.');
}
}
