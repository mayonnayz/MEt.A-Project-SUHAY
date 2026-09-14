// =====================================================
// INVENTORY JAVASCRIPT
// =====================================================

let selectedItem = null;


// =====================================================
// TOAST
// =====================================================

function showToast(message) {

    const toast = document.getElementById('toast');

    if (!toast) {
        return;
    }

    toast.innerText = message;

    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 2000);
}


// =====================================================
// SEARCH + CATEGORY FILTER
// =====================================================

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('searchInput');

    const categoryFilter =
        document.getElementById('categoryFilter');

    const rows =
        document.querySelectorAll('.inventory-row');

    const inventoryCount =
        document.getElementById('inventoryCount');


    // If elements don't exist, stop
    if (!searchInput || !categoryFilter) {
        return;
    }


    function filterInventory() {

        const searchValue =
            searchInput.value
                .toLowerCase()
                .trim();


        const categoryValue =
            categoryFilter.value
                .toLowerCase();


        let visibleCount = 0;


        rows.forEach(row => {

            const name =
                row.dataset.name || '';

            const category =
                row.dataset.category || '';

            const unit =
                row.dataset.unit || '';


            // Search matches item name,
            // category, or unit
            const matchesSearch =
                name.includes(searchValue) ||
                category.includes(searchValue) ||
                unit.includes(searchValue);


            // Category filter
            const matchesCategory =
                categoryValue === '' ||
                category === categoryValue;


            if (
                matchesSearch &&
                matchesCategory
            ) {

                row.classList.remove('hidden');

                visibleCount++;

            } else {

                row.classList.add('hidden');

            }

        });


        // Update count
        if (inventoryCount) {

            inventoryCount.innerHTML = `
                <span class="font-semibold text-[#0e243a]">
                    ${visibleCount}
                </span>
                ${visibleCount === 1 ? 'item' : 'items'}
            `;

        }

    }


    // Search while typing
    searchInput.addEventListener(
        'input',
        filterInventory
    );


    // Filter when category changes
    categoryFilter.addEventListener(
        'change',
        filterInventory
    );

});


// =====================================================
// OPEN MODAL
// =====================================================

function openModal(item) {

    selectedItem = item;


    const modal =
        document.getElementById('inventoryModal');


    if (!modal) {

        console.error(
            'inventoryModal was not found.'
        );

        return;

    }


    modal.classList.remove('hidden');

    modal.classList.add('flex');


    // Item name

    const itemName =
        document.getElementById('item_name');

    if (itemName) {

        itemName.value =
            item.name ?? '';

    }


    // Quantity

    const quantity =
        document.getElementById('quantity');

    if (quantity) {

        quantity.value =
            item.current_quantity ?? '';

    }


    // Unit

    const unit =
        document.getElementById('unit');

    if (unit) {

        unit.value =
            item.unit ?? '';

    }


    // Threshold

    const threshold =
        document.getElementById('threshold');

    if (threshold) {

        threshold.value =
            item.minimum_threshold ?? '';

    }


    // Category

    loadCategories(
        item.category
    );


    // Reset buttons

    resetButtons();

}


// =====================================================
// CLOSE MODAL
// =====================================================

function closeModal() {

    const modal =
        document.getElementById('inventoryModal');


    if (!modal) {
        return;
    }


    modal.classList.add('hidden');

    modal.classList.remove('flex');

}


// =====================================================
// LOAD CATEGORIES
// =====================================================

function loadCategories(selected) {

    const category =
        document.getElementById('category');


    if (!category) {
        return;
    }


    const categories =
        window.inventoryCategories || [];


    let html = '';


    categories.forEach(cat => {

        html += `
            <option
                value="${cat}"
                ${cat === selected ? 'selected' : ''}
            >
                ${cat}
            </option>
        `;

    });


    category.innerHTML = html;

}


// =====================================================
// RESET BUTTONS
// =====================================================

function resetButtons() {

    const saveBtn =
        document.getElementById('saveBtn');

    const closeBtn =
        document.getElementById('closeBtn');


    if (saveBtn) {

        saveBtn.classList.add('hidden');

    }


    if (closeBtn) {

        closeBtn.innerText =
            'Close';

    }

}


