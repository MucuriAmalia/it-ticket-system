<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tickets
            </h2>
            <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create Ticket</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2 text-left">Ticket No</th>
                            <th class="p-2 text-left">Title</th>
                            <th class="p-2 text-left">Category</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Priority</th>
                            <th class="p-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr class="border-b">
                                <td class="p-2">{{ $ticket->ticket_number }}</td>
                                <td class="p-2">{{ $ticket->title }}</td>
                                <td class="p-2">{{ $ticket->category->name ?? '-' }}</td>
                                <td class="p-2">{{ $ticket->status }}</td>
                                <td class="p-2">{{ $ticket->priority }}</td>
                                <td class="p-2">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 mr-2">View</a>
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="text-yellow-600">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-2">No tickets available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>