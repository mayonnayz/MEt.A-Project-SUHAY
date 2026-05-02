<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NGOs | Volunteer</title>

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
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    @include('components.nav')

    <div class="flex-1 p-6 md:p-8">

        @include('components.header', ['title' => 'NGOs'])

        <div class="bg-white rounded-[22px] border-[10px] border-[#0e243a] p-6 sm:p-8">

            {{-- SEARCH --}}
            <div class="mb-6 flex gap-3">

                <div class="flex items-center bg-white border-2 border-[#0e243a] rounded-2xl px-4 py-3 flex-1">
                    <input type="text" id="searchInput"
                        placeholder="Search NGOs..."
                        class="w-full outline-none text-[15px] font-medium placeholder:text-gray-500"/>
                </div>

            </div>

            {{-- LIST --}}
            <div id="ngoList" class="space-y-5">

                @foreach($ngos as $ngo)
                <div class="ngo-card rounded-2xl border-2 border-[#0e243a] bg-white p-5 
                            transition hover:bg-gray-100 hover:shadow-md"
                     data-name="{{ strtolower($ngo['name'] ?? '') }}"
                     data-address="{{ strtolower($ngo['address'] ?? '') }}"
                     data-contact="{{ strtolower($ngo['contact_number'] ?? '') }}">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div class="flex items-center gap-4">

                            <img src="{{ $ngo['logo'] ?? asset('images/suhayLogo.png') }}"
                                class="w-16 h-16 object-contain">

                            <div>
                                <div class="text-lg font-bold text-[#0e243a]">
                                    {{ $ngo['name'] ?? 'NGO Name' }}
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-700 mt-1">
                                    <img src="{{ asset('images/VolunteerIcons/VPhone.png') }}" class="w-4 h-4">
                                    {{ $ngo['contact_number'] ?? 'N/A' }}
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <img src="{{ asset('images/VolunteerIcons/VLocation.png') }}" class="w-4 h-4">
                                    {{ $ngo['address'] ?? 'N/A' }}
                                </div>
                            </div>

                        </div>

                        <button onclick="openModal({{ $ngo['id'] }})"
                            class="px-6 py-2 rounded-full bg-[#d39a11] text-white font-bold text-sm hover:bg-[#c2870d]">
                            View NGO Details
                        </button>

                    </div>

                </div>
                @endforeach

                {{-- NO RESULT --}}
                <div id="noResults" class="hidden text-center text-gray-500 font-medium">
                    No NGOs found.
                </div>

            </div>

        </div>
    </div>
</div>


{{-- MODAL --}}
<div id="ngoModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div id="modalContent"
        class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl">
    </div>
</div>

