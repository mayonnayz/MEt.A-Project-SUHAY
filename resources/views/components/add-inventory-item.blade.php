<div id="addInventoryModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-[500px] rounded-xl p-6 shadow-lg">

        <h2 class="text-xl font-bold mb-4">Add Inventory Item</h2>

        <!-- Item Name -->
        <div class="mb-3">
            <label class="text-sm">Item Name</label>
            <input id="add_item_name" class="w-full bg-gray-100 p-2 rounded">
        </div>

      
                <!-- Category (existing + new) -->
        <div class="mb-3">
            <label class="text-sm">Category</label>

            <!-- Existing categories -->
            <select id="add_category_select"
                class="w-full bg-gray-100 p-2 rounded mb-2">
                <option value="">Select Existing Category</option>
            </select>

            <p class="text-xs text-gray-500 mb-1">or add new:</p>

            <!-- New category input -->
            <input id="add_category_input"
                type="text"
                placeholder="New category"
                class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Quantity -->
        <div class="mb-3">
            <label class="text-sm">Quantity</label>
            <input id="add_quantity" type="number" min="0"
                class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Unit -->
        <div class="mb-3">
            <label class="text-sm">Unit</label>
            <input id="add_unit" class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Threshold -->
       <div class="mb-3">
            <label class="text-sm">Minimum Threshold</label>
            <input id="add_threshold" type="number" min="0"
                class="w-full bg-gray-100 p-2 rounded">
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 mt-4">

            <button onclick="closeAddModal()"
                class="px-4 py-2 bg-gray-300 rounded">
                Close
            </button>

            <button onclick="saveNewItem()"
                class="px-4 py-2 bg-[#f2c94c] rounded">
                Save
            </button>

        </div>

    </div>
</div>