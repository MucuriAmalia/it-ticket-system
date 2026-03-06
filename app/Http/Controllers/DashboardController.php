<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $openTickets = Ticket::where('status', 'open')->count();
            $inProgressTickets = Ticket::where('status', 'in_progress')->count();
            $resolvedTickets = Ticket::where('status', 'resolved')->count();
            $closedTickets = Ticket::where('status', 'closed')->count();
            $tickets = Ticket::latest()->take(10)->get();
        } else {
            $openTickets = Ticket::where('user_id', $user->id)->where('status', 'open')->count();
            $inProgressTickets = Ticket::where('user_id', $user->id)->where('status', 'in_progress')->count();
            $resolvedTickets = Ticket::where('user_id', $user->id)->where('status', 'resolved')->count();
            $closedTickets = Ticket::where('user_id', $user->id)->where('status', 'closed')->count();
            $tickets = Ticket::where('user_id', $user->id)->latest()->take(10)->get();
        }

        return view('dashboard.index', compact(
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'tickets'
        ));
    }
}
