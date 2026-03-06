<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Ticket</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium">Title</label>
                        <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title') }}">
                        @error('title') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Description</label>
                        <textarea name="description" class="w-full border rounded p-2" rows="5">{{ old('description') }}</textarea>
                        @error('description') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Category</label>
                        <select name="category_id" class="w-full border rounded p-2">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Priority</label>
                        <select name="priority" class="w-full border rounded p-2">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Department</label>
                        <select name="department_id" class="w-full border rounded p-2">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Submit Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>