<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $baseQuery = Ticket::query();

        if ($user->role !== 'admin') {
            $baseQuery->where('user_id', $user->id);
        }

        $statusCounts = (clone $baseQuery)
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'open') as open,
                SUM(status = 'in_progress') as in_progress,
                SUM(status = 'resolved') as resolved,
                SUM(status = 'closed') as closed,
                SUM(priority = 'critical') as critical
            ")
            ->first();

        $originStats = (clone $baseQuery)
            ->selectRaw("
                SUM(site_type = 'hq') as hq,
                SUM(site_type = 'branch') as branch
            ")
            ->first();

        $tickets = (clone $baseQuery)
            ->with(['category', 'department', 'user', 'assignee'])
            ->latest()
            ->take(10)
            ->get();

        $trendData = (clone $baseQuery)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $trendMap = $trendData->pluck('total', 'date');

        $trendLabels = [];
        $trendValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');

            $trendLabels[] = $date->format('D');
            $trendValues[] = $trendMap[$formattedDate] ?? 0;
        }

        $hqTickets = $originStats->hq ?? 0;
        $branchTickets = $originStats->branch ?? 0;
        $locationTotal = $hqTickets + $branchTickets;

        $hqPercentage = $locationTotal > 0 ? round(($hqTickets / $locationTotal) * 100) : 0;
        $branchPercentage = $locationTotal > 0 ? round(($branchTickets / $locationTotal) * 100) : 0;

        return view('dashboard.index', [
            'totalTickets' => $statusCounts->total ?? 0,
            'openTickets' => $statusCounts->open ?? 0,
            'inProgressTickets' => $statusCounts->in_progress ?? 0,
            'resolvedTickets' => $statusCounts->resolved ?? 0,
            'closedTickets' => $statusCounts->closed ?? 0,
            'criticalTickets' => $statusCounts->critical ?? 0,

            'hqTickets' => $hqTickets,
            'branchTickets' => $branchTickets,
            'hqPercentage' => $hqPercentage,
            'branchPercentage' => $branchPercentage,

            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,

            'tickets' => $tickets,
        ]);
    }
}