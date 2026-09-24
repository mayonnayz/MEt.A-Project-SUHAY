<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Accounts</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-8">

        @include('components.header', ['title' => 'NGO Profile'])

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-10 flex-wrap">

            <a href="/sm-ngos"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <a href="/ngo-members"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Members
            </a>

            <a href="/ngo-accounts"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Accounts
            </a>

        </div>


        <div class="bg-white rounded-2xl overflow-hidden">

            {{-- TOOLBAR --}}
            <div class="bg-gray-100 p-4 flex items-center gap-3 flex-wrap">

                {{-- LEFT SIDE: ACCOUNT ACTIONS --}}
                <div class="flex items-center gap-3 flex-wrap">

                    {{-- ADD ACCOUNTS --}}
                    <button type="button"
                            onclick="openAddAccountModal()"
                            class="bg-[#f2c94c] text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm whitespace-nowrap">
                        Add Accounts
                    </button>


                    {{-- IMPORT ACCOUNTS --}}
                    <form action="{{ route('ngo-accounts.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="csvImportForm">

                        @csrf

                        <input type="file"
                            name="csv"
                            id="csvInput"
                            accept=".csv"
                            class="hidden">

                        <button type="button"
                                onclick="openCsvInstructionsModal()"
                                class="bg-[#f2c94c] text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm whitespace-nowrap">
                            Import Accounts (CSV)
                        </button>
                    </form>

                </div>


                {{-- RIGHT SIDE: FILTER + SEARCH + ARCHIVED --}}
                <div class="flex items-center gap-3 flex-wrap ml-auto">


                    {{-- FILTER --}}
                    <form action="{{ route('ngo-accounts') }}"
                          method="GET"
                          class="flex items-center gap-2 text-sm font-semibold text-gray-600">

                        <label for="role">
                            Filter:
                        </label>

                        <select name="role"
                                id="role"
                                onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-[#0e243a] bg-white">

                            <option value="">
                                Roles
                            </option>

                            @foreach($roles as $value => $label)

                                <option value="{{ $value }}"
                                    @selected(request('role') == $value)>
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                    </form>


                    {{-- SEARCH --}}
                    <form id="searchForm"
                          action="{{ route('ngo-accounts') }}"
                          method="GET"
                          class="flex items-center">

                        @if(request('role'))

                            <input type="hidden"
                                   name="role"
                                   value="{{ request('role') }}">

                        @endif

                        <input type="text"
                               name="search"
                               id="searchInput"
                               value="{{ request('search') }}"
                               placeholder="Search..."
                               autocomplete="off"
                               class="w-56 border-[1.5px] border-indigo-300 rounded-lg px-3.5 py-2 text-sm outline-none focus:border-indigo-400">

                    </form>


                    {{-- ARCHIVED ACCOUNTS --}}
                    <a href="{{ route('ngo-accounts.archived') }}"
                       class="bg-[#0e243a] text-white px-5 py-2.5 rounded-lg font-semibold text-sm whitespace-nowrap hover:bg-[#193957]">
                        Archived Accounts
                    </a>

                </div>

            </div>


            {{-- TABLE --}}
            <table class="w-full border-collapse">

                <thead>

                    <tr class="border-b border-gray-200">

                        <th class="w-14"></th>

                        <th class="text-left px-5 py-4 text-sm text-[#0e243a]">
                            Name
                        </th>

                        <th class="text-left px-5 py-4 text-sm text-[#0e243a]">
                            Role
                        </th>

                        <th class="text-left px-5 py-4 text-sm text-[#0e243a]">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($accounts as $index => $account)

                        <tr class="border-b border-gray-200 last:border-0">

                            <td class="px-5 py-3.5 text-sm text-gray-500">
                                {{ $index + 1 }}
                            </td>


                            <td class="px-5 py-3.5 text-sm text-[#0e243a]">
                                {{ $account->first_name }}
                                {{ $account->last_name }}
                            </td>


                            <td class="px-5 py-3.5 text-sm text-[#0e243a]">
                                {{ $account->role_label }}
                            </td>


                            <td class="px-5 py-3.5">

                                <div class="flex items-center gap-2.5">

                                    {{-- ARCHIVE --}}
                                    <form action="{{ route('ngo-accounts.archive', $account->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Archive this account?')">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="bg-[#f2c94c] text-[#0e243a] px-4 py-2 rounded-lg font-semibold text-sm">
                                            Archive
                                        </button>

                                    </form>


                                    {{-- VIEW --}}
                                    <a href="{{ route('ngo-accounts.show', $account->id) }}"
                                       class="bg-[#f2c94c] text-[#0e243a] px-4 py-2 rounded-lg font-semibold text-sm">
                                        View
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="text-center px-5 py-8 text-sm text-gray-500">

                                No accounts found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@include('components.logout-modal')


<script>

function openLogoutModal() {

    document.getElementById('logoutModal').classList.remove('hidden');

    document.getElementById('logoutModal').classList.add('flex');

}


function closeLogoutModal() {

    document.getElementById('logoutModal').classList.add('hidden');

}




(function () {

    const input = document.getElementById('searchInput');
    const form = document.getElementById('searchForm');

    let timer = null;

    if (!input || !form) return;


    input.addEventListener('input', function () {

        clearTimeout(timer);

        timer = setTimeout(function () {

            form.submit();

        }, 100);

    });


    if (input.value.length > 0) {

        input.focus();

        input.setSelectionRange(
            input.value.length,
            input.value.length
        );

    }

})();

function openCsvInstructionsModal() {
    const modal = document.getElementById('csvInstructionsModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.classList.add('overflow-hidden');
}

function closeCsvInstructionsModal() {
    const modal = document.getElementById('csvInstructionsModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}

function chooseCsvFile() {
    closeCsvInstructionsModal();

    document.getElementById('csvInput').click();
}

document.getElementById('csvInput').addEventListener('change', function () {
    if (this.files.length > 0) {
        document.getElementById('csvImportForm').submit();
    }
});

document.getElementById('csvInstructionsModal').addEventListener('click', function (event) {
    if (event.target === this) {
        closeCsvInstructionsModal();
    }
});

function openCsvResultModal() {
    const modal = document.getElementById('csvResultModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.classList.add('overflow-hidden');
}

function closeCsvResultModal() {
    const modal = document.getElementById('csvResultModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}

</script>

<!-- Add Account Modal -->
<div id="addAccountModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">

    <div class="w-full max-w-2xl rounded-xl bg-white shadow-2xl">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h2 class="text-xl font-bold text-[#0e243a]">
                    Add Account
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Create a Volunteer Manager or Donation Manager account.
                </p>
            </div>

            <button type="button"
                    onclick="closeAddAccountModal()"
                    class="text-gray-400 hover:text-gray-700 text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('ngo-accounts.store') }}" method="POST">
            @csrf

                 @if ($errors->has('email'))
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                @if ($errors->has('general'))
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first('general') }}
                    </div>
                @endif

            <div class="px-6 py-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-[#0e243a] focus:ring-[#0e243a]"
                               placeholder="Enter first name">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-[#0e243a] focus:ring-[#0e243a]"
                               placeholder="Enter last name">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-[#0e243a] focus:ring-[#0e243a]"
                               placeholder="Enter email">
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Role <span class="text-red-500">*</span>
                        </label>

                        <select name="roles"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 bg-white focus:border-[#0e243a] focus:ring-[#0e243a]">

                            <option value="">Select role</option>

                            @foreach($roles as $roleId => $roleName)
                                <option value="{{ $roleId }}"
                                    {{ old('roles') == $roleId ? 'selected' : '' }}>
                                    {{ $roleName }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Birthday -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Birthday
                        </label>

                        <input type="date"
                               name="birth_date"
                               value="{{ old('birth_date') }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-[#0e243a] focus:ring-[#0e243a]">
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Contact Number
                        </label>

                        <input type="text"
                                name="contact_number"
                                id="contact_number"
                                value="{{ old('contact_number') }}"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="15"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-[#0e243a] focus:ring-[#0e243a]"
                                placeholder="Enter contact number"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                </div>

                <!-- Address -->
                <div class="mt-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Address
                    </label>

                    <textarea name="address"
                              rows="2"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-[#0e243a] focus:ring-[#0e243a]"
                              placeholder="Enter address">{{ old('address') }}</textarea>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 border-t px-6 py-4">

                <button type="button"
                        onclick="closeAddAccountModal()"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50">
                    Cancel
                </button>

                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-[#0e243a] text-white font-semibold text-sm hover:bg-[#173a5c]">
                    Add Account
                </button>

            </div>

        </form>

    </div>
</div>

<!-- CSV IMPORT RESULTS MODAL -->
<div id="csvResultModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">

    <div class="w-full max-w-xl rounded-xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h2 class="text-xl font-bold text-[#0e243a]">
                    CSV Import Completed
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Your CSV file has been processed.
                </p>
            </div>

            <button type="button"
                    onclick="closeCsvResultModal()"
                    class="text-gray-400 hover:text-gray-700 text-2xl leading-none">
                &times;
            </button>
        </div>

        {{-- Result --}}
        <div class="px-6 py-5">

            @if (session('import_result'))

                {{-- Imported count --}}
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                    <p class="font-semibold text-green-800">
                        {{ session('import_result.imported') }}
                        account(s) imported successfully.
                    </p>
                </div>

                {{-- Skipped rows --}}
                @if (count(session('import_result.skipped')) > 0)

                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-4">

                        <p class="font-semibold text-red-800">
                            {{ count(session('import_result.skipped') ) }}
                            row(s) were not included:
                        </p>

                        <div class="mt-3 max-h-60 overflow-y-auto">
                            <ul class="space-y-2 text-sm text-red-700">

                                @foreach (session('import_result.skipped') as $skipped)

                                    <li class="rounded-md bg-white border border-red-100 px-3 py-2">
                                        <strong>
                                            Row {{ $skipped['row'] }}:
                                        </strong>

                                        {{ $skipped['reason'] }}
                                    </li>

                                @endforeach

                            </ul>
                        </div>

                    </div>

                @else

                    <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                        <p class="text-sm text-green-700">
                            All valid rows were imported successfully.
                        </p>
                    </div>

                @endif

            @endif

            {{-- Import error --}}
            @if (session('import_error'))

                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                    <p class="font-semibold text-red-800">
                        CSV import failed.
                    </p>

                    <p class="mt-1 text-sm text-red-700">
                        {{ session('import_error') }}
                    </p>
                </div>

            @endif

        </div>

        {{-- Footer --}}
        <div class="flex justify-end border-t px-6 py-4">

            <button type="button"
                    onclick="closeCsvResultModal()"
                    class="px-5 py-2.5 rounded-lg bg-[#0e243a] text-white font-semibold text-sm hover:bg-[#173a5c]">
                Close
            </button>

        </div>

    </div>
</div>


<script>
    function openAddAccountModal() {
        const modal = document.getElementById('addAccountModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeAddAccountModal() {
        const modal = document.getElementById('addAccountModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');
    }

    @if (session('import_result') || session('import_error'))
        openCsvResultModal();
    @endif

    // Close when clicking the dark background
    document.getElementById('addAccountModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeAddAccountModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAddAccountModal();
        }
    });

    // Laravel validation error state
    const hasValidationErrors = {{ $errors->any() ? 'true' : 'false' }};

    // Automatically reopen modal if validation failed
    if (hasValidationErrors) {
        document.addEventListener('DOMContentLoaded', function () {
            openAddAccountModal();
        });
    }
</script>

<!-- CSV Instructions Modal -->
<div id="csvInstructionsModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">

    <div class="w-full max-w-2xl rounded-xl bg-white shadow-2xl">

        <!-- Header -->
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h2 class="text-xl font-bold text-[#0e243a]">
                    Import Accounts from CSV
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Please follow the required CSV format.
                </p>
            </div>

            <button type="button"
                    onclick="closeCsvInstructionsModal()"
                    class="text-gray-400 hover:text-gray-700 text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5">

            <div class="rounded-lg bg-gray-50 border border-gray-200 p-4 mb-5">

                <p class="text-sm font-semibold text-[#0e243a] mb-2">
                    Required column order
                </p>

                <code class="block text-sm bg-white border rounded-lg px-3 py-2 overflow-x-auto">
                    first_name,last_name,email,roles,birth_date,address,contact_number
                </code>

                    <div class="mt-3">
                        <a href="{{ asset('csv\NGO_AddAccounts_Template.csv') }}"
                        download
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:underline">
                            ↓ Download CSV Template
                        </a>
                    </div>

            </div>

            <div class="space-y-4">

                <div>
                    <p class="font-semibold text-sm text-gray-700">
                        Required fields
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        First Name, Last Name, Email, and Role.
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-sm text-gray-700">
                        Accepted roles
                    </p>

                    <ul class="text-sm text-gray-500 list-disc ml-5 mt-1">
                        <li>Volunteer Manager</li>
                        <li>Donation Manager</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-sm text-gray-700">
                        Optional fields
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Birth Date, Address, and Contact Number.
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-sm text-gray-700">
                        Birthday format
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        YYYY-MM-DD
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-sm text-gray-700">
                        Contact number
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Numbers only. Example:
                        <strong>09171234567</strong>
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-sm text-gray-700">
                        Password
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Do not include a password column.
                        The system automatically creates it from the first and last name.
                    </p>

                    <div class="block text-sm bg-white border rounded-lg px-3 py-2 overflow-x-auto">
                        Juan Dela Cruz
                        → juandelacruz
                    </div>
                </div>

                <div class="rounded-lg bg-yellow-50 border border-yellow-200 px-4 py-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Important:</strong>
                        Valid rows will be imported. Invalid rows will be skipped and reported after the import.
                    </p>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 border-t px-6 py-4">

            <button type="button"
                    onclick="closeCsvInstructionsModal()"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50">
                Cancel
            </button>

            <button type="button"
                    onclick="chooseCsvFile()"
                    class="px-5 py-2.5 rounded-lg bg-[#0e243a] text-white font-semibold text-sm hover:bg-[#173a5c]">
                Choose CSV File
            </button>

        </div>

    </div>
</div>

</body>
</html>
