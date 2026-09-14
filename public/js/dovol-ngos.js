/* =========================================================
   NGO DATA
========================================================= */

// NGO data should be provided by the Blade page.
// Example:
// <script>
//     window.ngoData = @json($ngos);
// </script>

const ngoData = window.ngoData || [];

let selectedNgoId = null;


/* =========================================================
   MODAL RESET
========================================================= */

function resetAllModals() {

    const modals = [
        'ngoModal',
        'donationModal',
        'confirmDonationModal',
        'loadingModal',
        'successModal'
    ];

    modals.forEach(id => {

        const modal = document.getElementById(id);

        if (modal) {

            modal.classList.remove('flex');
            modal.classList.add('hidden');

        }

    });

    document.body.style.overflow = 'auto';

}


/* =========================================================
   LIVE SEARCH
========================================================= */

const searchInput =
    document.getElementById('searchInput');

const ngoCards =
    document.querySelectorAll('.ngo-card');

const noResults =
    document.getElementById('noResults');


if (searchInput) {

    searchInput.addEventListener('input', function () {

        const search =
            this.value.toLowerCase().trim();

        let visible = 0;


        ngoCards.forEach(card => {

            const name =
                card.dataset.name || '';

            const address =
                card.dataset.address || '';

            const contact =
                card.dataset.contact || '';


            const matches =
                name.includes(search) ||
                address.includes(search) ||
                contact.includes(search);


            if (matches) {

                card.classList.remove('hidden');

                visible++;

            } else {

                card.classList.add('hidden');

            }

        });


        if (noResults) {

            if (visible === 0 && search !== '') {

                noResults.classList.remove('hidden');

            } else {

                noResults.classList.add('hidden');

            }

        }

    });

}


/* =========================================================
   OPEN NGO DETAILS
========================================================= */

