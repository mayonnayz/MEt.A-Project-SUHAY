let selectedItem = null;

function showToast(message) {
    const toast = document.getElementById('toast');
    if (!toast) return;

    toast.innerText = message;
    toast.classList.remove('hidden');

    setTimeout(() => toast.classList.add('hidden'), 3500);
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Show the toast saved before the page reload (after add/update)
document.addEventListener('DOMContentLoaded', function () {
    const message = sessionStorage.getItem('inventoryToast');

    if (message) {
        sessionStorage.removeItem('inventoryToast');
        showToast(message);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const rows = document.querySelectorAll('.inventory-row');
    const inventoryCount = document.getElementById('inventoryCount');

    if (!searchInput || !categoryFilter) return;

    function filterInventory() {
        const searchValue = searchInput.value.toLowerCase().trim();
        const categoryValue = categoryFilter.value.toLowerCase();
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const category = row.dataset.category || '';
            const unit = row.dataset.unit || '';

            const matchesSearch =
                name.includes(searchValue) ||
                category.includes(searchValue) ||
                unit.includes(searchValue);

            const matchesCategory =
                categoryValue === '' || category === categoryValue;

            if (matchesSearch && matchesCategory) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        if (inventoryCount) {
            inventoryCount.innerHTML = `
                <span class="font-semibold text-[#0e243a]">${visibleCount}</span>
                ${visibleCount === 1 ? 'item' : 'items'}
            `;
        }
    }

    searchInput.addEventListener('input', filterInventory);
    categoryFilter.addEventListener('change', filterInventory);
});

function setModalTitle(text) {
    const title = document.getElementById('modalTitle');
    if (title) title.innerText = text;
}

// Quantity is editable only when adding a NEW item.
// When editing, stock changes must go through Inventory Movement.
function setQuantityEditable(editable) {
    const quantity = document.getElementById('quantity');
    if (!quantity) return;

    quantity.readOnly = !editable;
    quantity.min = '0';

    const readonlyClasses = ['cursor-not-allowed', 'bg-gray-50', 'text-gray-500'];
    const editableClasses = ['bg-white', 'text-gray-800'];

    if (editable) {
        quantity.classList.remove(...readonlyClasses);
        quantity.classList.add(...editableClasses);
        quantity.placeholder = 'Enter initial quantity';
    } else {
        quantity.classList.remove(...editableClasses);
        quantity.classList.add(...readonlyClasses);
        quantity.placeholder = '0';
    }
}

function openModal(item) {
    selectedItem = item;

    const modal = document.getElementById('inventoryModal');

    if (!modal) {
        console.error('inventoryModal was not found.');
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setModalTitle('Edit Inventory Item');
    setQuantityEditable(false);

    const itemName = document.getElementById('item_name');
    if (itemName) itemName.value = item.name ?? '';

    const quantity = document.getElementById('quantity');
    if (quantity) quantity.value = item.current_quantity ?? '';

    const unit = document.getElementById('unit');
    if (unit) unit.value = item.unit ?? '';

    const threshold = document.getElementById('threshold');
    if (threshold) threshold.value = item.minimum_threshold ?? '';

    loadCategories(item.category);
    resetButtons();
}

function openAddItemModal() {
    const modal = document.getElementById('inventoryModal');

    if (!modal) {
        console.error('inventoryModal was not found.');
        return;
    }

    selectedItem = null;

    document.getElementById('item_name').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    document.getElementById('threshold').value = '';

    loadCategories('');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setModalTitle('Add Inventory Item');
    setQuantityEditable(true);

    document.getElementById('saveBtn').classList.remove('hidden');
    document.getElementById('closeBtn').innerText = 'Cancel';
}

function closeModal() {
    const modal = document.getElementById('inventoryModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function loadCategories(selected) {
    const category = document.getElementById('category');
    if (!category) return;

    const categories = window.inventoryCategories || [];
    let html = '';

    categories.forEach(cat => {
        html += `
            <option value="${escapeHtml(cat)}" ${cat === selected ? 'selected' : ''}>
                ${escapeHtml(cat)}
            </option>
        `;
    });

    category.innerHTML = html;
}

function resetButtons() {
    const saveBtn = document.getElementById('saveBtn');
    const closeBtn = document.getElementById('closeBtn');

    if (saveBtn) saveBtn.classList.add('hidden');
    if (closeBtn) closeBtn.innerText = 'Close';
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('inventoryModal');
    if (!modal) return;

    modal.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', enableSaveButton);
        field.addEventListener('change', enableSaveButton);
    });
});

function enableSaveButton() {
    const saveBtn = document.getElementById('saveBtn');
    const closeBtn = document.getElementById('closeBtn');

    if (saveBtn) saveBtn.classList.remove('hidden');
    if (closeBtn) closeBtn.innerText = 'Cancel';
}

function getFormValues() {
    return {
        item_name: document.getElementById('item_name').value.trim(),
        category: document.getElementById('category').value,
        quantity: document.getElementById('quantity').value,
        unit: document.getElementById('unit').value.trim(),
        threshold: document.getElementById('threshold').value
    };
}

function validateForm(values) {
    if (values.item_name === '') {
        return 'Item name is required.';
    }

    if (values.category === '') {
        return 'Please select a category.';
    }

    if (values.unit === '') {
        return 'Unit is required.';
    }

    if (!selectedItem) {
        if (values.quantity === '' || Number(values.quantity) < 0) {
            return 'Please enter a valid quantity (0 or more).';
        }
    }

    if (values.threshold === '' || Number(values.threshold) < 0) {
        return 'Please enter a valid minimum stock threshold (0 or more).';
    }

    return null;
}

function confirmSave() {
    const values = getFormValues();
    const error = validateForm(values);

    if (error) {
        showToast(error);
        return;
    }

    const isNew = !selectedItem;

    showConfirmModal({
        title: isNew ? 'Add Inventory Item' : 'Save Changes',
        message: isNew
            ? 'Are you sure you want to add this item?'
            : 'Are you sure you want to save this item?',
        onConfirm: function () {
            return saveToBackend();
        }
    });
}

/* =====================================================
   DUPLICATE NOTICE (same name)
   type: 'same_category'  -> same name + same category
         'other_category' -> same name, different category
   ===================================================== */

function closeSameNameModal() {
    const modal = document.getElementById('sameNameModal');
    if (modal) modal.remove();
}

function showSameNameModal(type, existing, values) {
    closeSameNameModal();

    const itemName = escapeHtml(existing.name);
    const existingCategory = escapeHtml(existing.category);
    const newCategory = escapeHtml(values.category);
    const existingQty = escapeHtml(existing.current_quantity);
    const existingUnit = escapeHtml(existing.unit);
    const addQty = escapeHtml(values.quantity);
    const newThreshold = escapeHtml(values.threshold);

    const isSameCategory = type === 'same_category';

    const title = isSameCategory
        ? 'Item already exists'
        : 'Same item, different category';

    const description = isSameCategory
        ? `<strong>${itemName}</strong> is already in your list under
           <strong>${existingCategory}</strong>
           (currently ${existingQty} ${existingUnit}).`
        : `<strong>${itemName}</strong> is already in your list under
           <strong>${existingCategory}</strong>
           (currently ${existingQty} ${existingUnit}), but you are adding it under
           <strong>${newCategory}</strong>.`;

    const question = isSameCategory
        ? 'Do you want to add the quantity to the existing item?'
        : 'Do you want to keep it as a separate item?';

    const helper = isSameCategory
        ? `
            <p>
                <span class="font-semibold text-[#0e243a]">Yes:</span>
                adds ${addQty} to the current quantity and updates the
                threshold to ${newThreshold}.
            </p>`
        : `
            <p>
                <span class="font-semibold text-[#0e243a]">Yes:</span>
                creates a separate item under ${newCategory}.
            </p>
            <p>
                <span class="font-semibold text-[#0e243a]">No:</span>
                adds ${addQty} to the existing item (stays under
                ${existingCategory}) and updates its threshold to
                ${newThreshold}.
            </p>`;

    const buttons = isSameCategory
        ? `
            <button type="button" id="sameNameBackBtn"
                class="w-1/2 rounded-full border-2 border-[#0e243a] py-2.5 text-sm font-semibold text-[#0e243a] transition hover:bg-gray-50 disabled:opacity-50">
                Go back
            </button>
            <button type="button" id="sameNameMergeBtn"
                class="w-1/2 rounded-full bg-[#f2c94c] py-2.5 text-sm font-bold text-[#0e243a] transition hover:bg-[#e5bb35] disabled:opacity-50">
                Yes, add to existing
            </button>`
        : `
            <button type="button" id="sameNameMergeBtn"
                class="w-1/2 rounded-full border-2 border-[#0e243a] py-2.5 text-sm font-semibold text-[#0e243a] transition hover:bg-gray-50 disabled:opacity-50">
                No, add to existing
            </button>
            <button type="button" id="sameNameSeparateBtn"
                class="w-1/2 rounded-full bg-[#f2c94c] py-2.5 text-sm font-bold text-[#0e243a] transition hover:bg-[#e5bb35] disabled:opacity-50">
                Yes, keep separate
            </button>`;

    const modal = document.createElement('div');
    modal.id = 'sameNameModal';
    modal.className =
        'fixed inset-0 z-[150] flex items-center justify-center bg-black/50 p-4';

    modal.innerHTML = `
        <div class="w-full max-w-md rounded-3xl bg-white p-7 shadow-2xl">

            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-yellow-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#d39a11]"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                    <path d="M10.3 3.5L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.5a2 2 0 00-3.4 0z"></path>
                </svg>
            </div>

            <h2 class="mb-2 text-center text-xl font-bold text-[#0e243a]">${title}</h2>

            <p class="mb-4 text-center text-sm text-gray-600">${description}</p>

            <p class="mb-5 text-center text-sm font-semibold text-[#0e243a]">${question}</p>

            <div class="mb-5 space-y-2 rounded-xl bg-gray-50 p-4 text-xs text-gray-500">
                ${helper}
            </div>

            <div class="flex gap-3">${buttons}</div>

            ${isSameCategory ? '' : `
            <button type="button" id="sameNameBackBtn"
                class="mt-4 w-full text-center text-xs text-gray-400 hover:text-gray-600">
                Go back and edit
            </button>`}

        </div>
    `;

    document.body.appendChild(modal);

    const mergeBtn = document.getElementById('sameNameMergeBtn');
    const separateBtn = document.getElementById('sameNameSeparateBtn');
    const backBtn = document.getElementById('sameNameBackBtn');

    async function choose(choice, activeBtn) {
        [mergeBtn, separateBtn, backBtn].forEach(btn => {
            if (btn) btn.disabled = true;
        });

        const originalText = activeBtn.textContent;
        activeBtn.textContent = 'Saving...';

        const ok = await saveToBackend(choice);

        if (!ok) {
            [mergeBtn, separateBtn, backBtn].forEach(btn => {
                if (btn) btn.disabled = false;
            });
            activeBtn.textContent = originalText;
        }
    }

    if (mergeBtn) mergeBtn.addEventListener('click', () => choose('merge', mergeBtn));
    if (separateBtn) separateBtn.addEventListener('click', () => choose('separate', separateBtn));
    if (backBtn) backBtn.addEventListener('click', closeSameNameModal);
}

/* =====================================================
   SAVE
   ===================================================== */

// onConflict: null | 'separate' | 'merge'
async function saveToBackend(onConflict = null) {
    const csrfElement = document.querySelector('meta[name="csrf-token"]');

    if (!csrfElement) {
        console.error('CSRF token was not found.');
        return false;
    }

    const csrfToken = csrfElement.getAttribute('content');
    const values = getFormValues();
    const isNew = !selectedItem;

    const url = isNew
        ? '/inventory/store'
        : `/inventory/update/${selectedItem.id}`;

    const payload = { ...values };

    if (isNew && onConflict) {
        payload.on_conflict = onConflict;
    }

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        let result = {};

        try {
            result = await response.json();
        } catch (_) {}

        // Same name found: show the notice and let the user decide
        if (response.status === 409 && result.needs_decision) {
            // Returning true closes the "Are you sure?" confirm modal,
            // then the notice appears.
            showSameNameModal(result.type, result.existing || {}, values);
            return true;
        }

        if (!response.ok) {
            let message = isNew
                ? 'Failed to add inventory item.'
                : 'Failed to update inventory.';

            if (result.errors) {
                message = Object.values(result.errors).flat().join(' ');
            } else if (result.message) {
                message = result.message;
            }

            console.error('Save failed:', response.status);
            showToast(message);
            return false;
        }

        closeSameNameModal();
        closeModal();

        const successMessage = isNew
            ? (result.message || 'Item added successfully!')
            : 'Inventory updated successfully!';

        sessionStorage.setItem('inventoryToast', successMessage);

        location.reload();

        return true;

    } catch (error) {
        console.error('Inventory save error:', error);
        showToast('Something went wrong. Please try again.');
        return false;
    }
}

function updateTableRow() {
    if (!selectedItem?.id) return;

    const row = document.querySelector(
        `tr[data-item-id="${selectedItem.id}"]`
    );

    if (!row) {
        location.reload();
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
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}