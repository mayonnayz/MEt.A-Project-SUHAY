<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Archived NGO Accounts</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
          rel="stylesheet">

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


        {{-- NGO NAVIGATION --}}

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


        {{-- MAIN CARD --}}

        <div class="bg-white rounded-2xl overflow-hidden">


            {{-- TOOLBAR --}}

            <div class="bg-gray-100 p-4 flex items-center gap-3 flex-wrap">


                {{-- TITLE --}}

                <div class="mr-auto">

                    <h1 class="text-xl font-bold text-[#0e243a]">
                        Archived Accounts
                    </h1>

                </div>


                {{-- ROLE FILTER --}}

                <form action="{{ route('ngo-accounts.archived') }}"
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

                <form action="{{ route('ngo-accounts.archived') }}"
                      method="GET"
                      id="archivedSearchForm">

                    @if(request('role'))

                        <input type="hidden"
                               name="role"
                               value="{{ request('role') }}">

                    @endif


                    <input type="text"
                           name="search"
                           id="archivedSearchInput"
                           value="{{ request('search') }}"
                           placeholder="Search..."
                           autocomplete="off"
                           class="w-56 border-[1.5px] border-indigo-300 rounded-lg px-3.5 py-2 text-sm outline-none focus:border-indigo-400">

                </form>


                {{-- BACK TO ACTIVE --}}

                <a href="{{ route('ngo-accounts') }}"
                   class="bg-[#f2c94c] text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm whitespace-nowrap">

                    Back to Accounts

                </a>


            </div>


            {{-- SUCCESS MESSAGE --}}

            @if(session('success'))

                <div class="mx-5 mt-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">

                    {{ session('success') }}

                </div>

            @endif


            {{-- TABLE --}}

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">


                    <thead>

                        <tr class="border-b border-gray-200">

                            <th class="w-14 text-left px-5 py-4 text-sm text-[#0e243a]">
                                #
                            </th>


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


                                {{-- NUMBER --}}

                                <td class="px-5 py-3.5 text-sm text-gray-500">

                                    {{ $index + 1 }}

                                </td>


                                {{-- NAME --}}

                                <td class="px-5 py-3.5 text-sm text-[#0e243a]">

                                    {{ $account->first_name }}
                                    {{ $account->last_name }}

                                </td>


                                {{-- ROLE --}}

                                <td class="px-5 py-3.5 text-sm text-[#0e243a]">

                                    {{ $account->role_label }}

                                </td>


                                {{-- ACTION --}}

                                <td class="px-5 py-3.5">

                                    <form action="{{ route('ngo-accounts.restore', $account->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Restore this account?')">

                                        @csrf

                                        @method('PATCH')


                                        <button type="submit"
                                                class="bg-[#f2c94c] text-[#0e243a] px-4 py-2 rounded-lg font-semibold text-sm">

                                            Restore

                                        </button>

                                    </form>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center px-5 py-10 text-sm text-gray-500">

                                    No archived accounts found.

                                </td>

                            </tr>

                        @endforelse


                    </tbody>

                </table>

            </div>


        </div>

    </div>

</div>


@include('components.logout-modal')


<script>

function openLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.remove('hidden');

    document
        .getElementById('logoutModal')
        .classList.add('flex');

}


function closeLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.add('hidden');

}

(function () {

    const input =
        document.getElementById('archivedSearchInput');

    const form =
        document.getElementById('archivedSearchForm');

    let timer = null;


    if (!input || !form) {
        return;
    }


    input.addEventListener('input', function () {

        clearTimeout(timer);


        timer = setTimeout(function () {

            form.submit();

        }, 300);

    });


    if (input.value.length > 0) {

        input.focus();

        input.setSelectionRange(
            input.value.length,
            input.value.length
        );

    }

})();

</script>


</body>

</html>