function openModal(id) {

    selectedNgoId = id;

    const ngo =
        ngoData.find(n => n.id == id) || {};


    const modalContent =
        document.getElementById('modalContent');

    if (!modalContent) {
        return;
    }


    /*
     * Image paths are supplied by the Blade page
     * through window.suhayAssets.
     */

    const assets =
        window.suhayAssets || {};

    const logoPath =
        assets.logo || '/images/suhayLogo.png';

    const phoneIcon =
        assets.phoneIcon ||
        '/images/VolunteerIcons/VPhone.png';

    const locationIcon =
        assets.locationIcon ||
        '/images/VolunteerIcons/VLocation.png';


    modalContent.innerHTML = `

        <div class="relative">

            <!-- CLOSE -->
            <button
                type="button"
                onclick="closeModal()"
                class="absolute top-4 right-5
                       w-9 h-9
                       rounded-full
                       bg-gray-100
                       text-gray-500
                       hover:bg-gray-200
                       hover:text-[#0e243a]
                       flex items-center
                       justify-center
                       text-xl
                       transition
                       z-10"
                aria-label="Close NGO details"
            >
                &times;
            </button>


            <!-- HEADER -->
            <div
                class="px-6 sm:px-8 pt-7 pb-6
                       border-b border-gray-100"
            >

                <div
                    class="flex flex-col
                           items-center text-center"
                >

                    <div
                        class="w-20 h-20
                               rounded-2xl
                               bg-gray-50
                               border border-gray-200
                               flex items-center
                               justify-center
                               overflow-hidden
                               mb-4"
                    >

                        <img
                            src="${ngo.logo || logoPath}"
                            class="w-full h-full
                                   object-contain p-2"
                            alt="NGO Logo"
                        >

                    </div>


                    <p
                        class="text-xs font-semibold
                               uppercase tracking-wider
                               text-[#d39a11]"
                    >
                        Partner Organization
                    </p>


                    <h2
                        class="text-2xl font-bold
                               text-[#0e243a] mt-1"
                    >
                        ${ngo.name || 'NGO Name'}
                    </h2>


                    <p
                        class="text-sm text-gray-500
                               mt-2 max-w-xl"
                    >
                        ${ngo.description ||
                          'No description available.'}
                    </p>

                </div>

            </div>


            <!-- BODY -->
            <div class="px-6 sm:px-8 py-6">


                <!-- CONTACT INFORMATION -->
                <div
                    class="grid grid-cols-1
                           sm:grid-cols-2
                           gap-4 mb-6"
                >


                    <!-- CONTACT -->
                    <div
                        class="rounded-2xl
                               bg-gray-50
                               border border-gray-200
                               p-4"
                    >

                        <div
                            class="flex items-center
                                   gap-2 mb-2"
                        >

                            <img
                                src="${phoneIcon}"
                                class="w-5 h-5"
                                alt="Contact"
                            >

                            <span
                                class="text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wide
                                       text-gray-400"
                            >
                                Contact
                            </span>

                        </div>


                        <p
                            class="text-sm
                                   font-semibold
                                   text-[#0e243a]"
                        >
                            ${ngo.contact_number || 'N/A'}
                        </p>

                    </div>


                    <!-- LOCATION -->
                    <div
                        class="rounded-2xl
                               bg-gray-50
                               border border-gray-200
                               p-4"
                    >

                        <div
                            class="flex items-center
                                   gap-2 mb-2"
                        >

                            <img
                                src="${locationIcon}"
                                class="w-5 h-5"
                                alt="Location"
                            >

                            <span
                                class="text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wide
                                       text-gray-400"
                            >
                                Location
                            </span>

                        </div>


                        <p
                            class="text-sm
                                   font-semibold
                                   text-[#0e243a]"
                        >
                            ${ngo.address || 'N/A'}
                        </p>

                    </div>

                </div>


                <!-- DONATION CHANNELS -->
                <div>

                    <h3
                        class="text-base
                               font-bold
                               text-[#0e243a]"
                    >
                        Donation Channels
                    </h3>


                    <p
                        class="text-xs
                               text-gray-400
                               mt-1 mb-4"
                    >
                        Choose your preferred way to
                        support this organization.
                    </p>


                    <div
                        class="grid grid-cols-1
                               lg:grid-cols-2
                               gap-4"
                    >


                        <!-- GCASH -->
                        <div
                            class="rounded-2xl
                                   border border-gray-200
                                   bg-white
                                   p-5
                                   shadow-sm"
                        >

                            <div
                                class="flex items-center
                                       justify-between mb-5"
                            >

                                <h4
                                    class="font-bold
                                           text-[#0e243a]"
                                >
                                    GCash
                                </h4>


                                <span
                                    class="text-[10px]
                                           font-bold
                                           px-2.5 py-1
                                           rounded-full
                                           bg-blue-50
                                           text-blue-600"
                                >
                                    E-WALLET
                                </span>

                            </div>


                            <div
                                class="space-y-4 text-sm"
                            >

                                <div>

                                    <span
                                        class="block
                                               text-xs
                                               text-gray-400
                                               mb-1"
                                    >
                                        Account Name
                                    </span>


                                    <span
                                        class="font-semibold
                                               text-[#0e243a]"
                                    >
                                        ${ngo.name || 'N/A'}
                                    </span>

                                </div>


                                <div>

                                    <span
                                        class="block
                                               text-xs
                                               text-gray-400
                                               mb-1"
                                    >
                                        Account Number
                                    </span>


                                    <span
                                        class="font-semibold
                                               text-[#0e243a]"
                                    >
                                        ${ngo.gcash || 'N/A'}
                                    </span>

                                </div>

                            </div>


                            <div
                                class="mt-5
                                       rounded-xl
                                       bg-gray-50
                                       border
                                       border-gray-200
                                       p-4
                                       h-32
                                       flex items-center
                                       justify-center"
                            >

                                <img
                                    src="${logoPath}"
                                    class="h-full
                                           max-w-full
                                           object-contain"
                                    alt="GCash QR"
                                >

                            </div>

                        </div>


                        <!-- BANK -->
                        <div
                            class="rounded-2xl
                                   border border-gray-200
                                   bg-white
                                   p-5
                                   shadow-sm"
                        >

                            <div
                                class="flex items-center
                                       justify-between mb-5"
                            >

                                <h4
                                    class="font-bold
                                           text-[#0e243a]"
                                >
                                    Bank
                                </h4>


                                <span
                                    class="text-[10px]
                                           font-bold
                                           px-2.5 py-1
                                           rounded-full
                                           bg-green-50
                                           text-green-600"
                                >
                                    BANK TRANSFER
                                </span>

                            </div>


                            <div
                                class="space-y-4 text-sm"
                            >

                                <div>

                                    <span
                                        class="block
                                               text-xs
                                               text-gray-400
                                               mb-1"
                                    >
                                        Account Name
                                    </span>


                                    <span
                                        class="font-semibold
                                               text-[#0e243a]"
                                    >
                                        ${ngo.name || 'N/A'}
                                    </span>

                                </div>


                                <div>

                                    <span
                                        class="block
                                               text-xs
                                               text-gray-400
                                               mb-1"
                                    >
                                        Bank
                                    </span>


                                    <span
                                        class="font-semibold
                                               text-[#0e243a]"
                                    >
                                        ${ngo.bank_account || 'N/A'}
                                    </span>

                                </div>


                                <div>

                                    <span
                                        class="block
                                               text-xs
                                               text-gray-400
                                               mb-1"
                                    >
                                        Account Number
                                    </span>


                                    <span
                                        class="font-semibold
                                               text-[#0e243a]"
                                    >
                                        ${ngo.bank_number || 'N/A'}
                                    </span>

                                </div>

                            </div>


                            <div
                                class="mt-5
                                       rounded-xl
                                       bg-gray-50
                                       border
                                       border-gray-200
                                       p-4
                                       h-32
                                       flex items-center
                                       justify-center"
                            >

                                <img
                                    src="${logoPath}"
                                    class="h-full
                                           max-w-full
                                           object-contain"
                                    alt="Bank QR"
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div
                class="px-6 sm:px-8 py-5
                       border-t border-gray-100
                       flex justify-end"
            >

                <button
                    type="button"
                    onclick="openDonationModal()"
                    class="px-8 py-2.5
                           rounded-full
                           bg-[#4CAF50]
                           text-white
                           font-semibold
                           text-sm
                           hover:bg-[#43a047]
                           transition"
                >
                    Donate Now
                </button>

            </div>

        </div>
    `;


    const modal =
        document.getElementById('ngoModal');

    const content =
        document.getElementById('modalContent');


    if (!modal || !content) {
        return;
    }


    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.classList.remove('modal-exit');
    content.classList.add('modal-enter');

    document.body.style.overflow = 'hidden';

}


