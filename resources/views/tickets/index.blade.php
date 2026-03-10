    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tickets
                </h2>
                <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Create Ticket
                </a>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
        <div class="bg-white shadow rounded p-4 mb-6">
            <form method="GET" action="{{ route('tickets.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                    <div>
                        <label class="block text-sm font-medium">Search</label>
                        <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Ticket No / Title"
                        class="w-full border rounded p-2">
                        </div>

                    <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <option value="open" {{ request('status')=='open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status')=='in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status')=='resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status')=='closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Priority</label>
                    <select name="priority" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <option value="low" {{ request('priority')=='low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority')=='medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority')=='high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('priority')=='critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Department</label>
                    <select name="department_id" class="w-full border rounded p-2">
                        <option value="">All</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Origin</label>
                    <select name="site_type" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <option value="hq" {{ request('site_type')=='hq' ? 'selected' : '' }}>HQ</option>
                        <option value="branch" {{ request('site_type')=='branch' ? 'selected' : '' }}>Branch</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Filter
                    </button>

                    <a href="{{ route('tickets.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                        Reset
                    </a>
                </div>

                </div>
            </form>
        </div>
                <div class="bg-white shadow rounded overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr class="border-b">
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Ticket No</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Title</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Category</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Department</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Origin</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Source</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Extension</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Priority</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Created By</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Assigned To</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->ticket_number }}
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->title }}
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->category->name ?? '-' }}
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->department->name ?? '-' }}
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        @if($ticket->site_type === 'hq')
                                            HQ
                                        @elseif($ticket->site_type === 'branch')
                                            Branch
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->source_name ?? '-' }}
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->extension_number ?? '-' }}
                                    </td>

                                    <td class="p-3 text-sm">
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

                                    <td class="p-3 text-sm">
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

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->user->name ?? '-' }}
                                    </td>

                                    <td class="p-3 text-sm text-gray-800">
                                        {{ $ticket->assignee->name ?? 'Unassigned' }}
                                    </td>

                <td class="p-3 text-sm">
                    <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:underline mr-3">
                                        View
        </a>

        @if(
            auth()->user()->role === 'admin' ||
            $ticket->user_id === auth()->id() ||
            $ticket->assigned_to === auth()->id()
        )
            <a href="{{ route('tickets.edit', $ticket) }}" class="text-yellow-600 hover:underline">
                Edit
            </a>
        @endif
    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="p-4 text-center text-gray-500">
                                        No tickets available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-app-layout>