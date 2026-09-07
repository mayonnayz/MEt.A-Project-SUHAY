
<div id="inventoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    
    <div class="bg-white w-[500px] rounded-xl p-6 shadow-lg">

        <h2 class="text-xl font-bold mb-4">Inventory Details</h2>

        <!-- Item Name -->
        <div class="mb-3">
            <label class="text-sm">Item Name</label>
            <input id="item_name" class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Category -->
        <select id="category" class="w-full bg-gray-100 p-2 rounded">
            @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>

        <!-- Quantity (READ ONLY) -->
        <div class="mb-3">
            <label class="text-sm">Current Quantity</label>
            <input id="quantity" class="w-full bg-gray-100 p-2 rounded" readonly>
        </div>

        <!-- Unit -->
        <div class="mb-3">
            <label class="text-sm">Unit</label>
            <input id="unit" class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Threshold -->
        <div class="mb-3">
            <label class="text-sm">Minimum Threshold</label>
           <input id="threshold" type="number" min="0" class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Status -->
        <!-- <div class="mb-3">
            <label class="text-sm">Status</label>
            <select id="status" class="w-full bg-gray-100 p-2 rounded">
                <option value="1">Active</option>
                <option value="0">Archived</option>
            </select>
        </div> -->

        <!-- Buttons -->
        <div class="flex justify-end gap-2 mt-4">

            <button id="closeBtn" onclick="closeModal()"
                class="px-4 py-2 bg-gray-300 rounded">
                Close
            </button>

            <button id="saveBtn" onclick="confirmSave()"
                class="px-4 py-2 bg-[#f2c94c] rounded hidden">
                Save
            </button>

        </div>

    </div>
</div>