/* =========================================================
   ADD ITEM
========================================================= */

function addItem() {

    const container =
        document.getElementById('itemsContainer');

    if (!container) {
        return;
    }

    const item =
        document.createElement('div');

    item.className = `
        grid grid-cols-1 sm:grid-cols-3
        gap-3
        p-4
        bg-gray-50
        border border-gray-200
        rounded-xl
    `;

    item.innerHTML = `

        <!-- DESCRIPTION -->
        <div class="sm:col-span-1">

            <label
                class="block text-xs font-semibold
                       text-[#0e243a] mb-1"
            >
                Item
            </label>

            <input
                type="text"
                class="donation-description w-full
                       border-2 border-gray-200
                       rounded-xl
                       px-3 py-2.5
                       text-sm
                       outline-none
                       focus:border-[#0e243a]"
                placeholder="e.g. Century Tuna"
            >

        </div>


        <!-- AMOUNT -->
        <div>

            <label
                class="block text-xs font-semibold
                       text-[#0e243a] mb-1"
            >
                Quantity
            </label>

            <input
                type="number"
                min="1"
                class="donation-amount w-full
                       border-2 border-gray-200
                       rounded-xl
                       px-3 py-2.5
                       text-sm
                       outline-none
                       focus:border-[#0e243a]"
                placeholder="e.g. 10"
            >

        </div>


        <!-- UNIT -->
        <div>

            <label
                class="block text-xs font-semibold
                       text-[#0e243a] mb-1"
            >
                Unit
            </label>

            <input
                type="text"
                class="donation-unit w-full
                       border-2 border-gray-200
                       rounded-xl
                       px-3 py-2.5
                       text-sm
                       outline-none
                       focus:border-[#0e243a]"
                placeholder="e.g. Can"
            >

        </div>

    `;

    container.appendChild(item);
}