// =====================================================
// DETECT CHANGES
// =====================================================

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const modal =
            document.getElementById(
                'inventoryModal'
            );


        if (!modal) {
            return;
        }


        const fields =
            modal.querySelectorAll(
                'input, select'
            );


        fields.forEach(field => {


            field.addEventListener(
                'input',
                function () {

                    enableSaveButton();

                }
            );


            field.addEventListener(
                'change',
                function () {

                    enableSaveButton();

                }
            );


        });

    }
);


// =====================================================
// ENABLE SAVE
// =====================================================

function enableSaveButton() {

    const saveBtn =
        document.getElementById('saveBtn');

    const closeBtn =
        document.getElementById('closeBtn');


    if (saveBtn) {

        saveBtn.classList.remove(
            'hidden'
        );

    }


    if (closeBtn) {

        closeBtn.innerText =
            'Cancel';

    }

}


// =====================================================
// CONFIRM SAVE
// =====================================================

function confirmSave() {

    if (!selectedItem) {

        alert(
            'Please select an inventory item first.'
        );

        return;

    }


    showConfirmModal({

        title:
            'Save Changes',

        message:
            'Are you sure you want to save this item?',

        onConfirm:
            function () {

                saveToBackend();

            }

    });

}


// =====================================================
// SAVE TO BACKEND
// =====================================================

function saveToBackend() {

    if (!selectedItem?.id) {

        alert(
            'No inventory item selected.'
        );

        return;

    }


    const id =
        selectedItem.id;


    const csrfElement =
        document.querySelector(
            'meta[name="csrf-token"]'
        );


    if (!csrfElement) {

        console.error(
            'CSRF token was not found.'
        );

        return;

    }


    const csrfToken =
        csrfElement.getAttribute(
            'content'
        );


    const itemName =
        document.getElementById(
            'item_name'
        ).value;


    const category =
        document.getElementById(
            'category'
        ).value;


    const quantity =
        document.getElementById(
            'quantity'
        ).value;


    const unit =
        document.getElementById(
            'unit'
        ).value;


    const threshold =
        document.getElementById(
            'threshold'
        ).value;


    fetch(
        `/inventory/update/${id}`,
        {

            method: 'POST',

            headers: {

                'Content-Type':
                    'application/json',

                'Accept':
                    'application/json',

                'X-CSRF-TOKEN':
                    csrfToken

            },

            body:
                JSON.stringify({

                    item_name:
                        itemName,

                    category:
                        category,

                    quantity:
                        quantity,

                    unit:
                        unit,

                    threshold:
                        threshold

                })

        }
    )


    .then(
        async response => {

            const text =
                await response.text();


            console.log(
                'STATUS:',
                response.status
            );


            console.log(
                'RESPONSE:',
                text
            );


            if (!response.ok) {

                throw new Error(
                    `Update failed: ${response.status}`
                );

            }


            return text;

        }
    )


    .then(
        data => {

            showToast(
                'Inventory updated successfully!'
            );


            setTimeout(
                () => {

                    location.reload();

                },
                500
            );

        }
    )


    .catch(
        error => {

            console.error(
                'Inventory update error:',
                error
            );


            showToast(
                'Failed to update inventory.'
            );

        }
    );

}


// =====================================================
// UPDATE TABLE ROW
// =====================================================

function updateTableRow() {

    if (!selectedItem?.id) {
        return;
    }


    const row =
        document.querySelector(
            `tr[data-item-id="${selectedItem.id}"]`
        );


    if (!row) {

        location.reload();

        return;

    }


    row.children[1].innerText =
        document.getElementById(
            'item_name'
        ).value;


    row.children[2].innerText =
        document.getElementById(
            'category'
        ).value;


    row.children[3].innerText =
        document.getElementById(
            'quantity'
        ).value;


    row.children[4].innerText =
        document.getElementById(
            'unit'
        ).value;


    closeModal();

}


// =====================================================
// LOGOUT MODAL
// =====================================================

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


// =====================================================
// CLOSE LOGOUT MODAL
// =====================================================

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