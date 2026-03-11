<?php

namespace App\Http\Controllers;

use App\Models\User;

class TechnicianMetricsController extends Controller
{
    public function leaderboard()
    {
        $technicians = User::where('role', 'technician')
            ->withCount([
                'assignedTickets',
                'resolvedTickets',
                'openTickets',
            ])
            ->orderByDesc('resolved_tickets_count')
            ->orderByDesc('assigned_tickets_count')
            ->get();

        return view('metrics.leaderboard', compact('technicians'));
    }
}