/* =========================================================
   REMOVE ITEM
========================================================= */

function removeItem(btn) {

    if (!btn) {
        return;
    }

    btn.parentElement.remove();


    const container =
        document.getElementById('itemsContainer');

    const donationType =
        document.getElementById('donationType');


    if (
        donationType &&
        (
            donationType.value === 'consumable' ||
            donationType.value === 'reusable'
        ) &&
        container &&
        container.children.length === 0
    ) {

        addItem();

    }

}


/* =========================================================
   BACK TO NGO MODAL
========================================================= */

function goBackToNgoModal() {

    const donationModal =
        document.getElementById('donationModal');

    const ngoModal =
        document.getElementById('ngoModal');


    if (!donationModal || !ngoModal) {
        return;
    }


    donationModal.classList.add('hidden');
    donationModal.classList.remove('flex');


    ngoModal.classList.remove('hidden');
    ngoModal.classList.add('flex');


    document.body.style.overflow = 'hidden';

}


/* =========================================================
   CLOSE NGO MODAL
========================================================= */

function closeModal() {

    const ngoModal =
        document.getElementById('ngoModal');

    const content =
        document.getElementById('modalContent');


    if (!ngoModal || !content) {
        return;
    }


    content.classList.remove('modal-enter');
    content.classList.add('modal-exit');


    setTimeout(() => {

        ngoModal.classList.add('hidden');
        ngoModal.classList.remove('flex');

        content.classList.remove('modal-exit');

        document.body.style.overflow = 'auto';

    }, 250);

}


/* =========================================================
   CLICK OUTSIDE MODALS
========================================================= */

const ngoModal =
    document.getElementById('ngoModal');

if (ngoModal) {

    ngoModal.addEventListener('click', function (e) {

        if (e.target.id === 'ngoModal') {
            closeModal();
        }

    });

}


const donationModal =
    document.getElementById('donationModal');

if (donationModal) {

    donationModal.addEventListener('click', function (e) {

        if (e.target.id === 'donationModal') {
            closeDonationModal();
        }

    });

}


const confirmDonationModal =
    document.getElementById('confirmDonationModal');

if (confirmDonationModal) {

    confirmDonationModal.addEventListener('click', function (e) {

        if (e.target.id === 'confirmDonationModal') {
            closeConfirmModal();
        }

    });

}


const successModal =
    document.getElementById('successModal');

if (successModal) {

    successModal.addEventListener('click', function (e) {

        if (e.target.id === 'successModal') {
            closeSuccessModal();
        }

    });

}


/* =========================================================
   OPEN DONATION MODAL
========================================================= */

function openDonationModal() {

    const ngoModal =
        document.getElementById('ngoModal');

    const donationModal =
        document.getElementById('donationModal');

    const donationContent =
        document.getElementById('donationContent');


    if (
        !ngoModal ||
        !donationModal ||
        !donationContent
    ) {
        return;
    }


    ngoModal.classList.add('hidden');
    ngoModal.classList.remove('flex');


    donationModal.classList.remove('hidden');
    donationModal.classList.add('flex');


    donationContent.classList.remove('modal-exit');
    donationContent.classList.add('modal-enter');


    document.body.style.overflow = 'hidden';


    /* RESET FORM */

    const donationType =
        document.getElementById('donationType');

    const donationChannel =
        document.getElementById('donationChannel');

    const items =
        document.getElementById('itemsContainer');


    if (donationType) {

        donationType.value = '';

    }


    if (donationChannel) {

        donationChannel.value = 'gcash';

    }


    // Reset monetary amount
    const donationAmount =
        document.getElementById('donationAmount');

    if (donationAmount) {

        donationAmount.value = '';

    }


    // Reset reference number
    const referenceNumber =
        document.getElementById('referenceNumber');

    if (referenceNumber) {

        referenceNumber.value = '';

    }


    if (items) {

        items.innerHTML = '';

    }


    toggleDonationType();

}


