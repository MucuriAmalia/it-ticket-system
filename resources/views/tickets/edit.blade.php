<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Ticket</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
<form method="POST" action="{{ route('tickets.update', $ticket) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block font-medium">Title</label>
        <input type="text" name="title" class="w-full border rounded p-2"
               value="{{ old('title', $ticket->title) }}">
    </div>

    <div class="mb-4">
        <label class="block font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2" rows="5">
            {{ old('description', $ticket->description) }}
        </textarea>
    </div>

    <div class="mb-4">
        <label class="block font-medium">Category</label>
        <select name="category_id" class="w-full border rounded p-2">
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ $ticket->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="mb-4">
        <label class="block font-medium">Priority</label>
        <select name="priority" class="w-full border rounded p-2">
            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
            <option value="critical" {{ $ticket->priority == 'critical' ? 'selected' : '' }}>Critical</option>
        </select>
    </div>
    @endif

    @if(auth()->user()->role === 'admin' || $ticket->assigned_to === auth()->id())
    <div class="mb-4">
        <label class="block font-medium">Status</label>
        <select name="status" class="w-full border rounded p-2">
            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </div>
    @endif

    <div class="mb-4">
        <label class="block font-medium">Department</label>
        <select name="department_id" class="w-full border rounded p-2">
            <option value="">Select Department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}"
                    {{ $ticket->department_id == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- NEW: Origin Section --}}
    <div class="mb-4">
        <label class="block font-medium">Origin</label>
        <select name="site_type" class="w-full border rounded p-2">
            <option value="hq" {{ $ticket->site_type == 'hq' ? 'selected' : '' }}>Head Office (HQ)</option>
            <option value="branch" {{ $ticket->site_type == 'branch' ? 'selected' : '' }}>Branch</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="block font-medium">Source Location / Office</label>
        <input type="text" name="source_name"
               class="w-full border rounded p-2"
               value="{{ old('source_name', $ticket->source_name) }}"
               placeholder="Example: ICT Manager Office / Embu Branch">
    </div>

    <div class="mb-4">
        <label class="block font-medium">Extension Number</label>
        <input type="text" name="extension_number"
               class="w-full border rounded p-2"
               value="{{ old('extension_number', $ticket->extension_number) }}"
               placeholder="Example: 726 or 803">
    </div>

    @if(auth()->user()->role === 'admin')
        <div class="mb-4">
            <label class="block font-medium">Assign Technician</label>
            <select name="assigned_to" class="w-full border rounded p-2">
                <option value="">Unassigned</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}"
                        {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                        {{ $admin->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

@if(auth()->user()->role === 'admin' || $ticket->assigned_to === auth()->id())
    <div class="mb-4">
        <label class="block font-medium">Resolution Notes</label>
        <textarea name="resolution_notes"
                  class="w-full border rounded p-2"
                  rows="4">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
    </div>
@endif

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Update Ticket
    </button>

</form>
            </div>
        </div>
    </div>
</x-app-layout>