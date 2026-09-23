// ========================================
// DATA FROM BLADE
// ========================================

const ngoData = window.ngoData || [];
const assets = window.suhayAssets || {};


// ========================================
// OPEN NGO MODAL
// ========================================

function openModal(id) {

    const ngo = ngoData.find(n => n.id == id) || {};


    // ========================================
    // NGO LOGO
    // ========================================

    const logoUrl = ngo.logo_url || assets.logo;


    // ========================================
    // MODAL CONTENT
    // ========================================

    document.getElementById('modalContent').innerHTML = `

        <div class="p-8 md:p-12 relative">


            <!-- CLOSE BUTTON -->

            <button
                type="button"
                onclick="closeModal()"
                class="absolute top-5 right-6 text-gray-500 hover:text-gray-800 text-4xl font-bold leading-none"
            >
                &times;
            </button>


            <!-- NGO HEADER -->

            <div class="mb-10">

                <div class="flex items-center gap-6">

                    <!-- LOGO -->
                    <div class="relative w-32 h-32 md:w-40 md:h-40 flex-shrink-0 flex items-center justify-center rounded-full bg-gradient-to-br from-[#fffdf5] to-[#f5f6f8] ring-1 ring-gray-100">

                        <img
                            src="${logoUrl}"
                            class="w-24 h-24 md:w-32 md:h-32 object-contain drop-shadow-sm"
                            alt="NGO Logo"
                            onerror="this.src='${assets.logo}'"
                        >

                    </div>

                    <!-- THIN DIVIDER -->
                    <div class="w-px h-24 md:h-28 bg-gray-200 flex-shrink-0"></div>

                    <!-- NAME -->
                    <h2 class="flex-1 min-w-0 text-3xl md:text-4xl font-bold text-[#0e243a] leading-snug">
                        ${ngo.name || ''}
                    </h2>

                </div>

                <p class="text-base md:text-lg text-gray-600 mt-6 leading-relaxed">
                    ${ngo.description || ''}
                </p>

            </div>


            <!-- ================================= -->
            <!-- NGO CONTACT -->
            <!-- ================================= -->

            <div class="grid sm:grid-cols-2 gap-8 text-base bg-gray-50 p-7 rounded-2xl">

                <div class="flex flex-col items-center text-center gap-3">

                    <div class="flex items-center gap-2 font-semibold text-[#0e243a] text-lg">

                        <img
                            src="${assets.phoneIcon}"
                            class="w-5 h-5"
                            alt="Phone"
                        >

                        Contact

                    </div>

                    <p class="text-gray-600">
                        ${ngo.contact_number || 'N/A'}
                    </p>

                </div>


                <div class="flex flex-col items-center text-center gap-3">

                    <div class="flex items-center gap-2 font-semibold text-[#0e243a] text-lg">

                        <img
                            src="${assets.locationIcon}"
                            class="w-5 h-5"
                            alt="Location"
                        >

                        Location

                    </div>

                    <p class="text-gray-600">
                        ${ngo.address || 'N/A'}
                    </p>

                </div>

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