/* =========================================================
   CLOSE DONATION MODAL
========================================================= */

function closeDonationModal() {

    const modal =
        document.getElementById('donationModal');

    const content =
        document.getElementById('donationContent');


    if (!modal || !content) {
        return;
    }


    content.classList.remove('modal-enter');
    content.classList.add('modal-exit');


    setTimeout(() => {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        content.classList.remove('modal-exit');

        document.body.style.overflow = 'auto';

    }, 250);

}


/* =========================================================
   OPEN CONFIRMATION
========================================================= */

function openConfirmModal() {

    const donationType =
        document.getElementById('donationType');

    if (!donationType) {
        return;
    }


    const type =
        donationType.value;


    // =====================================================
    // CHECK DONATION TYPE
    // =====================================================

    if (!type) {

        alert(
            'Please select a donation type.'
        );

        return;
    }


    /* =====================================================
       VALIDATE CONSUMABLE / REUSABLE
    ====================================================== */

    if (
        type === 'consumable' ||
        type === 'reusable'
    ) {

        const rows =
            document.querySelectorAll(
                '#itemsContainer > div'
            );


        let validItem = false;


        rows.forEach(row => {

            const description =
                row.querySelector(
                    '.donation-description'
                );


            const quantity =
                row.querySelector(
                    '.donation-amount'
                );


            const unit =
                row.querySelector(
                    '.donation-unit'
                );


            if (
                description &&
                description.value.trim() !== '' &&
                quantity &&
                quantity.value !== '' &&
                unit &&
                unit.value.trim() !== ''
            ) {

                validItem = true;

            }

        });


        if (!validItem) {

            alert(
                'Please complete at least one donation item.'
            );

            return;

        }

    }


    /* =====================================================
       VALIDATE MONETARY
    ====================================================== */

    if (type === 'monetary') {

        const amountInput =
            document.getElementById('donationAmount');


        const amount =
            amountInput
                ? amountInput.value.trim()
                : '';


        if (!amount || Number(amount) <= 0) {

            alert(
                'Please enter a valid donation amount.'
            );

            return;

        }

    }


    /* =====================================================
       VALIDATE ONLINE MONETARY
    ====================================================== */

    if (type === 'online_monetary') {

        const amountInput =
            document.getElementById('onlineDonationAmount');


        const referenceInput =
            document.getElementById('referenceNumber');


        const amount =
            amountInput
                ? amountInput.value.trim()
                : '';


        const reference =
            referenceInput
                ? referenceInput.value.trim()
                : '';


        if (!amount || Number(amount) <= 0) {

            alert(
                'Please enter a valid donation amount.'
            );

            return;

        }


        if (!reference) {

            alert(
                'Please enter the reference number.'
            );

            return;

        }

    }


    const donationModal =
        document.getElementById('donationModal');

    const modal =
        document.getElementById('confirmDonationModal');

    const content =
        document.getElementById('confirmContent');


    if (!donationModal || !modal || !content) {
        return;
    }


    donationModal.classList.add('hidden');
    donationModal.classList.remove('flex');


    modal.classList.remove('hidden');
    modal.classList.add('flex');


    content.classList.remove('modal-exit');
    content.classList.add('modal-enter');

}


/* =========================================================
   CLOSE CONFIRMATION
========================================================= */

