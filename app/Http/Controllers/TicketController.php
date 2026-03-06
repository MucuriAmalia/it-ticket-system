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
    public function index()
    {
         $user = auth()->user();

        $tickets = $user->role === 'admin'
            ? Ticket::with(['category', 'user', 'assignee', 'department'])->latest()->get()
            : Ticket::with(['category', 'user', 'assignee', 'department'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $departments = Department::all();

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
        ]);

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
        ]);

        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'created_ticket',
            'old_value' => null,
            'new_value' => 'Ticket created',
            'created_at' => now(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
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
         $this->authorizeTicket($ticket);

        $categories = Category::all();
        $departments = Department::all();
        $admins = User::where('role', 'admin')->get();

        return view('tickets.edit', compact('ticket', 'categories', 'departments', 'admins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeTicket($ticket);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'department_id' => $request->department_id,
            'assigned_to' => auth()->user()->role === 'admin' ? $request->assigned_to : $ticket->assigned_to,
            'resolution_notes' => $request->resolution_notes,
        ]);

        if ($oldStatus !== $request->status) {
            ActivityLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'status_updated',
                'old_value' => $oldStatus,
                'new_value' => $request->status,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
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

        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
            abort(403);
        }
    }
}
