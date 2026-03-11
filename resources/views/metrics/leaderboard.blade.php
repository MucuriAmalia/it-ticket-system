<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Technician Leaderboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Top Performing Technicians</h3>

                    <a href="{{ route('dashboard') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Back to Dashboard
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="text-left p-3">Rank</th>
                                <th class="text-left p-3">Technician</th>
                                <th class="text-left p-3">Resolved</th>
                                <th class="text-left p-3">Assigned</th>
                                <th class="text-left p-3">Open</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($technicians as $index => $technician)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-bold">
                                        @if($index === 0)
                                            🥇 1
                                        @elseif($index === 1)
                                            🥈 2
                                        @elseif($index === 2)
                                            🥉 3
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="p-3">{{ $technician->name }}</td>
                                    <td class="p-3">{{ $technician->resolved_tickets_count }}</td>
                                    <td class="p-3">{{ $technician->assigned_tickets_count }}</td>
                                    <td class="p-3">{{ $technician->open_tickets_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">
                                        No technicians found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>