function closeConfirmModal() {

    const modal =
        document.getElementById('confirmDonationModal');

    const content =
        document.getElementById('confirmContent');


    if (!modal || !content) {
        return;
    }


    content.classList.remove('modal-enter');
    content.classList.add('modal-exit');


    setTimeout(() => {

        modal.classList.remove('flex');
        modal.classList.add('hidden');

        content.classList.remove(
            'modal-enter',
            'modal-exit'
        );

    }, 250);

}


/* =========================================================
   START PROCESSING
========================================================= */

function startProcessing() {

    const donationType =
        document.getElementById('donationType');


    if (!donationType) {
        return;
    }


    const type =
        donationType.value;


    // =====================================================
    // CHECK TYPE
    // =====================================================

    if (!type) {

        alert(
            'Please select a donation type.'
        );

        return;

    }


    /*
     * IMPORTANT:
     *
     * We now send the ACTUAL donation type.
     *
     * monetary
     * online_monetary
     * consumable
     * reusable
     *
     * We DO NOT send "non-monetary".
     */

    let data = {

        ngo_id:
            selectedNgoId,

        type:
            type,

        description:
            null,

        amount:
            null,

        unit:
            null,

        reference_no:
            null,

        date:
            new Date()
                .toISOString()
                .split('T')[0]

    };


    /* =====================================================
       MONETARY
    ====================================================== */

    if (type === 'monetary') {

        const amountInput =
            document.getElementById('donationAmount');


        const amount =
            amountInput
                ? amountInput.value.trim()
                : '';


        data.amount =
            amount
                ? Number(amount)
                : null;

    }


    /* =====================================================
       ONLINE MONETARY
    ====================================================== */

    else if (type === 'online_monetary') {

        const amountInput =
            document.getElementById(
                'onlineDonationAmount'
            );


        const referenceInput =
            document.getElementById(
                'referenceNumber'
            );


        const amount =
            amountInput
                ? amountInput.value.trim()
                : '';


        const reference =
            referenceInput
                ? referenceInput.value.trim()
                : '';


        data.amount =
            amount
                ? Number(amount)
                : null;


        data.reference_no =
            reference || null;

    }


    /* =====================================================
       CONSUMABLE / REUSABLE
    ====================================================== */

    else if (
        type === 'consumable' ||
        type === 'reusable'
    ) {

        const rows =
            document.querySelectorAll(
                '#itemsContainer > div'
            );


        /*
         * The donations_table structure stores:
         *
         * description
         * amount
         * unit
         *
         * as one donation record.
         *
         * Therefore, each item is submitted
         * separately.
         */

        if (rows.length === 0) {

            alert(
                'Please add at least one donation item.'
            );

            return;

        }


        // We will process the first valid item here.
        // Additional items are handled below.

        let validRows = [];


        rows.forEach(row => {

            const description =
                row.querySelector(
                    '.donation-description'
                );

            const quantity =
                row.querySelector(
                    '.donation-amount'
                );

            const unit =
                row.querySelector(
                    '.donation-unit'
                );


            if (
                description &&
                description.value.trim() !== ''
            ) {

                validRows.push({

                    description:
                        description.value.trim(),

                    amount:
                        quantity &&
                        quantity.value !== ''
                            ? Number(quantity.value)
                            : null,

                    unit:
                        unit
                            ? unit.value.trim()
                            : null

                });

            }

        });


        if (validRows.length === 0) {

            alert(
                'Please complete at least one donation item.'
            );

            return;

        }


        /*
         * Store the items temporarily.
         * The actual sending happens below.
         */

        data.items = validRows;

    }


    // =====================================================
    // LOG FINAL DATA
    // =====================================================

    console.log(
        'FINAL DONATION DATA:',
        data
    );


    // =====================================================
    // CLOSE CONFIRMATION
    // =====================================================

    const confirmModal =
        document.getElementById(
            'confirmDonationModal'
        );

    const confirmContent =
        document.getElementById(
            'confirmContent'
        );


    if (!confirmModal || !confirmContent) {
        return;
    }


    confirmContent.classList.remove(
        'modal-enter'
    );

    confirmContent.classList.add(
        'modal-exit'
    );


    setTimeout(() => {

        confirmModal.classList.add(
            'hidden'
        );

        confirmModal.classList.remove(
            'flex'
        );


        // =================================================
        // SHOW LOADING
        // =================================================

        const loadingModal =
            document.getElementById(
                'loadingModal'
            );


        if (loadingModal) {

            loadingModal.classList.remove(
                'hidden'
            );

            loadingModal.classList.add(
                'flex'
            );

        }


        // =================================================
        // CSRF TOKEN
        // =================================================

        const csrfToken =
            document.querySelector(
                'meta[name="csrf-token"]'
            )?.getAttribute('content');


        // =================================================
        // SUBMIT FUNCTION
        // =================================================

        function submitDonation(donationData) {

            return fetch(
                '/submit-donation',
                {

                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken || '',

                        'Accept':
                            'application/json'

                    },

                    body:
                        JSON.stringify(
                            donationData
                        )

                }
            )
            .then(res => {

                console.log(
                    'Response status:',
                    res.status
                );

                return res.json();

            });

        }


        // =================================================
        // SUBMIT DONATION
        // =================================================

        let requests = [];


        /*
         * For Consumable / Reusable:
         *
         * Send every item as its own database row.
         */

        if (
            type === 'consumable' ||
            type === 'reusable'
        ) {

            data.items.forEach(item => {

                const itemData = {

                    ngo_id:
                        data.ngo_id,

                    type:
                        data.type,

                    description:
                        item.description,

                    amount:
                        item.amount,

                    unit:
                        item.unit,

                    reference_no:
                        null,

                    date:
                        data.date

                };


                requests.push(
                    submitDonation(
                        itemData
                    )
                );

            });

        }


        /*
         * Monetary / Online Monetary:
         *
         * Send one donation record.
         */

        else {

            const donationData = {

                ngo_id:
                    data.ngo_id,

                type:
                    data.type,

                description:
                    null,

                amount:
                    data.amount,

                unit:
                    null,

                reference_no:
                    data.reference_no,

                date:
                    data.date

            };


            requests.push(
                submitDonation(
                    donationData
                )
            );

        }


        // =================================================
        // WAIT FOR ALL REQUESTS
        // =================================================

        Promise.all(requests)

            .then(results => {

                console.log(
                    'Donation responses:',
                    results
                );


                setTimeout(() => {

                    closeLoadingModal();


                    const failed =
                        results.find(
                            result =>
                                !result.success
                        );


                    if (failed) {

                        alert(
                            'Donation failed: ' +
                            (
                                failed.message ||
                                'Unknown error'
                            )
                        );

                        return;

                    }


                    // =================================================
                    // SUCCESS
                    // =================================================

                    openSuccessModal();

                }, 1500);

            })

            .catch(error => {

                console.error(
                    'Fetch error:',
                    error
                );


                setTimeout(() => {

                    closeLoadingModal();


                    alert(
                        'Network error. Please try again.'
                    );

                }, 1500);

            });

    }, 250);

}


