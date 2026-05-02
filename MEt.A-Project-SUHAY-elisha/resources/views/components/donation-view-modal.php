<div id="donationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    
    <div class="bg-white w-[420px] rounded-2xl shadow-xl p-6 relative">

        

        <!-- Logo -->
        <div class="flex justify-center">
            <img src="/images/suhayLogo.png" class="h-20" alt="Logo">
        </div>

        <!-- Title -->
        <h2 class="text-center font-bold text-lg mt-2">Donation Form</h2>

        <!-- Date + Donor -->
        <p class="text-center text-sm text-gray-600" id="modalDate">Date: </p>
        <p class="text-center text-sm font-medium" id="modalName">Donor: </p>

        <hr class="my-4">

        <!-- Fields -->
        <div class="space-y-4 text-sm">

            <div class="flex justify-between items-center">
                <span class="font-medium">Donation Type</span>
                <span id="modalType" class="px-4 py-1 rounded-full border text-gray-700"></span>
            </div>

            <div class="flex justify-between items-center">
                <span class="font-medium">Donation Channel</span>
                <span id="modalPayment" class="px-4 py-1 rounded-full border text-gray-700"></span>
            </div>

            <div>
                <label class="font-medium block mb-1">Reference Number</label>
                <input id="modalRef" readonly
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex justify-center gap-3 mt-6">
            <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-full">
                Confirm
            </button>

            <button onclick="closeModal()"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-full">
                Close
            </button>
        </div>

    </div>
</div>