
<div id="donationModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-[420px] rounded-2xl shadow-xl p-6 relative">

        <div class="flex justify-center">
            <img src="/images/suhayLogo.png" class="h-20" alt="Logo">
        </div>

        <h2 class="text-center font-bold text-lg mt-2">
            Donation Details
        </h2>

        <p id="modalDate" class="text-center text-sm text-gray-600">
            Date:
        </p>

        <p id="modalName" class="text-center text-sm font-medium">
            Donor:
        </p>

        <hr class="my-4">

        <div class="space-y-4 text-sm">

            <div class="flex justify-between items-center gap-4">
                <span class="font-medium">Donation Type</span>
                <span id="modalType"
                      class="px-4 py-1 rounded-full border text-gray-700"></span>
            </div>

            <div>
                <label class="font-medium block mb-1">Description</label>
                <div id="modalDescription"
                     class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700"></div>
            </div>

            <div class="flex justify-between items-center">
                <span class="font-medium">Amount</span>
                <span id="modalAmount" class="text-gray-700 font-semibold"></span>
            </div>

            <div class="flex justify-between items-center">
                <span class="font-medium">Unit</span>
                <span id="modalUnit" class="text-gray-700"></span>
            </div>

            <div>
                <label class="font-medium block mb-1">Source</label>
                <div id="modalSource"
                     class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700"></div>
            </div>

            <div class="flex justify-between items-center">
                <span class="font-medium">Status</span>
                <span id="modalStatus"
                      class="px-4 py-1 rounded-full border text-gray-700"></span>
            </div>

        </div>

        <p id="donationActionMessage"
           class="hidden mt-3 text-center text-sm"
           role="status"></p>

        <div class="flex justify-center gap-3 mt-6">

            <button
                id="confirmDonationButton"
                type="button"
                onclick="confirmDonation()"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-full">
                Confirm
            </button>

            <button
                type="button"
                onclick="closeModal()"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-full">
                Close
            </button>

        </div>

    </div>
</div>
