<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 shadow rounded">Open: {{ $openTickets }}</div>
                <div class="bg-white p-4 shadow rounded">In Progress: {{ $inProgressTickets }}</div>
                <div class="bg-white p-4 shadow rounded">Resolved: {{ $resolvedTickets }}</div>
                <div class="bg-white p-4 shadow rounded">Closed: {{ $closedTickets }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Recent Tickets</h3>
                    <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">New Ticket</a>
                </div>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Ticket No</th>
                            <th class="text-left p-2">Title</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-left p-2">Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr class="border-b">
                                <td class="p-2">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                </td>
                                <td class="p-2">{{ $ticket->title }}</td>
                                <td class="p-2">{{ $ticket->status }}</td>
                                <td class="p-2">{{ $ticket->priority }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-2">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>