/* =========================================================
   CLOSE LOADING
========================================================= */

function closeLoadingModal() {

    const modal =
        document.getElementById(
            'loadingModal'
        );

    const content =
        document.getElementById(
            'loadingContent'
        );


    if (!modal || !content) {
        return;
    }


    content.classList.remove(
        'modal-enter'
    );

    content.classList.add(
        'modal-exit'
    );


    setTimeout(() => {

        modal.classList.remove(
            'flex'
        );

        modal.classList.add(
            'hidden'
        );

        content.classList.remove(
            'modal-enter',
            'modal-exit'
        );

    }, 250);

}


/* =========================================================
   SUCCESS
========================================================= */

function openSuccessModal() {

    const modal =
        document.getElementById(
            'successModal'
        );

    const content =
        document.getElementById(
            'successContent'
        );


    if (!modal || !content) {
        return;
    }


    modal.classList.remove(
        'hidden'
    );

    modal.classList.add(
        'flex'
    );


    content.classList.remove(
        'modal-exit'
    );

    content.classList.add(
        'modal-enter'
    );

}


function closeSuccessModal() {

    const modal =
        document.getElementById(
            'successModal'
        );

    const content =
        document.getElementById(
            'successContent'
        );


    if (!modal || !content) {
        return;
    }


    content.classList.remove(
        'modal-enter'
    );

    content.classList.add(
        'modal-exit'
    );


    setTimeout(() => {

        resetAllModals();

    }, 250);

}


