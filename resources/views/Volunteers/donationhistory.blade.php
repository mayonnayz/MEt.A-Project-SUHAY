<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donations | Volunteer</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .modal-enter {
            animation: zoomFadeIn 0.3s ease forwards;
        }

        .modal-exit {
            animation: zoomFadeOut 0.25s ease forwards;
        }

        @keyframes zoomFadeIn {
            0% { opacity: 0; transform: scale(0.7); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes zoomFadeOut {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.7); }
        }

        .field {
            @apply px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm;
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    {{-- SIDEBAR --}}
    @include('components.nav')

    <div class="flex-1 p-6 md:p-8">

        {{-- HEADER --}}
        @include('components.header', ['title' => 'Donations'])

        {{-- MAIN CONTAINER --}}
        <div class="bg-[#f5f5f5] rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">

            {{-- SEARCH + FILTER --}}
            <div class="mb-6 flex flex-col md:flex-row gap-3">

                <!-- SEARCH -->
                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-full px-4 py-2 flex-1">
                    <input type="text" id="searchInput"
                        placeholder="Search Donation History..."
                        class="w-full outline-none text-[14px] font-medium placeholder:text-gray-500">

                    <div class="ml-3 w-10 h-10 rounded-xl bg-[#0e243a] flex items-center justify-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>

                <!-- FILTER -->
                <select id="filterType"
                    class="border-2 border-[#0e243a] rounded-full px-5 py-2 text-sm font-medium">
                    <option value="all">All</option>
                    <option value="monetary">Monetary</option>
                    <option value="non-monetary">Non-Monetary</option>
                </select>

            </div>

            {{-- DONATION LIST --}}
            <div id="donationList" class="space-y-5">

                @forelse ($donations as $donation)
                <div class="donation-card rounded-2xl border-2 border-[#0e243a] bg-white p-5 
                            flex flex-col md:flex-row md:items-center md:justify-between gap-4"
                    data-name="{{ strtolower($donation->name ?? 'unknown') }}"
                    data-type="{{ strtolower($donation->type ?? 'monetary') }}">

                    <div>
                        <div class="text-lg font-bold text-[#0e243a]">
                            {{ $donation->name ?? 'Unknown NGO' }}
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                            <img src="{{ asset('images/VolunteerIcons/DVDateIcon.png') }}" class="w-8 h-8">
                            {{ \Carbon\Carbon::parse($donation->date)->format('F d, Y') }}
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <img src="{{ asset('images/VolunteerIcons/DVTypeIcon.png') }}" class="w-8 h-8">
                            {{ ucfirst($donation->type ?? 'Monetary') }}
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <button class="px-5 py-2 rounded-full bg-[#d4a017] text-white font-bold text-sm view-donation-btn hover:bg-[#c2870d] transition"
                                data-donation-type="{{ ucfirst($donation->type ?? 'Monetary') }}"
                                data-channel="{{ ucfirst($donation->payment_type ?? 'N/A') }}"
                                data-reference="{{ $donation->reference_number ?? 'N/A' }}"
                                data-date="{{ \Carbon\Carbon::parse($donation->date)->format('F d, Y') }}"
                                data-items="{{ is_string($donation->donation_items) ? json_encode(json_decode($donation->donation_items)) : json_encode($donation->donation_items ?? []) }}"
                                data-ngo-name="{{ ($donation->ngo->name ?? 'Unknown NGO') }}">
                            View Donation
                        </button>
                    </div>

                </div>
                @empty
                <div class="text-center text-gray-500 font-medium py-12">
                    No donations found.
                </div>
                @endforelse

                <div id="noResults" class="hidden text-center text-gray-500 font-medium">
                    No donations found.
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Donation Modal - PERFECT ANIMATIONS -->
<div id="donationModalBackdrop" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
  <div id="donationModalContent" class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
    
    <!-- HEADER -->
    <div class="p-6 text-center relative border-b">
      <button onclick="closeDonationModal()" class="absolute top-4 right-6 text-xl font-bold hover:text-gray-700">×</button>
      <img src="{{ asset('images/suhayLogo.png') }}" class="h-12 mx-auto mb-3">
      <h2 class="font-bold text-xl text-[#0e243a]">Donation Details</h2>
    </div>

    <!-- CONTENT -->
    <div id="donationModalBody" class="p-6 space-y-4">
      <!-- Dynamic content will be populated here -->
    </div>

  </div>
</div>

@include('components.logout-modal')

<script>
// SEARCH & FILTER
const searchInput = document.getElementById('searchInput');
const filterType = document.getElementById('filterType');
const cards = document.querySelectorAll('.donation-card');
const noResults = document.getElementById('noResults');

searchInput.addEventListener('keyup', filterDonations);
filterType.addEventListener('change', filterDonations);

function filterDonations() {
    const search = searchInput.value.toLowerCase().trim();
    const type = filterType.value;

    let visible = 0;

    cards.forEach(card => {
        const name = card.dataset.name || "";
        const cardType = card.dataset.type || "";

        const matchSearch = name.includes(search);
        const matchType = (type === "all") || (cardType === type);

        if (matchSearch && matchType) {
            card.style.display = "flex";
            visible++;
        } else {
            card.style.display = "none";
        }
    });

    noResults.style.display = visible === 0 ? "block" : "none";
}

// DONATION MODAL FUNCTIONS
function openDonationModal(btn) {
    const donationType = btn.dataset.donationType || 'N/A';
    const channel = btn.dataset.channel || 'N/A';
    const reference = btn.dataset.reference || 'N/A';
    const date = btn.dataset.date || 'N/A';
    const items = JSON.parse(btn.dataset.items || '[]');
    const ngoName = btn.dataset.ngoName || 'Unknown NGO';

    let content = `
        <div class="space-y-4">

            <!-- TYPE + DATE -->
            <div class="grid grid-cols-2 gap-4 text-sm bg-gray-50 p-4 rounded-xl">
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 font-medium mb-1">Donation Type</span>
                    <span class="font-bold text-[#0e243a] text-base">${donationType}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 font-medium mb-1">Date</span>
                    <span class="font-bold text-[#0e243a] text-base">${date}</span>
                </div>
            </div>
    `;

    // =========================
    // NON-MONETARY
    // =========================
    if (donationType.toLowerCase().includes('non')) {

        content += `
            <div class="border rounded-xl p-4 bg-gray-50">
                <div class="font-bold text-[#0e243a] mb-4 flex items-center gap-2 text-sm">
                    <img src="{{ asset('images/VolunteerIcons/DVTypeIcon.png') }}" class="w-5 h-5">
                    Donated Items
                </div>

                <div class="space-y-2 max-h-40 overflow-y-auto">
        `;

        if (items.length > 0) {
            items.forEach(item => {
                content += `
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border shadow-sm">
                        <span class="text-sm font-medium text-gray-800">${item.name || 'Item'}</span>
                        <span class="font-bold text-[#0e243a] text-sm px-3 py-1 bg-[#d4a017]/10 rounded-full">
                            x${item.quantity || 1}
                        </span>
                    </div>
                `;
            });
        } else {
            content += `
                <div class="text-gray-500 text-sm text-center py-8">
                    No items listed
                </div>
            `;
        }

        content += `</div></div>`;
    }

    // =========================
    // MONETARY
    // =========================
    else {

        content += `
            <div class="space-y-4">

                <!-- CHANNEL -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <span class="text-xs text-gray-500 font-medium block mb-1">
                        Donation Channel
                    </span>
                    <span class="font-bold text-[#0e243a] text-base">
                        ${channel}
                    </span>
                </div>

                <!-- REFERENCE -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <span class="text-xs text-gray-500 font-medium block mb-1">
                        Reference Number
                    </span>
                    <span class="font-bold text-[#0e243a] text-base">
                        ${reference}
                    </span>
                </div>

            </div>
        `;
    }

    content += `</div>`;

    // INSERT CONTENT
    document.getElementById('donationModalBody').innerHTML = content;

    // SHOW MODAL
    const backdrop = document.getElementById('donationModalBackdrop');
    const contentEl = document.getElementById('donationModalContent');

    backdrop.classList.remove('hidden');
    backdrop.classList.add('flex');

    contentEl.classList.remove('modal-exit');
    contentEl.classList.add('modal-enter');

    document.body.style.overflow = 'hidden';
}

function closeDonationModal() {
    const backdrop = document.getElementById('donationModalBackdrop');
    const contentEl = document.getElementById('donationModalContent');

    contentEl.classList.remove('modal-enter');
    contentEl.classList.add('modal-exit');

    setTimeout(() => {
        backdrop.classList.add('hidden');
        backdrop.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 250);
}

// EVENT LISTENERS - Connect to buttons
document.querySelectorAll('.view-donation-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        openDonationModal(e.currentTarget);
    });
});

// CLICK OUTSIDE TO CLOSE
document.getElementById('donationModalBackdrop').addEventListener('click', function(e) {
    if (e.target.id === 'donationModalBackdrop') {
        closeDonationModal();
    }
});

// ESC KEY TO CLOSE
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDonationModal();
    }
});

// LOGOUT FUNCTIONS
function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>

</body>
</html>