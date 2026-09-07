<div id="nonMonetaryModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-[500px] rounded-2xl shadow-xl p-6">

        <!-- Logo -->
        <div class="flex justify-center">
            <img src="/images/suhayLogo.png" class="h-20">
        </div>

        <!-- Title -->
        <h2 class="text-center font-bold text-xl mt-2">
            Donation Form
        </h2>

        <!-- Date + Donor -->
        <p class="text-center text-sm text-gray-600"
            id="nonModalDate">
        </p>

        <p class="text-center text-sm font-medium"
            id="nonModalName">
        </p>

        <!-- Donation Type -->
        <div class="flex justify-between items-center mt-5">
            <span class="font-semibold">
                Donation Type
            </span>

            <span class="border rounded-full px-4 py-1 text-sm">
                Non-Monetary
            </span>
        </div>

        <!-- Table -->
        <table class="w-full mt-4 border text-sm">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Item</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>

            <tbody id="nonMonetaryTable">

            </tbody>
        </table>

        <!-- Buttons -->
        <div class="flex justify-center gap-3 mt-6">
         <button 
             id="nonConfirmBtn"
            onclick="openConfirmDonation()"
            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-full">
            Confirm
        </button>

            <button onclick="closeModal()"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-full">
                Close
            </button>
        </div>

    </div>
</div>