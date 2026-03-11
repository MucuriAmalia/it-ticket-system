<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Ticket</h2>
    </x-slot>

    @php
        $hqExtensions = [
            '700' => 'Reception',
            '701' => 'Customer Care',
            '702' => 'CEO',
            '703' => 'Executive Assistant - Justa',
            '704' => 'Board Room',
            '705' => 'Conference Room',
            '706' => 'Kitchen',
            '707' => 'HR Manager',
            '708' => 'HR Assistant - Lydia',
            '709' => 'Administration Room',
            '710' => 'Registry',
            '711' => 'CFO - Liz',
            '712' => 'Finance - Clement',
            '713' => 'Finance Assistants',
            '714' => 'Finance - Elias',
            '715' => 'Finance - Belinda',
            '716' => 'Finance Interns Room',
            '717' => 'MIS - Betty/Ruth',
            '718' => 'MIS Assistants',
            '719' => 'Legal Manager - Christabel',
            '720' => 'Legal Assistants',
            '721' => 'Marketing Manager - Backson',
            '722' => 'Chief Operations Officer - Mugendi',
            '723' => 'COO Assistant - Christine',
            '724' => 'Operations Manager - Simon',
            '725' => 'Operations Manager - Cecilia',
            '726' => 'ICT Manager - Marangu',
            '727' => 'ICT Officer - Kamau',
            '728' => 'ICT Assistants',
            '729' => 'Audit Manager - Edward',
            '730' => 'Audit Assistants',
            '731' => 'Credit Analyst - Amos',
            '799' => 'Gate - Security',
        ];

        $branchExtensions = [
            '801' => 'Kiritiri',
            '802' => 'Siakago',
            '803' => 'Embu',
            '804' => 'Thika',
            '805' => 'Kiambu',
            '806' => 'Kikuyu',
            '807' => 'Utawala',
            '808' => 'Kasarani',
            '809' => 'Nairobi',
            '810' => 'Kitengela',
            '811' => 'Rongai',
            '812' => 'Tala',
            '813' => 'Machakos',
            '814' => 'Makueni',
            '815' => 'Emali',
            '816' => 'Loitoktok',
            '817' => 'Kibwezi',
            '818' => 'Voi',
            '819' => 'Taita Taveta',
            '820' => 'Kitui',
            '821' => 'Zombe',
            '822' => 'Mwingi',
            '823' => 'Matuu',
            '824' => 'Nakuru',
            '825' => 'Nyahururu',
            '826' => 'Kerugoya',
            '827' => 'Nyeri',
            '828' => 'Nanyuki',
            '829' => 'Nkubu',
            '830' => 'Meru',
            '831' => 'Maua',
            '832' => 'Laare',
            '833' => 'Ruinyenjes',
            '834' => 'Chuka',
            '835' => 'Marimanti',
            '836' => 'Karatina',
        ];
    @endphp

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium">Title</label>
                        <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title') }}">
                        @error('title')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Description</label>
                        <textarea name="description" class="w-full border rounded p-2" rows="5">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

<div>
    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>

    <select name="category_id" id="category_id"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

        <option value="">Select Category</option>

        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach

    </select>
</div>

                    <div class="mb-4">
                        <label class="block font-medium">Priority</label>
                        <select name="priority" class="w-full border rounded p-2">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
    <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
    <select name="department_id" id="department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        <option value="">Select Department</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
        @endforeach
    </select>
    @error('department_id')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

                    <div class="mb-4">
                        <label class="block font-medium">Ticket Origin</label>
                        <select name="site_type" id="site_type" class="w-full border rounded p-2">
                            <option value="">Select Origin</option>
                            <option value="hq" {{ old('site_type') == 'hq' ? 'selected' : '' }}>Head Office (HQ)</option>
                            <option value="branch" {{ old('site_type') == 'branch' ? 'selected' : '' }}>Branch</option>
                        </select>
                        @error('site_type')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4" id="hq_extension_wrapper" style="display: none;">
                        <label class="block font-medium">HQ Office / Extension</label>
                        <select name="hq_extension" id="hq_extension" class="w-full border rounded p-2">
                            <option value="">Select HQ Extension</option>
                            @foreach($hqExtensions as $extension => $name)
                                <option value="{{ $extension }}" data-name="{{ $name }}" {{ old('hq_extension') == $extension ? 'selected' : '' }}>
                                    {{ $extension }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4" id="branch_extension_wrapper" style="display: none;">
                        <label class="block font-medium">Branch / Extension</label>
                        <select name="branch_extension" id="branch_extension" class="w-full border rounded p-2">
                            <option value="">Select Branch Extension</option>
                            @foreach($branchExtensions as $extension => $name)
                                <option value="{{ $extension }}" data-name="{{ $name }}" {{ old('branch_extension') == $extension ? 'selected' : '' }}>
                                    {{ $extension }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="source_name" id="source_name" value="{{ old('source_name') }}">
                    <input type="hidden" name="extension_number" id="extension_number" value="{{ old('extension_number') }}">

                    @error('source_name')
                        <div class="text-red-600 text-sm mb-2">{{ $message }}</div>
                    @enderror

                    @error('extension_number')
                        <div class="text-red-600 text-sm mb-4">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Submit Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const siteType = document.getElementById('site_type');
            const hqWrapper = document.getElementById('hq_extension_wrapper');
            const branchWrapper = document.getElementById('branch_extension_wrapper');
            const hqSelect = document.getElementById('hq_extension');
            const branchSelect = document.getElementById('branch_extension');
            const sourceNameInput = document.getElementById('source_name');
            const extensionInput = document.getElementById('extension_number');

            function resetSelections() {
                sourceNameInput.value = '';
                extensionInput.value = '';
            }

            function toggleOriginFields() {
                hqWrapper.style.display = 'none';
                branchWrapper.style.display = 'none';

                if (siteType.value === 'hq') {
                    hqWrapper.style.display = 'block';
                    branchSelect.value = '';
                } else if (siteType.value === 'branch') {
                    branchWrapper.style.display = 'block';
                    hqSelect.value = '';
                } else {
                    hqSelect.value = '';
                    branchSelect.value = '';
                    resetSelections();
                }
            }

            function syncSourceDetails() {
                let selectedOption = null;

                if (siteType.value === 'hq' && hqSelect.value) {
                    selectedOption = hqSelect.options[hqSelect.selectedIndex];
                }

                if (siteType.value === 'branch' && branchSelect.value) {
                    selectedOption = branchSelect.options[branchSelect.selectedIndex];
                }

                if (selectedOption) {
                    extensionInput.value = selectedOption.value;
                    sourceNameInput.value = selectedOption.getAttribute('data-name');
                } else {
                    resetSelections();
                }
            }

            siteType.addEventListener('change', function () {
                toggleOriginFields();
                syncSourceDetails();
            });

            hqSelect.addEventListener('change', syncSourceDetails);
            branchSelect.addEventListener('change', syncSourceDetails);

            toggleOriginFields();
            syncSourceDetails();
        });
    </script>
</x-app-layout>