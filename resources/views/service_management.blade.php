<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SUHAY - Volunteer Management</title>

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

    {{-- SIDEBAR --}}
     @include('components.nav')

    <div class="flex-1 p-8">

        {{-- HEADER --}}
        @include('components.header', ['title' => 'Volunteer Management'])


        {{-- ============================= --}}
        {{-- NAVIGATION TABS --}}
        {{-- ============================= --}}

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <a href="/service-management"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Volunteer Lists
            </a>

            <a href="/applications"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Applications
            </a>

            <a href="/assignments"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Assignments
            </a>

            <a href="/events"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Events
            </a>

            <a href="/track-activity"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Track Activity
            </a>

        </div>


        {{-- ============================= --}}
        {{-- VOLUNTEER STATISTICS --}}
        {{-- ============================= --}}

        <div class="bg-gray-300 rounded-xl p-6 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="font-semibold text-gray-700">
                        Total Volunteers:
                    </p>

                    <p class="text-2xl font-bold text-[#0e243a] mt-1">
                        {{ $volunteers->count() }}
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-gray-700">
                        Active Volunteers:
                    </p>

                    <p class="text-2xl font-bold text-[#0e243a] mt-1">
                        {{ $volunteers->count() }}
                    </p>
                </div>

            </div>

        </div>


        {{-- ============================= --}}
        {{-- SEARCH --}}
        {{-- ============================= --}}

        <div class="flex justify-center mb-4">

            <form method="GET"
                  action="/service-management"
                  class="flex w-full md:w-1/3">

                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search Volunteer...."
                    class="w-full p-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#0e243a]"
                >

                <button
                    type="submit"
                    class="bg-white px-4 py-2 rounded-r-md flex items-center justify-center">

                    <img
                        src="/images/searchbar.png"
                        class="w-9 h-8"
                        alt="Search"
                    >

                </button>

            </form>

        </div>


        {{-- ============================= --}}
        {{-- SKILL FILTER --}}
        {{-- ============================= --}}

        <div class="flex items-center justify-center gap-4 mb-6 flex-wrap">

            <p class="font-semibold text-gray-700">
                Filter:
            </p>

            <select
                id="skillFilter"
                class="border p-2 rounded-md">

                <option value="">
                    All Skills
                </option>

                @foreach($skills as $skill)

                    <option
                        value="{{ strtolower($skill) }}"
                        {{ strtolower(request('search_skill')) == strtolower($skill) ? 'selected' : '' }}>

                        {{ $skill }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- ============================= --}}
        {{-- VOLUNTEER LIST --}}
        {{-- ============================= --}}

        <div
            id="volunteerGrid"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

            @forelse($volunteers as $volunteer)

                <div
                    class="volunteer-card bg-[#4a5568] text-white rounded-2xl p-6 text-center
                           hover:scale-105 hover:shadow-xl transition-all duration-300">


                    {{-- PROFILE ICON --}}
                    <div
                        class="w-20 h-20 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">

                        <svg
                            class="w-10 h-10 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                   M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>

                        </svg>

                    </div>


                    {{-- NAME --}}
                    <h3 class="font-bold text-lg">

                        {{ $volunteer->first_name }}
                        {{ $volunteer->last_name }}

                    </h3>


                    {{-- BASIC INFORMATION --}}
                    <div
                        class="text-xs mt-4 text-left space-y-2
                               bg-[#3b4252] p-3 rounded-lg">

                        <p>
                            <span class="font-semibold">
                                Email:
                            </span>

                            {{ $volunteer->email ?: 'N/A' }}
                        </p>

                        <p>
                            <span class="font-semibold">
                                Phone:
                            </span>

                            {{ $volunteer->contact_number ?: 'N/A' }}
                        </p>

                        <p>
                            <span class="font-semibold">
                                Skills:
                            </span>

                            {{ $volunteer->skills ?: 'N/A' }}
                        </p>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="flex justify-between mt-4 gap-2">

                        {{-- VIEW --}}
                        <button
                            type="button"
                            onclick='openModal(@json($volunteer))'
                            class="bg-blue-500 hover:bg-blue-600 text-white
                                   px-4 py-2 rounded-full text-xs font-semibold">

                            View

                        </button>


                        {{-- DEACTIVATE --}}
                        <button
                            type="button"
                            onclick="deactivateVolunteer({{ $volunteer->account_id }})"
                            class="bg-red-500 hover:bg-red-600 text-white
                                   px-4 py-2 rounded-full text-xs font-semibold">

                            Deactivate

                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-12">

                    <p class="text-gray-600 text-lg font-semibold">
                        No volunteers found.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- ============================= --}}
{{-- MODALS --}}
{{-- ============================= --}}

@include('components.application-modal')

@include('components.logout-modal')


{{-- ============================= --}}
{{-- SKILL FILTER SCRIPT --}}
{{-- ============================= --}}

<script>

