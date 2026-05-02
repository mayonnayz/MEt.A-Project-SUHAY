<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        td.label { background: #0c2d48; color: white; width: 35%; font-weight: 600; }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-6 md:p-8">
        @include('components.header', ['title' => 'Applications'])

        <div class="bg-white rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">

            <form method="GET" action="/volunteer/applications" class="mb-6 flex flex-col sm:flex-row gap-3">

                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-2xl px-4 py-3 flex-1">
                    <!-- <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Applications..."
                        class="w-full outline-none text-[15px] font-medium placeholder:text-gray-500"/> -->

                        <input type="text" id="searchInput"
    placeholder="Search Applications..."
    class="w-full outline-none text-[15px] font-medium placeholder:text-gray-500"/>

                    <div class="ml-3 w-10 h-10 rounded-xl bg-[#0e243a] flex items-center justify-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>

                <div class="bg-white border-2 border-[#0e243a] rounded-2xl px-6 py-3 flex items-center">
                    <!-- <select name="filter" onchange="this.form.submit()"
                        class="outline-none text-sm font-medium bg-transparent cursor-pointer w-full sm:w-40"> -->
                        <select id="filterType"
    class="outline-none text-sm font-medium bg-transparent cursor-pointer w-full sm:w-40">
                        <option value="">All</option>
                        <option value="current" {{ request('filter')=='current' ? 'selected' : '' }}>Current</option>
                        <option value="past" {{ request('filter')=='past' ? 'selected' : '' }}>Past</option>
                    </select>
                </div>

            </form>

            <div class="space-y-5">

                @foreach($applications as $app)
                     
                     <!-- <div class="application-card rounded-2xl border-2 border-[#0e243a] bg-white p-5 
            transition hover:bg-gray-100 hover:shadow-md cursor-pointer"
     data-event-id="{{ $app['volunteer_event_id'] }}"
     data-name="{{ strtolower($app['event_name'] ?? '') }}"
     data-ngo="{{ strtolower($app['ngo_name'] ?? '') }}"> -->

     <div class="application-card rounded-2xl border-2 border-[#0e243a] bg-white p-5 
    transition hover:bg-gray-100 hover:shadow-md cursor-pointer"
    data-event-id="{{ $app['volunteer_event_id'] }}"
    data-name="{{ strtolower($app['event_name'] ?? '') }}"
    data-ngo="{{ strtolower($app['ngo_name'] ?? '') }}"
    data-status="{{ $app['status'] }}">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <div class="text-lg font-bold text-[#0e243a] mb-2">
                                {{ $app['event_name'] ?? 'Unknown Event' }}
                            </div>

                            <div class="text-sm text-gray-700 space-y-1">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/VolunteerIcons/VDate.png') }}" class="h-5 w-5">
                                    <span>
                                        {{ isset($app['date']) ? \Carbon\Carbon::parse($app['date'])->format('F d, Y') : 'No date' }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/VolunteerIcons/VNGO.png') }}" class="h-5 w-5">
                                    <span>{{ $app['ngo_name'] ?? 'Unknown NGO' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2">

                            @if($app['status'] == 1)
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">APPROVED</span>
                            @elseif($app['status'] == 0)
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">REJECTED</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">PENDING</span>
                            @endif

                            <button
                                class="viewBtn px-6 py-2 rounded-full bg-[#d39a11] text-white font-bold text-sm hover:bg-[#c2870d]"
                                data-first_name="{{ $app['first_name'] ?? '' }}"
                                data-last_name="{{ $app['last_name'] ?? '' }}"
                                data-address="{{ $app['address'] ?? '' }}"
                                data-contact="{{ $app['contact'] ?? '' }}"
                                data-email="{{ $app['email'] ?? '' }}"
                                data-dob="{{ $app['dob'] ?? '' }}"
                                data-date="{{ $app['date'] ?? '' }}"
                                data-availability="{{ $app['availability'] ?? '' }}"
                                data-skills="{{ $app['skills'] ?? '' }}"
                                data-interests="{{ $app['interests'] ?? '' }}"
                                data-experience_details="{{ $app['experience_details'] ?? '' }}"
                                data-has_experience="{{ $app['has_experience'] ?? 0 }}"
                                data-remarks="{{ $app['remarks'] ?? '' }}"
                            >
                                View Application
                            </button>

                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@include('components.logout-modal')

<div id="viewModal" style="display:none;" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-[#f8f8f8] w-[650px] max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-700 p-6">

        <div class="text-center mb-3">
            <img src="{{ asset('images/suhayLogo.png') }}" class="h-28 mx-auto"/>
            <div class="text-sm font-semibold">Volunteer Application Form</div>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Personal Information</div>
            <table class="w-full text-xs border border-gray-400">
                <tr>
                    <td class="label p-1">
                        First Name<br>
                        Last Name<br>
                        Address<br>
                        Contact<br>
                        Email<br>
                        DOB
                    </td>
                    <td class="border p-2 align-top" id="modalPersonal"></td>
                </tr>
            </table>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Application Details</div>
            <table class="w-full text-xs border border-gray-400">
                <tr><td class="label p-1">Application Date</td><td id="modalDate"></td></tr>
                <tr><td class="label p-1">Availability</td><td id="modalAvailability"></td></tr>
                <tr><td class="label p-1">Skills</td><td id="modalSkills"></td></tr>
                <tr><td class="label p-1">Interests</td><td id="modalInterests"></td></tr>
            </table>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Experience</div>
            <table class="w-full text-xs border border-gray-400">
                <tr><td class="label p-1">Has Experience</td><td id="modalHasExperience"></td></tr>
                <tr><td class="label p-1">Details</td><td id="modalExperience"></td></tr>
            </table>
        </div>

        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Motivation</div>
            <table class="w-full text-xs border border-gray-400">
                <tr><td class="label p-1">Reason</td><td id="modalRemarks"></td></tr>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            <button onclick="closeModal()" class="px-8 py-2 rounded-full bg-[#0e243a] text-white font-bold">
                Close
            </button>
        </div>

    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const filterType = document.getElementById('filterType');
const cards = document.querySelectorAll('.application-card');

searchInput.addEventListener('keyup', filterApplications);
filterType.addEventListener('change', filterApplications);

// function filterApplications() {
//     const search = searchInput.value.toLowerCase().trim();
//     const filter = filterType.value;

//     let visible = 0;

//     cards.forEach(card => {
//         const name = card.dataset.name || "";
//         const ngo = card.dataset.ngo || "";

//         const matchSearch = name.includes(search) || ngo.includes(search);

//         // OPTIONAL filter logic (customize if needed)
//         let matchFilter = true;

//         if (filter === "current") {
//             matchFilter = true; // you can replace with real logic later
//         } 
//         else if (filter === "past") {
//             matchFilter = true; // same here
//         }

//         if (matchSearch && matchFilter) {
//     card.classList.remove("hidden");
//     visible++;
// } else {
//     card.classList.add("hidden");
// }
//     });
// }

function filterApplications() {
    const search = searchInput.value.toLowerCase().trim();
    const filter = filterType.value;

    let visible = 0;

    cards.forEach(card => {
        const name = card.dataset.name || "";
        const ngo = card.dataset.ngo || "";
        const status = card.dataset.status; // 👈 important

        const matchSearch = name.includes(search) || ngo.includes(search);

        // COMBO BOX LOGIC
        let matchFilter = true;

        if (filter === "current") {
            matchFilter = status === "1";
        } 
        else if (filter === "past") {
            matchFilter = status === "0";
        }

        if (matchSearch && matchFilter) {
            card.classList.remove("hidden");
            visible++;
        } else {
            card.classList.add("hidden");
        }
    });
}
</script>

<script>

document.querySelectorAll('.viewBtn').forEach(button => {
    button.addEventListener('click', function (e) {

        e.stopPropagation();

        document.getElementById('modalPersonal').innerHTML = `
            ${this.dataset.first_name}<br>
            ${this.dataset.last_name}<br>
            ${this.dataset.address}<br>
            ${this.dataset.contact}<br>
            ${this.dataset.email}<br>
            ${this.dataset.dob}
        `;

        document.getElementById('modalDate').textContent = this.dataset.date;
        document.getElementById('modalAvailability').textContent = this.dataset.availability;
        document.getElementById('modalSkills').textContent = this.dataset.skills;
        document.getElementById('modalInterests').textContent = this.dataset.interests;
        document.getElementById('modalExperience').textContent = this.dataset.experience_details;
        document.getElementById('modalHasExperience').textContent = this.dataset.has_experience == "1" ? "Yes" : "No";
        document.getElementById('modalRemarks').textContent = this.dataset.remarks || 'N/A';

        document.getElementById('viewModal').style.display = 'flex';
    });
});

function closeModal() {
    document.getElementById('viewModal').style.display = 'none';
}

document.addEventListener("DOMContentLoaded", function () {
    const highlightId = new URLSearchParams(window.location.search).get("highlight");

    if (highlightId) {
        const target = document.querySelector(`[data-event-id='${highlightId}']`);
        if (target) {
            target.classList.add("bg-yellow-100", "ring-4", "ring-yellow-400");
            target.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
});

</script>

<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>

</body>
</html>