/* =========================================================
   TOGGLE DONATION TYPE
========================================================= */

function toggleDonationType() {

    const donationType =
        document.getElementById(
            'donationType'
        );


    if (!donationType) {
        return;
    }


    const type =
        donationType.value;


    const nonMonetaryForm =
        document.getElementById(
            'nonMonetaryForm'
        );

    const monetaryForm =
        document.getElementById(
            'monetaryForm'
        );

    const onlineMonetaryForm =
        document.getElementById(
            'onlineMonetaryForm'
        );


    // =====================================================
    // HIDE EVERYTHING
    // =====================================================

    if (nonMonetaryForm) {

        nonMonetaryForm.classList.add(
            'hidden'
        );

    }


    if (monetaryForm) {

        monetaryForm.classList.add(
            'hidden'
        );

    }


    if (onlineMonetaryForm) {

        onlineMonetaryForm.classList.add(
            'hidden'
        );

    }


    // =====================================================
    // MONETARY
    // Physical cash
    // =====================================================

    if (type === 'monetary') {

        if (monetaryForm) {

            monetaryForm.classList.remove(
                'hidden'
            );

        }

    }


    // =====================================================
    // ONLINE MONETARY
    // GCash / Bank + reference
    // =====================================================

    else if (
        type === 'online_monetary'
    ) {

        if (onlineMonetaryForm) {

            onlineMonetaryForm.classList.remove(
                'hidden'
            );

        }

    }


    // =====================================================
    // CONSUMABLE / REUSABLE
    // =====================================================

    else if (
        type === 'consumable' ||
        type === 'reusable'
    ) {

        if (nonMonetaryForm) {

            nonMonetaryForm.classList.remove(
                'hidden'
            );

        }


        const itemsContainer =
            document.getElementById(
                'itemsContainer'
            );


        /*
         * Automatically add one item row
         * when the user chooses Consumable
         * or Reusable.
         */

        if (
            itemsContainer &&
            itemsContainer.children.length === 0
        ) {

            addItem();

        }

    }

}


/* =========================================================
   LOGOUT MODAL
========================================================= */

function openLogoutModal() {

    const modal =
        document.getElementById(
            'logoutModal'
        );


    if (!modal) {
        return;
    }


    modal.classList.remove(
        'hidden'
    );

    modal.classList.add(
        'flex'
    );

}


function closeLogoutModal() {

    const modal =
        document.getElementById(
            'logoutModal'
        );


    if (!modal) {
        return;
    }


    modal.classList.add(
        'hidden'
    );

    modal.classList.remove(
        'flex'
    );

}


/* =========================================================
   ESC KEY
========================================================= */

document.addEventListener(
    'keydown',
    function (event) {

        if (event.key !== 'Escape') {
            return;
        }


        const modals = [
            'ngoModal',
            'donationModal',
            'confirmDonationModal',
            'successModal'
        ];


        for (const id of modals) {

            const modal =
                document.getElementById(id);


            if (
                modal &&
                !modal.classList.contains('hidden')
            ) {

                if (id === 'ngoModal') {

                    closeModal();

                }

                else if (
                    id === 'donationModal'
                ) {

                    closeDonationModal();

                }

                else if (
                    id === 'confirmDonationModal'
                ) {

                    closeConfirmModal();

                }

                else if (
                    id === 'successModal'
                ) {

                    closeSuccessModal();

                }

                break;

            }

        }

    }
);