document.getElementById('skillFilter').addEventListener('change', function () {

    const skill = this.value;

    const search = document.getElementById('searchInput').value;

    let url = `/service-management?`;

    if (search) {
        url += `search=${encodeURIComponent(search)}&`;
    }

    if (skill) {
        url += `search_skill=${encodeURIComponent(skill)}`;
    }

    fetch(url)
        .then(response => response.text())
        .then(html => {

            const parser = new DOMParser();

            const doc = parser.parseFromString(html, 'text/html');

            const newGrid = doc.getElementById('volunteerGrid');

            if (newGrid) {

                document.getElementById('volunteerGrid').innerHTML =
                    newGrid.innerHTML;

            }

        })
        .catch(error => {

            console.error('Filter error:', error);

        });

});

</script>


{{-- ============================= --}}
{{-- LOGOUT MODAL --}}
{{-- ============================= --}}

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

</script>


{{-- ============================= --}}
{{-- VOLUNTEER MODAL --}}
{{-- ============================= --}}

<script>

function openModal(app) {

    const modal = document.getElementById('appModal');

    const box = document.getElementById('modalBox');

    if (box) {
        box.classList.remove('scale-95');
    }


    // =============================
    // PERSONAL INFORMATION
    // =============================

    const firstName =
        document.getElementById('first_name');

    const lastName =
        document.getElementById('last_name');

    const address =
        document.getElementById('address');

    const contact =
        document.getElementById('contact');

    const email =
        document.getElementById('email');

    const dob =
        document.getElementById('dob');


    if (firstName) {
        firstName.innerText =
            app.first_name ?? '---';
    }

    if (lastName) {
        lastName.innerText =
            app.last_name ?? '---';
    }

    if (address) {
        address.innerText =
            app.address ?? '---';
    }

    if (contact) {
        contact.innerText =
            app.contact_number ?? '---';
    }

    if (email) {
        email.innerText =
            app.email ?? '---';
    }

    if (dob) {
        dob.innerText =
            app.birth_date ?? '---';
    }


    // =============================
    // APPLICATION INFORMATION
    // =============================

    const applicationDate =
        document.getElementById('application_date');

    const skills =
        document.getElementById('skills_text');

    const remarks =
        document.getElementById('remarks_text');


    if (applicationDate) {
        applicationDate.innerText =
            app.application_date ?? '---';
    }

    if (skills) {
        skills.innerText =
            app.skills ?? '---';
    }

    if (remarks) {
        remarks.innerText =
            app.remarks ?? '---';
    }


    // =============================
    // SHOW MODAL
    // =============================

    if (modal) {

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }

}

</script>


{{-- ============================= --}}
{{-- CLOSE MODAL / PAGINATION --}}
{{-- ============================= --}}

<script>

function closeModal() {

    const modal =
        document.getElementById('appModal');

    const box =
        document.getElementById('modalBox');


    if (box) {

        box.classList.add('scale-95');

    }


    setTimeout(() => {

        if (modal) {

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }

    }, 150);

}


function nextPage() {

    const page1 =
        document.getElementById('page1');

    const page2 =
        document.getElementById('page2');


    if (page1) {

        page1.classList.add('hidden');

    }

    if (page2) {

        page2.classList.remove('hidden');

    }

}


function prevPage() {

    const page1 =
        document.getElementById('page1');

    const page2 =
        document.getElementById('page2');


    if (page2) {

        page2.classList.add('hidden');

    }

    if (page1) {

        page1.classList.remove('hidden');

    }

}

</script>


{{-- ============================= --}}
{{-- DEACTIVATE VOLUNTEER --}}
{{-- ============================= --}}

<script>

function deactivateVolunteer(id) {

    if (!confirm('Are you sure you want to deactivate this volunteer?')) {
        return;
    }


    console.log('Sending account ID:', id);


    fetch(`/volunteers/deactivate/${id}`, {

        method: 'PATCH',

        headers: {

            'X-CSRF-TOKEN':
                '{{ csrf_token() }}',

            'Accept':
                'application/json'

        }

    })

    .then(async response => {

        console.log('STATUS:', response.status);

        const text =
            await response.text();

        console.log(
            'RAW RESPONSE:',
            text
        );


        let data;

        try {

            data = JSON.parse(text);

        } catch (error) {

            throw new Error(
                'Server returned an invalid response.'
            );

        }


        if (!response.ok) {

            throw new Error(
                data.message ||
                'Failed to deactivate volunteer.'
            );

        }


        return data;

    })

    .then(data => {

        console.log(
            'RESPONSE:',
            data
        );

        alert(
            data.message ||
            'Volunteer deactivated successfully.'
        );

        location.reload();

    })

    .catch(error => {

        console.error(
            'ERROR:',
            error
        );

        alert(
            error.message ||
            'Request failed. Please check the console.'
        );

    });

}

</script>

</body>
</html>