<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">
    @include('components.nav')

    <div class="flex-1 p-8 bg-gray-200 min-h-screen">
    
        @include('components.header', ['title' => 'Inventory'])

        <!-- Tabs -->
        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6">
            <button class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Master List
            </button>
            <button class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Inventory Movement
            </button>
        </div>

        <!-- Container -->
        <div class="bg-white rounded-2xl p-6 shadow-md border-4 border-[#0e243a]">

            <!-- Top Controls -->
           <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

                <!-- LEFT SIDE -->
                <div class="flex gap-3">
                    <!-- Search -->
                    <div class="flex items-center bg-gray-100 px-3 py-2 rounded-full">
                        <span class="mr-2">🔍</span>
                        <input type="text" placeholder="Search"
                            class="bg-transparent outline-none text-sm">
                    </div>

                    <!-- Category -->
                    <select class="bg-gray-100 px-4 py-2 rounded-lg text-sm">
                        <option>Category</option>
                    </select>
                </div>

                <!-- RIGHT SIDE -->
                    <div class="flex flex-col items-end gap-2">

                        <button class="bg-[#f2c94c] px-5 py-2 rounded-full font-semibold">
                            Add Item
                        </button>

                        <span class="text-sm text-gray-600">
                            {{ $inventory->firstItem() }} to {{ $inventory->lastItem() }} of {{ $inventory->total() }}
                        </span>

                        <!-- Pagination -->
                        <div>
                            {{ $inventory->links() }}
                        </div>

                    </div>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">

                    <thead>
                        <tr class="border-b">
                            <th class="py-2 px-2">#</th>
                            <th class="py-2 px-2">Item Name</th>
                            <th class="py-2 px-2">Category</th>
                            <th class="py-2 px-2">Quantity</th>
                            <th class="py-2 px-2">Unit</th>
                            <th class="py-2 px-2">Status</th>
                            <th class="py-2 px-2">Last Updated</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($inventory as $item)
                        <tr 
                            class="cursor-pointer hover:bg-gray-100 transition
                            @if($item->stock_status == 'Low Stock' || $item->stock_status == 'No Stock')
                                bg-red-100
                            @endif"
                            onclick="openModal(@js($item))"
                        >
                            <td class="py-2 px-2">{{ $loop->iteration }}</td>
                            <td class="py-2 px-2">{{ $item->name }}</td>
                            <td class="py-2 px-2">{{ $item->category }}</td>

                            <td class="py-2 px-2 
                                @if($item->stock_status != 'In Stock') text-red-600 font-bold @endif">
                                {{ $item->current_quantity }}
                            </td>

                            <td class="py-2 px-2">{{ $item->unit }}</td>

                            <td class="py-2 px-2
                                @if($item->stock_status == 'In Stock') text-green-600
                                @else text-red-500 font-semibold
                                @endif
                            ">
                                {{ $item->stock_status }}
                            </td>

                            <td class="py-2 px-2">
                                {{ $item->last_movement_date }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        
                </table>
                
            </div>


        </div>

    </div>
</div>
<div id="toast"
     class="hidden fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg transition">
    Saved successfully!
</div>
@include('components.inventory-item-modal')
@include('components.logout-modal')
@include('components.confirm-modal')
@include('components.confirm-modal-script')
<script>
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.innerText = message;

    toast.classList.remove('hidden');
    toast.classList.add('opacity-100');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 2000);
}
let selectedItem = null;

// open modal
function openModal(item) {
    selectedItem = item;

    document.getElementById('inventoryModal').classList.remove('hidden');
    document.getElementById('inventoryModal').classList.add('flex');

    document.getElementById('item_name').value = item.name;
    document.getElementById('quantity').value = item.current_quantity;
    document.getElementById('unit').value = item.unit;
    document.getElementById('threshold').value = item.minimum_threshold;
    // document.getElementById('status').value = item.status;

    loadCategories(item.category);

    resetButtons();
}

// close modal
function closeModal() {
    document.getElementById('inventoryModal').classList.add('hidden');
    document.getElementById('inventoryModal').classList.remove('flex');
}

// load unique categories (simple version)
function loadCategories(selected) {
    const categories = @json($categories->values());
    let html = '';
    categories.forEach(cat => {
        html += `<option value="${cat}" ${cat === selected ? 'selected' : ''}>${cat}</option>`;
    });

    document.getElementById('category').innerHTML = html;
}

// detect edit
document.querySelectorAll('#inventoryModal input, #inventoryModal select')
.forEach(el => {
    el.addEventListener('input', () => {
        document.getElementById('saveBtn').classList.remove('hidden');
        document.getElementById('closeBtn').innerText = "Cancel";
    });
});

// reset buttons
function resetButtons() {
    document.getElementById('saveBtn').classList.add('hidden');
    document.getElementById('closeBtn').innerText = "Close";
}


function confirmSave() {
    showConfirmModal({
        title: "Save Changes",
        message: "Are you sure you want to save this item?",
        onConfirm: () => {
            saveToBackend(); // your real save function
        }
    });
}

function saveToBackend() {

    if (!selectedItem?.id) {
        alert("No item selected");
        return;
    }

    let id = selectedItem.id;
console.log("SELECTED ITEM:", selectedItem);
console.log("ID:", selectedItem?.id);
   fetch(`/inventory/update/${id}`, {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        item_name: document.getElementById('item_name').value,
        category: document.getElementById('category').value,
        quantity: document.getElementById('quantity').value,
        unit: document.getElementById('unit').value,
        threshold: document.getElementById('threshold').value
    })
})
.then(async res => {
    const text = await res.text();
    console.log("STATUS:", res.status);
    console.log("RESPONSE:", text);

    return text;
})
.then(data => {
    console.log("RAW:", data);

   showToast("Inventory updated successfully!");

setTimeout(() => {
    location.reload();
}, 500);
})
.catch(err => console.error(err));
    
}
function updateTableRow() {
    let row = document.querySelector(`tr[onclick*="${selectedItem.id}"]`);

    if (!row) {
        location.reload(); // fallback
        return;
    }

    row.children[1].innerText = document.getElementById('item_name').value;
    row.children[2].innerText = document.getElementById('category').value;
    row.children[3].innerText = document.getElementById('quantity').value;
    row.children[4].innerText = document.getElementById('unit').value;

    closeModal();
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

</script>
</body>
</html>