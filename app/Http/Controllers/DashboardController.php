<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Base query depending on role
        $ticketQuery = Ticket::query();

        if ($user->role !== 'admin') {
            $ticketQuery->where('user_id', $user->id);
        }

        // Ticket status statistics
        $statusCounts = $ticketQuery
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'open') as open,
                SUM(status = 'in_progress') as in_progress,
                SUM(status = 'resolved') as resolved,
                SUM(status = 'closed') as closed,
                SUM(priority = 'critical') as critical
            ")
            ->first();

        // HQ vs Branch statistics
        $originStats = $ticketQuery
            ->selectRaw("
                SUM(site_type = 'hq') as hq,
                SUM(site_type = 'branch') as branch
            ")
            ->first();

        // Recent tickets
        $tickets = $ticketQuery
            ->with(['category', 'department', 'user', 'assignee'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', [
            'totalTickets' => $statusCounts->total ?? 0,
            'openTickets' => $statusCounts->open ?? 0,
            'inProgressTickets' => $statusCounts->in_progress ?? 0,
            'resolvedTickets' => $statusCounts->resolved ?? 0,
            'closedTickets' => $statusCounts->closed ?? 0,
            'criticalTickets' => $statusCounts->critical ?? 0,

            'hqTickets' => $originStats->hq ?? 0,
            'branchTickets' => $originStats->branch ?? 0,

            'tickets' => $tickets,
        ]);
    }
}