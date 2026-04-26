<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        td.label {
            background: #0c2d48;
            color: white;
            width: 35%;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-8">
       @include('components.header', ['title' => 'Applications'])


        <!-- Outer Panel -->
        <div class="bg-white rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">
            <!-- Search -->
            <form method="GET" action="/volunteer/applications" class="mb-6 flex flex-col sm:flex-row gap-3">

                <!-- SEARCH BOX -->
                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-2xl px-4 py-3 flex-1">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Applications..."
                        class="w-full outline-none text-[15px] font-medium placeholder:text-gray-500"
                    />

                    <!-- SEARCH ICON (RESTORED) -->
                    <div class="ml-3 w-10 h-10 rounded-xl bg-[#0e243a] flex items-center justify-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>

                </div>

                <!-- FILTER DROPDOWN (SEPARATE BOX) -->
                    <div class="bg-white border-2 border-[#0e243a] rounded-2xl px-6 py-3 flex items-center">
                    <select
                        name="filter"
                        onchange="this.form.submit()"
                        class="outline-none text-sm font-medium bg-transparent cursor-pointer w-full sm:w-40 text-left"
                    >
                        <option value="">All</option>
                        <option value="current" {{ request('filter')=='current' ? 'selected' : '' }}>Current</option>
                        <option value="past" {{ request('filter')=='past' ? 'selected' : '' }}>Past</option>
                    </select>
                </div>

            </form>

            <!-- Event Cards Container -->
            <div class="space-y-5">
                @foreach($applications as $app)
                <div class="rounded-2xl border-2 border-[#0e243a] bg-white p-5">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- LEFT -->
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

                        <!-- RIGHT -->
                        <div class="flex flex-col items-end gap-2">

                            <!-- STATUS BADGE -->
                            @if($app['status'] == 1)
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                    APPROVED
                                </span>
                            @elseif($app['status'] == 0)
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                    REJECTED
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">
                                    PENDING
                                </span>
                            @endif

                            <!-- BUTTON -->
<button
class="viewBtn px-6 py-2 rounded-full bg-[#d39a11] text-white font-bold text-sm hover:bg-[#c2870d] transition"

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

<script>

    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }

</script>

<script>
function closeModal() {
    const modal = document.getElementById('viewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

<script>

document.querySelectorAll('.viewBtn').forEach(button => {
    button.addEventListener('click', function () {

        const modal = document.getElementById('viewModal');
        const content = document.getElementById('modalContent');

        content.innerHTML = `
            <tr>
                <td class="label p-1">First Name</td>
                <td class="border p-1">${this.dataset.first_name}</td>
            </tr>
            <tr>
                <td class="label p-1">Last Name</td>
                <td class="border p-1">${this.dataset.last_name}</td>
            </tr>
            <tr>
                <td class="label p-1">Address</td>
                <td class="border p-1">${this.dataset.address}</td>
            </tr>
            <tr>
                <td class="label p-1">Contact Number</td>
                <td class="border p-1">${this.dataset.contact}</td>
            </tr>
            <tr>
                <td class="label p-1">Email Address</td>
                <td class="border p-1">${this.dataset.email}</td>
            </tr>
            <tr>
                <td class="label p-1">Date of Birth</td>
                <td class="border p-1">${this.dataset.dob}</td>
            </tr>

        `;

        document.getElementById('modalDate').textContent = this.dataset.date;
        document.getElementById('modalAvailability').textContent = this.dataset.availability;
        document.getElementById('modalSkills').textContent = this.dataset.skills;
        document.getElementById('modalInterests').textContent = this.dataset.interests;
        document.getElementById('modalExperience').textContent = this.dataset.experience_details;

        const hasExp = this.dataset.has_experience;
        document.getElementById('modalExperienceAnswer').textContent =
            hasExp == "1" ? "Yes" : "No";

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
});
</script>

<!-- VIEW APPLICATION MODAL -->
<div id="viewModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-[#f8f8f8] w-[650px] rounded-2xl border-2 border-gray-700 p-6 relative">

        <!-- HEADER -->
        <div class="text-center mb-3">
            <div class="flex justify-center">
        <img
          src="{{ asset('images/suhayLogo.png') }}" 
          alt="SUHAY"
          class="h-28 w-28 object-contain"
        />
      </div>
            <div class="text-sm font-semibold">Volunteer Application Form</div>
        </div>

        <!-- PERSONAL INFO -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Personal Information</div>

            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tbody id="modalContent">
                    <!-- FILLED BY JS -->
                </tbody>
            </table>
        </div>

        <!-- AVAILABILITY -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Application Details</div>

            <table class="w-full text-xs border border-gray-400 border-collapse">
            <tr>
                <td class="label p-1">Application Date</td>
                <td class="border p-1" id="modalDate"></td>
            </tr>
            <tr>
                <td class="label p-1">Availability</td>
                <td class="border p-1" id="modalAvailability"></td>
            </tr>
            <tr>
                <td class="label p-1">Skills</td>
                <td class="border p-1" id="modalSkills"></td>
            </tr>
            <tr>
                <td class="label p-1">Interests</td>
                <td class="border p-1" id="modalInterests"></td>
            </tr>
            <tr>
                <td class="label p-1">Experience Details</td>
                <td class="border p-1" id="modalExperience"></td>
            </tr>
        </table>
        </div>

        <!-- QUESTIONS -->
        <div class="mt-4">
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tr class="bg-[#0c2d48] text-white">
                    <th class="p-1">Questions</th>
                    <th class="p-1">Answer</th>
                </tr>
                    <tr>
                        <td class="p-1 border">Do you have any volunteering experience</td>
                        <td class="p-1 border" id="modalExperienceAnswer"></td>
                    </tr>
            </table>
        </div>

        <!-- BOTTOM ACTION -->
<div class="mt-6 flex justify-center">
    <button
        onclick="closeModal()"
        class="px-8 py-2 rounded-full bg-[#0e243a] text-white font-bold text-sm hover:bg-[#091826] transition"
    >
        Close
    </button>
</div>
    </div>

</div>
</body>
</html>