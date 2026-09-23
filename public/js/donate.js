// ========================================
// AOS
// ========================================

AOS.init({
    duration: 800,
    once: false,
    offset: 100,
    easing: 'ease-out-cubic'
});


// ========================================
// DATA FROM BLADE
// ========================================

const ngoData = window.ngoData || [];
const assets = window.suhayAssets || {};


// ========================================
// OPEN NGO MODAL
// ========================================
function closeResultModal() {

    const modal =
        document.getElementById('resultModal');

    if (modal) {

        modal.remove();

    }

}
function openModal(id) {

    const ngo = ngoData.find(n => n.id == id) || {};

    const accounts = ngo.bank_accounts || [];


    // ========================================
    // BUILD ACCOUNT DISPLAY
    // ========================================

    let accountDisplay = '';

    if (accounts.length > 0) {

        accounts.forEach(account => {

            accountDisplay += `
                <div class="border rounded-xl p-4">

                    <div class="font-bold text-[#0e243a]">
                        ${account.type}
                    </div>

                    <div class="text-sm mt-2">

                        <p>
                            <span class="text-gray-500">
                                Account Name:
                            </span>

                            ${account.account_name}
                        </p>

                        <p>
                            <span class="text-gray-500">
                                Account Number:
                            </span>

                            ${account.account_number}
                        </p>

                    </div>

                </div>
            `;

        });

    } else {

        accountDisplay = `
            <p class="text-gray-500 text-center">
                No online payment accounts available.
            </p>
        `;
    }


    // ========================================
    // NGO LOGO
    // ========================================

    const logoUrl = ngo.logo_url || assets.logo;


    // ========================================
    // MODAL CONTENT
    // ========================================

    document.getElementById('modalContent').innerHTML = `

        <div class="p-6 md:p-8 relative">


            <!-- CLOSE BUTTON -->

            <button
                type="button"
                onclick="closeModal()"
                class="absolute top-4 right-5 text-gray-500 hover:text-gray-800 text-3xl font-bold"
            >
                &times;
            </button>


            <!-- NGO HEADER -->

            <div class="text-center mb-8">

                <img
                    src="${logoUrl}"
                    class="h-20 mx-auto mb-3 object-contain"
                    alt="NGO Logo"
                    onerror="this.src='${assets.logo}'"
                >

                <h2 class="text-2xl font-bold text-[#0e243a]">
                    ${ngo.name || ''}
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    ${ngo.description || ''}
                </p>

            </div>


            <!-- ================================= -->
            <!-- NGO CONTACT -->
            <!-- ================================= -->

            <div class="grid md:grid-cols-2 gap-6 mb-8 text-sm bg-gray-50 p-5 rounded-xl">

                <div class="flex flex-col items-center text-center gap-2">

                    <div class="flex items-center gap-2 font-semibold text-[#0e243a]">

                        <img
                            src="${assets.phoneIcon}"
                            class="w-4 h-4"
                            alt="Phone"
                        >

                        Contact

                    </div>

                    <p>
                        ${ngo.contact_number || 'N/A'}
                    </p>

                </div>


                <div class="flex flex-col items-center text-center gap-2">

                    <div class="flex items-center gap-2 font-semibold text-[#0e243a]">

                        <img
                            src="${assets.locationIcon}"
                            class="w-4 h-4"
                            alt="Location"
                        >

                        Location

                    </div>

                    <p>
                        ${ngo.address || 'N/A'}
                    </p>

                </div>

            </div>


            <!-- ================================= -->
            <!-- ONLINE PAYMENT ACCOUNTS -->
            <!-- ================================= -->

            <div class="mb-8">

                <h3 class="text-xl font-bold text-[#0e243a] mb-4">
                    Online Donation Accounts
                </h3>

                <div class="grid md:grid-cols-2 gap-4">

                    ${accountDisplay}

                </div>

            </div>


            <!-- ================================= -->
            <!-- DONATION FORM -->
            <!-- ================================= -->

            <div class="border-2 border-[#0e243a] rounded-2xl p-6">

                <h3 class="text-xl font-bold text-[#0e243a] mb-6">
                    Make a Donation
                </h3>


                <form
                    method="POST"
                    action="/submit-donation"
                    id="donationForm"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="_token"
                        value="${getCsrfToken()}"
                    >


                    <!-- NGO ID -->

                    <input
                        type="hidden"
                        name="ngo_id"
                        value="${ngo.id}"
                    >


                    <!-- ================================= -->
                    <!-- ANONYMOUS DONATION -->
                    <!-- ================================= -->

                    <div class="bg-gray-50 rounded-xl p-4 mb-6">

                        <label class="flex items-center gap-3 cursor-pointer">

                            <input
                                type="checkbox"
                                name="anonymous"
                                value="1"
                                id="anonymousDonation"
                                class="w-5 h-5"
                                onchange="toggleDonorSource()"
                            >

                            <span class="font-semibold text-[#0e243a]">
                                Make this donation anonymous
                            </span>

                        </label>

                        <p class="text-sm text-gray-500 mt-2 ml-8">
                            Your name will not be recorded as the donation source.
                        </p>

                    </div>


                    <!-- ================================= -->
                    <!-- DONOR SOURCE -->
                    <!-- ================================= -->

                    <div class="mb-5">

                        <label
                            for="source"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Donor / Source
                        </label>

                        <input
                            type="text"
                            name="source"
                            id="source"
                            placeholder="e.g. From Juan Dela Cruz"
                            class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4a017]"
                        >

                    </div>


                    <!-- ================================= -->
                    <!-- DONATION TYPE -->
                    <!-- ================================= -->

                    <div class="mb-5">

                        <label
                            for="donationType"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Donation Type
                        </label>

                        <select
                            name="type"
                            id="donationType"
                            onchange="updateDonationFields()"
                            required
                            class="w-full border rounded-xl px-4 py-3"
                        >

                            <option value="">
                                Select Donation Type
                            </option>

                            <option value="MONETARY">
                                Monetary
                            </option>

                            <option value="ONLINE_MONETARY">
                                Online Monetary
                            </option>

                            <option value="CONSUMABLE">
                                Consumable
                            </option>

                            <option value="REUSABLE">
                                Reusable
                            </option>

                        </select>

                    </div>


                    <!-- ================================= -->
                    <!-- DESCRIPTION -->
                    <!-- ================================= -->

                    <div class="mb-5">

                        <label
                            for="description"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            required
                            placeholder="e.g. Financial donation, canned goods, clothes..."
                            class="w-full border rounded-xl px-4 py-3"
                        ></textarea>

                    </div>


                    <!-- ================================= -->
                    <!-- AMOUNT -->
                    <!-- ================================= -->

                    <div class="grid md:grid-cols-2 gap-5 mb-5">

                        <div>

                            <label
                                for="amount"
                                class="block font-semibold text-[#0e243a] mb-2"
                            >
                                Amount / Quantity
                            </label>

                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                min="0"
                                step="0.01"
                                required
                                class="w-full border rounded-xl px-4 py-3"
                            >

                        </div>


                        <div>

                            <label
                                for="unit"
                                class="block font-semibold text-[#0e243a] mb-2"
                            >
                                Unit
                            </label>

                            <input
                                type="text"
                                name="unit"
                                id="unit"
                                placeholder="e.g. piece, sack, can"
                                class="w-full border rounded-xl px-4 py-3"
                            >

                        </div>

                    </div>


                    <!-- ================================= -->
                    <!-- PROOF OF PAYMENT -->
                    <!-- ================================= -->

                    <div
                        id="proofOfPaymentField"
                        class="mb-6 hidden"
                    >

                        <label
                            for="proof_of_payment"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Proof of Payment
                        </label>

                        <input
                            type="file"
                            name="proof_of_payment"
                            id="proof_of_payment"
                            accept="image/jpeg,image/png,image/jpg,application/pdf"
                            class="w-full border rounded-xl px-4 py-3 bg-white"
                        >

                        <p class="text-sm text-gray-500 mt-2">
                            Upload a screenshot or receipt of your online payment.
                            Accepted files: JPG, JPEG, PNG, PDF.
                        </p>

                    </div>


                    <!-- ================================= -->
                    <!-- SUBMIT -->
                    <!-- ================================= -->

                    <div class="flex justify-end gap-3">

                        <button
                            type="button"
                            onclick="closeModal()"
                            class="px-6 py-3 rounded-full bg-gray-400 text-white hover:bg-gray-500 transition"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="px-8 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold hover:bg-[#d39a11] transition"
                        >
                            Submit Donation
                        </button>

                    </div>


                </form>

            </div>

        </div>

    `;


    // ========================================
    // SHOW MODAL
    // ========================================

    const modal = document.getElementById('ngoModal');
    const content = document.getElementById('modalContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.classList.remove('modal-exit');
    content.classList.add('modal-enter');

    document.body.style.overflow = 'hidden';
}


// ========================================
// CSRF TOKEN
// ========================================

function getCsrfToken() {

    const token =
        document.querySelector('meta[name="csrf-token"]');

    return token
        ? token.getAttribute('content')
        : '';
}


// ========================================
// DONATION TYPE FIELDS
// ========================================

function updateDonationFields() {

    const type =
        document.getElementById('donationType').value;

    const unit =
        document.getElementById('unit');

    const proofOfPayment =
        document.getElementById('proof_of_payment');

    const proofOfPaymentField =
        document.getElementById('proofOfPaymentField');


    // ========================================
    // ONLINE MONETARY
    // ========================================

    if (type === 'ONLINE_MONETARY') {

        proofOfPaymentField.classList.remove('hidden');

        proofOfPayment.required = true;

        unit.value = '';
        unit.placeholder = 'Not applicable';
        unit.disabled = true;

    }


    // ========================================
    // OTHER DONATION TYPES
    // ========================================

    else {

        proofOfPaymentField.classList.add('hidden');

        proofOfPayment.required = false;
        proofOfPayment.value = '';

        unit.disabled = false;

        unit.placeholder =
            'e.g. piece, sack, can';
    }
}


// ========================================
// ANONYMOUS DONATION
// ========================================

function toggleDonorSource() {

    const anonymous =
        document.getElementById('anonymousDonation');

    const source =
        document.getElementById('source');


    if (anonymous.checked) {

        source.value = 'Anonymous';

        source.disabled = true;

    } else {

        source.value = '';

        source.disabled = false;
    }
}


// ========================================
// CLOSE MODAL
// ========================================

function closeModal() {

    const modal =
        document.getElementById('ngoModal');

    const content =
        document.getElementById('modalContent');


    content.classList.remove('modal-enter');

    content.classList.add('modal-exit');


    setTimeout(() => {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.style.overflow = 'auto';

    }, 250);
}


// ========================================
// CLICK OUTSIDE MODAL
// ========================================

const ngoModal =
    document.getElementById('ngoModal');

if (ngoModal) {

    ngoModal.addEventListener('click', function (e) {

        if (e.target.id === 'ngoModal') {

            closeModal();

        }

    });

}