<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">{{ $ticket->ticket_number }} - {{ $ticket->title }}</h3>

                <p><strong>Description:</strong> {{ $ticket->description }}</p>
                <p><strong>Category:</strong> {{ $ticket->category->name ?? '-' }}</p>
                <p><strong>Priority:</strong> {{ $ticket->priority }}</p>
                <p><strong>Status:</strong> {{ $ticket->status }}</p>
                <p><strong>Department:</strong> {{ $ticket->department->name ?? '-' }}</p>
                <p><strong>Reported By:</strong> {{ $ticket->user->name ?? '-' }}</p>
                <p><strong>Assigned To:</strong> {{ $ticket->assignee->name ?? 'Unassigned' }}</p>
                <p><strong>Resolution Notes:</strong> {{ $ticket->resolution_notes ?? 'N/A' }}</p>

                <div class="mt-4">
                    <a href="{{ route('tickets.edit', $ticket) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit Ticket</a>
                </div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h4 class="text-lg font-bold mb-4">Activity Log</h4>
                @forelse($ticket->activityLogs as $log)
                    <div class="border-b py-2">
                        <p><strong>{{ $log->action }}</strong> by {{ $log->user->name ?? 'System' }}</p>
                        <p>Old Value: {{ $log->old_value ?? '-' }}</p>
                        <p>New Value: {{ $log->new_value ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $log->created_at }}</p>
                    </div>
                @empty
                    <p>No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>