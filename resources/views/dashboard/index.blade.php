<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="space-y-8">

    {{-- Ticket Overview --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Ticket Overview</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="bg-white p-4 shadow rounded-lg">
                <p class="text-sm text-gray-500">Total Tickets</p>
                <p class="text-2xl font-bold">{{ $totalTickets }}</p>
            </div>

            <div class="bg-blue-100 p-4 shadow rounded-lg">
                <p class="text-sm text-blue-700">Open</p>
                <p class="text-2xl font-bold text-blue-700">{{ $openTickets }}</p>
            </div>

            <div class="bg-yellow-100 p-4 shadow rounded-lg">
                <p class="text-sm text-yellow-700">In Progress</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $inProgressTickets }}</p>
            </div>

            <div class="bg-green-100 p-4 shadow rounded-lg">
                <p class="text-sm text-green-700">Resolved</p>
                <p class="text-2xl font-bold text-green-700">{{ $resolvedTickets }}</p>
            </div>

            <div class="bg-gray-200 p-4 shadow rounded-lg">
                <p class="text-sm text-gray-700">Closed</p>
                <p class="text-2xl font-bold text-gray-700">{{ $closedTickets }}</p>
            </div>

            <div class="bg-red-100 p-4 shadow rounded-lg">
                <p class="text-sm text-red-700">Critical</p>
                <p class="text-2xl font-bold text-red-700">{{ $criticalTickets }}</p>
            </div>
        </div>
    </div>

    {{-- Technician Leaderboard Button --}}
<div class="mt-6 mb-6">
    <a href="{{ route('metrics.leaderboard') }}"
       class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        Technician Leaderboard
    </a>
</div>

    {{-- Location Analytics --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-6">Location Analytics</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">HQ Tickets</span>
                    <span class="font-bold">{{ $hqTickets }}</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $hqPercentage }}%"></div>
                </div>

                <p class="text-sm text-gray-500 mt-2">{{ $hqPercentage }}%</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Branch Tickets</span>
                    <span class="font-bold">{{ $branchTickets }}</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-green-600 h-4 rounded-full" style="width: {{ $branchPercentage }}%"></div>
                </div>

                <p class="text-sm text-gray-500 mt-2">{{ $branchPercentage }}%</p>
            </div>
        </div>
    </div>

    {{-- Ticket Trends Chart --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-6">Ticket Trends (Last 7 Days)</h3>

        <div class="space-y-4">
            @foreach($trendLabels as $index => $label)
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>{{ $label }}</span>
                        <span>{{ $trendValues[$index] }}</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div
                            class="bg-indigo-600 h-4 rounded-full"
                            style="width: {{ max($trendValues) > 0 ? ($trendValues[$index] / max($trendValues)) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<div class="bg-white shadow rounded-lg p-4 mt-6 mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Technician Performance</h3>
        <p class="text-sm text-gray-500">View technician leaderboard based on resolved tickets.</p>
    </div>

    <a href="{{ route('metrics.leaderboard') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        View Leaderboard
    </a>
</div>

{{-- Recent Tickets --}}
<div class="bg-white shadow rounded-lg p-6">

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Recent Tickets</h3>

        <a href="{{ route('tickets.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            New Ticket
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="border-b bg-gray-100">
                    <th class="text-left p-2">Ticket</th>
                    <th class="text-left p-2">Title</th>
                    <th class="text-left p-2">Department</th>
                    <th class="text-left p-2">Origin</th>
                    <th class="text-left p-2">Status</th>
                    <th class="text-left p-2">Priority</th>
                    <th class="text-left p-2">Created</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($tickets as $ticket)
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-2">
                            <a href="{{ route('tickets.show', $ticket) }}"
                               class="text-blue-600 hover:underline">
                                {{ $ticket->ticket_number }}
                            </a>
                        </td>

                        <td class="p-2">
                            {{ $ticket->title }}
                        </td>

                        <td class="p-2">
                            {{ $ticket->department->name ?? '-' }}
                        </td>

                        <td class="p-2">
                            {{ $ticket->site_type_label ?? '-' }}
                        </td>

                        <td class="p-2">
                            @php
                                $statusClasses = match($ticket->status) {
                                    'open' => 'bg-blue-100 text-blue-700',
                                    'in_progress' => 'bg-yellow-100 text-yellow-700',
                                    'resolved' => 'bg-green-100 text-green-700',
                                    'closed' => 'bg-gray-200 text-gray-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClasses }}">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                        </td>

                        <td class="p-2">
                            @php
                                $priorityClasses = match($ticket->priority) {
                                    'low' => 'bg-gray-100 text-gray-700',
                                    'medium' => 'bg-blue-100 text-blue-700',
                                    'high' => 'bg-orange-100 text-orange-700',
                                    'critical' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <span class="px-2 py-1 rounded text-xs font-medium {{ $priorityClasses }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>

                        <td class="p-2">
                            {{ $ticket->created_at->diffForHumans() }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-3 text-gray-500">
                            No recent tickets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="py-6">
    <a href="{{ route('tickets.index') }}"
       class="text-blue-600 hover:underline">
        View All Tickets
    </a>
    </div>
</x-app-layout>