<div id="donationModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <div id="donationContent"
        class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative">

        <!-- CLOSE -->
        <button onclick="closeDonationModal()" 
            class="absolute top-3 right-5 text-xl font-bold">×</button>

        <!-- LOGO -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/suhayLogo.png') }}" class="h-14 mx-auto mb-2">
            <h2 class="font-bold text-lg text-[#0e243a]">Donation Form</h2>
        </div>

        <!-- DONATION TYPE ROW -->
        <div class="mb-5 flex items-center justify-between gap-4">

            <label class="text-sm font-medium text-[#0e243a] whitespace-nowrap">
                Donation Type
            </label>

            <select id="donationType" onchange="toggleDonationType()"
                class="border rounded-lg px-3 py-2 w-40 text-sm">
                <option value="non">Non-Monetary</option>
                <option value="monetary">Monetary</option>
            </select>

        </div>

        <!-- ITEM LABEL -->
        <div id="itemLabel" class="mb-2">
            <label class="text-sm font-medium text-[#0e243a]">Item</label>
        </div>

        <!-- ITEM INPUTS -->
        <div id="itemsContainer" class="space-y-3 mb-4">
            <!-- dynamic items here -->
        </div>

        <button id="addItemBtn" onclick="addItem()" 
            class="bg-[#4CAF50] text-white px-4 py-2 rounded-full text-sm mb-4">
            + Add Item
        </button>

        <!-- MONETARY -->
        <div id="monetaryForm" class="hidden mb-4">

            <div class="mb-3">
                <label class="text-sm font-medium text-[#0e243a]">Donation Channel</label>
                <select id="donationChannel"
                    class="w-full border rounded-lg px-3 py-2 mt-1">
                    <option value="gcash">GCash</option>
                    <option value="bank">Bank</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-[#0e243a]">Reference Number</label>
                <input type="text"
                    class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

        </div>

        <!-- BUTTONS -->
        <div class="flex justify-between gap-3 mt-6">

            <!-- BACK -->
            <button onclick="goBackToNgoModal()"
                class="w-1/2 py-2 rounded-full border border-[#0e243a] text-[#0e243a] font-bold bg-white hover:bg-gray-100">
                Back
            </button>

            <!-- SUBMIT -->
            <button class="w-1/2 py-2 rounded-full bg-[#d4a017] text-[#0e243a] font-bold hover:bg-[#c29314]">
                Submit
            </button>

        </div>

    </div>
</div>

@include('components.logout-modal')

<script>

// DATA
const ngoData = @json($ngos);

// LIVE SEARCH
const searchInput = document.getElementById('searchInput');
const ngoCards = document.querySelectorAll('.ngo-card');
const noResults = document.getElementById('noResults');

searchInput.addEventListener('keyup', function () {
    const search = this.value.toLowerCase();
    let visible = 0;

    ngoCards.forEach(card => {
        const name = card.dataset.name;
        const address = card.dataset.address;
        const contact = card.dataset.contact;

        if (name.includes(search) || address.includes(search) || contact.includes(search)) {
            card.style.display = "block";
            visible++;
        } else {
            card.style.display = "none";
        }
    });

    noResults.style.display = visible === 0 ? "block" : "none";
});


// OPEN MODAL (UPDATED WITH ICONS)
function openModal(id) {
    const ngo = ngoData.find(n => n.id == id) || {};

    document.getElementById('modalContent').innerHTML = `
<div class="p-6 md:p-8 relative">

    <button onclick="closeModal()" class="absolute top-4 right-6 text-xl font-bold">×</button>

    <div class="text-center mb-6">
        <img src="{{ asset('images/suhayLogo.png') }}" class="h-16 mx-auto mb-2">
        <h2 class="text-2xl font-bold text-[#0e243a]">${ngo.name || ''}</h2>
        <p class="text-sm text-gray-600 mt-1">${ngo.description || ''}</p>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6 text-sm bg-gray-50 p-4 rounded-xl">

        <!-- CONTACT -->
        <div class="flex flex-col items-center text-center gap-2">
            <div class="flex items-center gap-2 font-semibold">
                <img src="{{ asset('images/VolunteerIcons/VPhone.png') }}" class="w-4 h-4">
                Contact
            </div>
            <p>${ngo.contact_number || 'N/A'}</p>
        </div>

        <!-- LOCATION -->
        <div class="flex flex-col items-center text-center gap-2">
            <div class="flex items-center gap-2 font-semibold">
                <img src="{{ asset('images/VolunteerIcons/VLocation.png') }}" class="w-4 h-4">
                Location
            </div>
            <p>${ngo.address || 'N/A'}</p>
        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- GCASH -->
        <div class="border rounded-xl p-5">
            <h3 class="font-bold text-[#0e243a] mb-6 text-center">GCASH</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-600 mb-1">Account Name:</span>
                        <span class="font-medium block">${ngo.name || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="block text-gray-600 mb-1">Account Number:</span>
                        <span class="font-medium block">${ngo.gcash || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border text-center">
                    <img src="{{ asset('images/suhayLogo.png') }}" class="w-full max-h-40 object-contain mx-auto block rounded">
                </div>
            </div>
        </div>

        <!-- BANK -->
        <div class="border rounded-xl p-5">
            <h3 class="font-bold text-[#0e243a] mb-6 text-center">BANK</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-600 mb-1">Account Name:</span>
                        <span class="font-medium block">${ngo.name || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="block text-gray-600 mb-1">Bank:</span>
                        <span class="font-medium block">${ngo.bank_account || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="block text-gray-600 mb-1">Account Number:</span>
                        <span class="font-medium block">${ngo.bank_number || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border text-center">
                    <img src="{{ asset('images/suhayLogo.png') }}" class="w-full max-h-40 object-contain mx-auto block rounded">
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-6">
        <button onclick="openDonationModal()" 
            class="bg-[#4CAF50] px-8 py-2 rounded-full font-bold text-white hover:bg-[#43a047] transition">
            Donate Now
        </button>
    </div>

</div>
`;

    const modal = document.getElementById('ngoModal');
    const content = document.getElementById('modalContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.classList.remove('modal-exit');
    content.classList.add('modal-enter');

    document.body.style.overflow = 'hidden';
}

// ADD ITEM
function addItem() {
    const container = document.getElementById('itemsContainer');

    const itemDiv = document.createElement('div');
    itemDiv.className = "flex items-center gap-2";

    itemDiv.innerHTML = `
        <input type="text" 
            placeholder="Enter item"
            class="flex-1 border rounded-lg px-3 py-2">

        <button onclick="removeItem(this)" 
            class="text-[#0e243a] text-xl font-bold">
            −
        </button>
    `;

    container.appendChild(itemDiv);
}

function goBackToNgoModal() {
    const donationModal = document.getElementById('donationModal');
    const ngoModal = document.getElementById('ngoModal');

    // CLOSE donation modal
    donationModal.classList.add('hidden');
    donationModal.classList.remove('flex');

    // REOPEN NGO modal
    ngoModal.classList.remove('hidden');
    ngoModal.classList.add('flex');

    document.body.style.overflow = 'hidden';
}

// REMOVE ITEM
function removeItem(btn) {
    btn.parentElement.remove();
}

// CLOSE
function closeModal() {
    const ngoModal = document.getElementById('ngoModal');
    const content = document.getElementById('modalContent');

    // trigger exit animation
    content.classList.remove('modal-enter');
    content.classList.add('modal-exit');

    setTimeout(() => {
        ngoModal.classList.add('hidden');
        ngoModal.classList.remove('flex');

        document.body.style.overflow = 'auto';
    }, 250); // match animation duration
}

// CLICK OUTSIDE
document.getElementById('ngoModal').addEventListener('click', function(e) {
    if (e.target.id === 'ngoModal') {
        closeModal();
    }
});

document.getElementById('donationModal').addEventListener('click', function(e) {
    if (e.target.id === 'donationModal') {
        closeDonationModal();
    }
});

// OPEN DONATION MODAL
function openDonationModal() {

    const ngoModal = document.getElementById('ngoModal');
    const donationModal = document.getElementById('donationModal');
    const donationContent = document.getElementById('donationContent');

    // HIDE NGO MODAL (NOT destroy, just hide)
    ngoModal.classList.add('hidden');
    ngoModal.classList.remove('flex');

    // OPEN DONATION MODAL
    donationModal.classList.remove('hidden');
    donationModal.classList.add('flex');

    donationContent.classList.remove('modal-exit');
    donationContent.classList.add('modal-enter');

    document.body.style.overflow = 'hidden';

    // RESET FORM
    document.getElementById('donationType').value = "non";
    document.getElementById('donationChannel').value = "gcash";

    const items = document.getElementById('itemsContainer');
    items.innerHTML = "";

    addItem();
    toggleDonationType();
}

// CLOSE DONATION MODAL
function closeDonationModal() {
    const modal = document.getElementById('donationModal');
    const content = document.getElementById('donationContent');

    // trigger exit animation
    content.classList.remove('modal-enter');
    content.classList.add('modal-exit');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.style.overflow = 'auto';
    }, 250);
}

// TOGGLE FORMS
function toggleDonationType() {
    const type = document.getElementById('donationType').value;

    const mon = document.getElementById('monetaryForm');
    const items = document.getElementById('itemsContainer');
    const label = document.getElementById('itemLabel');
    const addBtn = document.getElementById('addItemBtn');

    if (type === "monetary") {
        // SHOW monetary
        mon.classList.remove('hidden');

        // HIDE item-related UI
        items.classList.add('hidden');
        label.classList.add('hidden');
        addBtn.classList.add('hidden');

        // CLEAR items (important)
        items.innerHTML = "";

    } else {
        // SHOW item-related UI
        items.classList.remove('hidden');
        label.classList.remove('hidden');
        addBtn.classList.remove('hidden');

        // HIDE monetary
        mon.classList.add('hidden');

        // ensure at least 1 input
        if (items.children.length === 0) {
            addItem();
        }
    }
}

// LOGOUT MODAL
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