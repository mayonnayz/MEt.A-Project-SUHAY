let selectedItem = null;

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

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('searchInput');

    const categoryFilter =
        document.getElementById('categoryFilter');

    const rows =
        document.querySelectorAll('.inventory-row');

    const inventoryCount =
        document.getElementById('inventoryCount');

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

            const matchesSearch =
                name.includes(searchValue) ||
                category.includes(searchValue) ||
                unit.includes(searchValue);

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

        if (inventoryCount) {

            inventoryCount.innerHTML = `
                <span class="font-semibold text-[#0e243a]">
                    ${visibleCount}
                </span>
                ${visibleCount === 1 ? 'item' : 'items'}
            `;

        }

    }

    searchInput.addEventListener(
        'input',
        filterInventory
    );

    categoryFilter.addEventListener(
        'change',
        filterInventory
    );

});

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

    const itemName =
        document.getElementById('item_name');

    if (itemName) {

        itemName.value =
            item.name ?? '';

    }
    
    const quantity =
        document.getElementById('quantity');

    if (quantity) {

        quantity.value =
            item.current_quantity ?? '';

    }

    const unit =
        document.getElementById('unit');

    if (unit) {

        unit.value =
            item.unit ?? '';

    }

    const threshold =
        document.getElementById('threshold');

    if (threshold) {

        threshold.value =
            item.minimum_threshold ?? '';

    }

    loadCategories(
        item.category
    );


    // Reset buttons

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

    modal.querySelector('h2').innerText = 'Add Inventory Item';

    document.getElementById('saveBtn').classList.remove('hidden');

    document.getElementById('closeBtn').innerText = 'Cancel';
}

function closeModal() {

    const modal =
        document.getElementById('inventoryModal');


    if (!modal) {
        return;
    }


    modal.classList.add('hidden');

    modal.classList.remove('flex');

}

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