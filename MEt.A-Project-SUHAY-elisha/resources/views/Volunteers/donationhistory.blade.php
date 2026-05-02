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

            <button
            class="px-5 py-2 rounded-full bg-[#d4a017] text-white font-bold text-sm view-donation-btn"
            data-donation-type="{{ $donation->type }}"
            data-channel="{{ $donation->payment_type ?? 'N/A' }}"
            data-reference="{{ $donation->reference_number ?? 'N/A' }}"
            data-date="{{ \Carbon\Carbon::parse($donation->date)->format('F d, Y') }}"
            data-items='@json($donation->donation_items)'
            >
            View Donation
            </button>
    </div>


</div>
@empty
<div class="text-center text-gray-500 font-medium">
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

@include('components.logout-modal')

<script>
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
</script>

<!-- Donation Modal -->
<div id="donationModalBackdrop" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-[12px] border shadow-2xl w-[520px] max-w-[92vw] p-[22px]">

    <div class="flex flex-col items-center gap-2 pb-2">
      <div class="w-[62px] h-[42px] flex items-center justify-center">
        <img src="{{ asset('images/suhayLogo.png') }}" />
      </div>
      <div class="font-extrabold text-[#173a63] text-[16px]">Donation Details</div>
    </div>

      <div class="mt-4 space-y-4">

  <!-- Donation Type -->
  <div>
    <div class="text-[13px] font-bold text-[#1f3f66]">Donation Type</div>
    <div id="modalDonationType" class="field">-</div>
  </div>

  <!-- PAYMENT METHOD (MONETARY ONLY) -->
  <div id="paymentMethodSection">
    <div class="text-[13px] font-bold text-[#1f3f66]">Payment Method</div>
    <div id="modalDonationChannel" class="field">-</div>
  </div>

  <!-- NON-MONETARY ITEMS -->
  <div id="nonMonetarySection" class="hidden">
    <div class="text-[13px] font-bold text-[#1f3f66] mb-2">Donated Items</div>
    <div id="itemsList" class="space-y-2"></div>
  </div>

  <!-- Reference -->
  <div>
    <div class="text-[13px] font-bold text-[#1f3f66]">Reference Number</div>
    <div id="modalReference" class="field">-</div>
  </div>

      <!-- CLOSE BUTTON -->
      <div class="flex justify-center pt-3">
        <button
          id="closeDonationModalBtn"
          type="button"
          class="w-[190px] h-[38px] rounded-full bg-[#c79611] hover:bg-[#b7830e] text-white font-extrabold text-[14px]"
        >
          Close
        </button>
      </div>

    </div>
  </div>
</div>

<style>
.field {
  width: 100%;
  height: 38px;
  border: 1px solid #cfd4dd;
  border-radius: 6px;
  padding: 0 10px;
  display: flex;
  align-items: center;
  background: #f3f4f6;
  font-size: 14px;
}
</style>

<div id="nonMonetarySection" class="hidden">
  <div class="text-[13px] font-bold text-[#1f3f66] mb-2">Donated Items</div>

  <div id="itemsList" class="space-y-2"></div>
</div>

<script>
const donationModalBackdrop = document.getElementById('donationModalBackdrop');
const closeDonationModalBtn = document.getElementById('closeDonationModalBtn');

const modalDonationType = document.getElementById('modalDonationType');
const modalDonationChannel = document.getElementById('modalDonationChannel');
const modalReference = document.getElementById('modalReference');

const nonMonetarySection = document.getElementById('nonMonetarySection');
const itemsList = document.getElementById('itemsList');
// const modalDate = document.getElementById('modalDate');

function openDonationModalFromButton(btn) {

    const donationType = btn.dataset.donationType || 'N/A';
    const channel = btn.dataset.channel || 'N/A';
    const reference = btn.dataset.reference || 'N/A';
    const date = btn.dataset.date || 'N/A';

    const items = JSON.parse(btn.dataset.items || '[]');

    // TEXT FIELDS
    modalDonationType.textContent = donationType;
    modalDonationChannel.textContent = channel;
    modalReference.textContent = reference;
    // modalDate.textContent = date;

    // HANDLE NON-MONETARY ITEMS
    itemsList.innerHTML = '';

    if (donationType.toLowerCase().includes('non')) {
        nonMonetarySection.classList.remove('hidden');

        if (items.length > 0) {
            items.forEach(item => {
                const row = document.createElement('div');
                row.className = "flex justify-between border rounded px-3 py-2 bg-gray-50";

                row.innerHTML = `
                    <span>${item.name}</span>
                    <span class="font-bold">x${item.quantity}</span>
                `;

                itemsList.appendChild(row);
            });
        } else {
            itemsList.innerHTML = '<div class="text-gray-500">No items listed</div>';
        }

    } else {
        nonMonetarySection.classList.add('hidden');
    }

    donationModalBackdrop.classList.remove('hidden');
    donationModalBackdrop.classList.add('flex');
}

function closeDonationModal() {
    donationModalBackdrop.classList.add('hidden');
    donationModalBackdrop.classList.remove('flex');
}

// OPEN MODAL
document.querySelectorAll('.view-donation-btn').forEach(btn => {
    btn.addEventListener('click', () => openDonationModalFromButton(btn));
});

// CLOSE BUTTON
closeDonationModalBtn.addEventListener('click', closeDonationModal);

// CLICK OUTSIDE
donationModalBackdrop.addEventListener('click', (e) => {
    if (e.target === donationModalBackdrop) closeDonationModal();
});

// ESC KEY
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDonationModal();
});

function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

</body>
</html>