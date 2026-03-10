<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket Details
            </h2>

            <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:underline">
                ← Back to Tickets
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Ticket Overview --}}
            <div class="bg-white shadow rounded p-6 mb-6">

                <h3 class="text-xl font-bold mb-4">
                    {{ $ticket->ticket_number }} - {{ $ticket->title }}
                </h3>

                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div>
                        <strong>Category:</strong>
                        {{ $ticket->category->name ?? '-' }}
                    </div>

                    <div>
                        <strong>Department:</strong>
                        {{ $ticket->department->name ?? '-' }}
                    </div>

                    <div>
                        <strong>Origin:</strong>
                        @if($ticket->site_type === 'hq')
                            HQ
                        @elseif($ticket->site_type === 'branch')
                            Branch
                        @else
                            -
                        @endif
                    </div>

                    <div>
                        <strong>Source:</strong>
                        {{ $ticket->source_name ?? '-' }}
                    </div>

                    <div>
                        <strong>Extension:</strong>
                        {{ $ticket->extension_number ?? '-' }}
                    </div>

                    <div>
                        <strong>Reported By:</strong>
                        {{ $ticket->user->name ?? '-' }}
                    </div>

                    <div>
                        <strong>Assigned To:</strong>
                        {{ $ticket->assignee->name ?? 'Unassigned' }}
                    </div>

                    <div>
                        <strong>Status:</strong>

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
                    </div>

                    <div>
                        <strong>Priority:</strong>

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
                    </div>

                </div>

                <div class="mt-6">
                    <strong>Description</strong>
                    <p class="mt-2 text-gray-700">
                        {{ $ticket->description }}
                    </p>
                </div>

                <div class="mt-6">
                    <strong>Resolution Notes</strong>
                    <p class="mt-2 text-gray-700">
                        {{ $ticket->resolution_notes ?? 'No resolution notes yet.' }}
                    </p>
                </div>

<div class="mt-6 flex gap-3">
    @if(
        auth()->user()->role === 'admin' ||
        $ticket->user_id === auth()->id() ||
        $ticket->assigned_to === auth()->id()
    )
    
        <a href="{{ route('tickets.edit', $ticket) }}"
           class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
            Edit Ticket
        </a>
    @endif
</div>

            </div>


            {{-- Activity Logs --}}
            <div class="bg-white shadow rounded p-6">

                <h4 class="text-lg font-bold mb-4">
                    Activity Log
                </h4>

                @forelse($ticket->activityLogs as $log)

                    <div class="border-b py-3 text-sm">

                        <p class="font-semibold">
                            {{ str_replace('_', ' ', ucfirst($log->action)) }}
                            by {{ $log->user->name ?? 'System' }}
                        </p>

                        @if($log->old_value)
                            <p class="text-gray-600">
                                <strong>Old:</strong> {{ $log->old_value }}
                            </p>
                        @endif

                        @if($log->new_value)
                            <p class="text-gray-600">
                                <strong>New:</strong> {{ $log->new_value }}
                            </p>
                        @endif

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </p>

                    </div>

                @empty
                    <p class="text-gray-500">No activity recorded yet.</p>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>