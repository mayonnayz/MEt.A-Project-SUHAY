<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Profile</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">
    @include('components.nav')

    <div class="flex-1 p-8">
        @include('components.header', ['title' => 'NGO Profile'])

        <!-- 🔥 TABS -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

            <!-- DETAILS (ACTIVE) -->
            <a href="/sm-ngos"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <!-- MEMBERS -->
            <a href="/ngo-members"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold hover:opacity-90 transition">
                Members
            </a>

        </div>

        <div class="mt-10">

            <div class="text-center mb-12">

                <div class="flex justify-center items-center gap-16 mb-6 flex-wrap">

                    <img src="{{ asset('images/suhayLogo.png') }}" class="w-40 h-40 object-contain">

                    <div class="w-px h-28 bg-gradient-to-b from-[#0e243a] to-[#f2c94c] hidden md:block"></div>

                    <img src="{{ $ngo->logo_url ?? asset('images/ngo-logo-placeholder.png') }}"
                        class="w-40 h-40 object-contain rounded-full">
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-[#0e243a] mb-2">
                    {{ $ngo->name ?? '------' }}
                </h1>

                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    {{ $ngo->description ?? '------' }}
                </p>

            </div>

            <div class="max-w-5xl mx-auto space-y-12">

                <div>
    <h3 class="text-xl font-bold text-[#0e243a] mb-4 border-b-2 border-[#0e243a]/20 pb-3">
        Contact Information
    </h3>

    <div class="grid md:grid-cols-2 gap-8 text-lg">
        <div>
            <p class="text-gray-500">Email Address</p>
            {{-- No email column exists in ngo_profile yet — add one and pass it in
                 (e.g. {{ $ngo->email ?? '------' }}) once the schema supports it --}}
            <p class="font-bold text-[#0e243a] text-xl">{{ $ngo->email ?? '------' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Contact Number</p>
            <p class="font-bold text-[#0e243a] text-xl">
                {{ $ngo->contact_number ?? '------' }}
            </p>
        </div>
    </div>
</div>

<div>
    <div class="flex items-center justify-between border-b-2 border-[#0e243a]/20 pb-3 mb-6">
        <h3 class="text-xl font-bold text-[#0e243a]">
            Accounts Details
        </h3>
    </div>

    <div class="bg-gray-100 rounded-2xl p-6">

        @if(!empty($ngo->bank_accounts))

            <div class="flex items-center justify-between gap-6 flex-wrap">

                <div>
                    <p class="text-gray-500 text-sm mb-1">
                        Available Payment Accounts
                    </p>

                    <p class="font-bold text-[#0e243a] text-xl">
                        {{ count($ngo->bank_accounts) }}
                        {{ count($ngo->bank_accounts) === 1 ? 'Account' : 'Accounts' }}
                    </p>

                    <p class="text-gray-500 text-sm mt-1">
                        Bank and Online payment options
                    </p>
                </div>

                <button
                    onclick="openPaymentModal()"
                    class="bg-[#0e243a] text-white px-7 py-3 rounded-xl font-bold hover:bg-[#173652] transition"
                >
                    View Payment Accounts
                </button>

                </div>
                <!-- <button
                    type="button"
                    onclick="openManageAccountsModal()"
                    class="bg-[#f2c94c] text-[#0e243a] px-6 py-3 rounded-xl font-bold hover:bg-[#e6bd43] transition"
                >
                    Manage Accounts
                </button> -->

        @else

            <div class="text-center py-6">
                <p class="text-gray-500">
                    No payment accounts available.
                </p>
            </div>

        @endif

    </div>
</div>

                <div>
                    <h3 class="text-xl font-bold text-[#0e243a] mb-4 border-b-2 border-[#0e243a]/20 pb-3">
                        Complete Address
                    </h3>

                    <p class="font-bold text-[#0e243a] text-xl leading-relaxed">
                        {{ $ngo->address ?? '------' }}
                    </p>
                </div>

            </div>

            <div class="flex justify-end mt-20">
                <button onclick="openEditNgoModal()"
                    class="bg-[#f2c94c] text-[#0e243a] px-10 py-4 rounded-xl text-lg font-bold shadow hover:bg-[#e6bd43] transition">
                    Edit Organization Details
                </button>
            </div>

        </div>
    </div>
</div>
<!-- PAYMENT ACCOUNTS MODAL -->
<div id="paymentModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div id="paymentModalBox"
         class="bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl transform scale-95 transition duration-200">

        <!-- HEADER -->
        <div class="sticky top-0 bg-white border-b px-8 py-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-extrabold text-[#0e243a]">
                    Payment Accounts
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Available bank and GCash accounts
                </p>
            </div>

            <button
                onclick="closePaymentModal()"
                class="text-gray-400 hover:text-[#0e243a] text-3xl font-bold"
            >
                &times;
            </button>

        </div>


        <!-- ACCOUNTS -->
        <div class="p-8">

            @if(!empty($ngo->bank_accounts))

                <div class="space-y-6">

                    @foreach($ngo->bank_accounts as $account)

                        <div class="border rounded-2xl p-6">

                            <div class="flex flex-col md:flex-row gap-8">

                                <!-- ACCOUNT INFORMATION -->
                                <div class="flex-1">

                                    <div class="flex items-center gap-3 mb-5">

                                        <span class="px-4 py-2 rounded-full text-sm font-bold
                                            {{ strtoupper($account['type']) === 'GCASH'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-blue-100 text-blue-700' }}">

                                            {{ strtoupper($account['type']) }}

                                        </span>

                                    </div>


                                    <div class="mb-4">

                                        <p class="text-gray-500 text-sm">
                                            Account Name
                                        </p>

                                        <p class="font-bold text-[#0e243a] text-xl">
                                            {{ $account['account_name'] }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-gray-500 text-sm">
                                            Account Number
                                        </p>

                                        <p class="font-bold text-[#0e243a] text-xl tracking-wide">
                                            {{ $account['account_number'] }}
                                        </p>

                                    </div>

                                </div>


                                <!-- QR CODE -->
                                @if(!empty($account['qr_code']))

                                    <div class="md:w-48 flex flex-col items-center justify-center">

                                        <p class="text-gray-500 text-sm font-semibold mb-3">
                                            Scan to Pay
                                        </p>

                                        <div class="bg-white border rounded-2xl p-3 shadow-sm">

                                            <img
                                                src="{{ $account['qr_code'] }}"
                                                alt="{{ $account['type'] }} QR Code"
                                                class="w-40 h-40 object-contain"
                                            >

                                        </div>

                                    </div>

                                @else

                                    <div class="md:w-48 flex items-center justify-center">

                                        <div class="text-center text-gray-400">

                                            <div class="text-4xl mb-2">
                                                —
                                            </div>

                                            <p class="text-sm">
                                                No QR code available
                                            </p>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-12">

                    <p class="text-gray-500">
                        No payment accounts available.
                    </p>

                </div>

            @endif

        </div>


       <!-- FOOTER -->
        <div class="border-t px-8 py-5 flex justify-between items-center gap-3">

            <button
                type="button"
                onclick="openManageAccountsModal()"
                class="bg-[#0e243a] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#173652] transition"
            >
                Manage Accounts
            </button>

            <button
                type="button"
                onclick="closePaymentModal()"
                class="bg-[#f2c94c] text-[#0e243a] px-7 py-3 rounded-xl font-bold hover:bg-[#e6bd43] transition"
            >
                Close
            </button>

        </div>

    </div>

</div>

@include('components.logout-modal')
@include('components.edit-ngo-modal')
@include('components.manage-accounts-modal')

<script>
function openEditNgoModal() {
    const modal = document.getElementById('editNgoModal');
    const box = document.getElementById('ngoModalBox');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95');
    }, 10);
}

function closeEditNgoModal() {
    const modal = document.getElementById('editNgoModal');
    const box = document.getElementById('ngoModalBox');

    box.classList.add('scale-95');
    modal.classList.add('opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 150);
}

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
function openPaymentModal() {

    const modal = document.getElementById('paymentModal');
    const box = document.getElementById('paymentModalBox');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        box.classList.remove('scale-95');
    }, 10);
}


function closePaymentModal() {

    const modal = document.getElementById('paymentModal');
    const box = document.getElementById('paymentModalBox');

    box.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 150);
}


// Close when clicking outside the modal
document.getElementById('paymentModal').addEventListener('click', function(e) {

    if (e.target === this) {
        closePaymentModal();
    }

});

function openManageAccountsModal() {

    const modal = document.getElementById('manageAccountsModal');
    const box = document.getElementById('manageAccountsModalBox');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        box.classList.remove('scale-95');
    }, 10);
}


function closeManageAccountsModal() {

    const modal = document.getElementById('manageAccountsModal');
    const box = document.getElementById('manageAccountsModalBox');

    box.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 150);
}


function openAddAccountForm() {

    document
        .getElementById('addAccountForm')
        .classList.remove('hidden');

}


function closeAddAccountForm() {

    document
        .getElementById('addAccountForm')
        .classList.add('hidden');

}


document.getElementById('manageAccountsModal')
    .addEventListener('click', function(e) {

        if (e.target === this) {
            closeManageAccountsModal();
        }

    });
</script>


</body>
</html>