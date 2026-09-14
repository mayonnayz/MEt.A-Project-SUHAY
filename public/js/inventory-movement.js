

    /* =====================================================
       SEARCH + FILTER
    ===================================================== */

    const searchInput = document.getElementById('searchInput');
    const movementFilter = document.getElementById('movementFilter');
    const dateFilter = document.getElementById('dateFilter');

    function filterMovements() {

        const search =
            searchInput.value.toLowerCase().trim();

        const type =
            movementFilter.value;

        const date =
            dateFilter.value;

        const rows =
            document.querySelectorAll('.movement-row');

        let visible = 0;

        rows.forEach(row => {

            const rowSearch =
                row.dataset.search.toLowerCase();

            const rowType =
                row.dataset.type;

            const rowDate =
                row.dataset.date;

            const matchesSearch =
                !search ||
                rowSearch.includes(search);

            const matchesType =
                !type ||
                rowType === type;

            const matchesDate =
                !date ||
                rowDate === date;

            if (
                matchesSearch &&
                matchesType &&
                matchesDate
            ) {

                row.classList.remove('hidden');

                visible++;

            } else {

                row.classList.add('hidden');

            }

        });

    }


    searchInput.addEventListener(
        'input',
        filterMovements
    );

    movementFilter.addEventListener(
        'change',
        filterMovements
    );

    dateFilter.addEventListener(
        'change',
        filterMovements
    );



    /* =====================================================
       DETAILS MODAL
    ===================================================== */

    function openMovementDetails(movement) {

        const modal =
            document.getElementById(
                'movementDetailsModal'
            );

        const title =
            document.getElementById(
                'modalMovementTitle'
            );

        const date =
            document.getElementById(
                'modalDate'
            );

        const type =
            document.getElementById(
                'modalType'
            );

        const account =
            document.getElementById(
                'modalAccount'
            );

        const items =
            document.getElementById(
                'modalItems'
            );

        const totalQuantity =
            document.getElementById(
                'modalTotalQuantity'
            );

        const remarks =
            document.getElementById(
                'modalRemarks'
            );


        title.textContent =
            'Movement #' + movement.id;


        date.textContent =
            formatDate(movement.date_updated);


        account.textContent =
            movement.account?.name ??
            movement.account?.full_name ??
            'Unknown User';


        remarks.textContent =
            movement.remarks ||
            'No remarks provided.';


        /* Movement type */

        let typeText = '';
        let typeClass = '';

        if (Number(movement.movement_type) === 0) {

            typeText = 'Inbound';

            typeClass =
                'bg-green-100 text-green-700';

        } else if (
            Number(movement.movement_type) === 1
        ) {

            typeText = 'Outbound';

            typeClass =
                'bg-red-100 text-red-700';

        } else {

            typeText = 'Pending';

            typeClass =
                'bg-orange-100 text-orange-700';

        }


        type.innerHTML = `
            <span class="
                inline-flex
                items-center
                px-3 py-1
                rounded-full
                ${typeClass}
                text-xs
                font-semibold
            ">
                ${typeText}
            </span>
        `;


        /* Items */

        items.innerHTML = '';

        let total = 0;

        const movementItems =
            movement.items ?? [];


        if (movementItems.length === 0) {

            items.innerHTML = `
                <div class="
                    px-4 py-5
                    text-center
                    text-sm
                    text-gray-400
                ">
                    No movement items found.
                </div>
            `;

        } else {

            movementItems.forEach(item => {

                const quantity =
                    Number(item.quantity || 0);

                total += quantity;


                const itemName =
                    item.name ??
                    'Unknown Item';

                const unit =
                    item.unit ??
                    '';


                items.innerHTML += `

                    <div class="
                        flex
                        items-center
                        justify-between
                        px-4 py-3
                        border-b
                        border-gray-100
                        last:border-b-0
                    ">

                        <div>

                            <p class="
                                font-semibold
                                text-[#0e243a]
                            ">
                                ${itemName}
                            </p>

                            <p class="
                                text-xs
                                text-gray-400
                                mt-0.5
                            ">
                                ${unit}
                            </p>

                        </div>

                        <div class="
                            text-right
                        ">

                            <p class="
                                font-bold
                                text-gray-700
                            ">
                                ${quantity}
                            </p>

                            <p class="
                                text-xs
                                text-gray-400
                            ">
                                quantity
                            </p>

                        </div>

                    </div>

                `;

            });

        }


        totalQuantity.textContent =
            total + ' total quantity';


        modal.classList.add('show');

        document.body.classList.add(
            'overflow-hidden'
        );

    }


    function closeMovementDetails() {

        const modal =
            document.getElementById(
                'movementDetailsModal'
            );

        modal.classList.remove('show');

        document.body.classList.remove(
            'overflow-hidden'
        );

    }



    /* =====================================================
       ADD MOVEMENT MODAL
    ===================================================== */

    function openMovementModal() {

        const modal =
            document.getElementById(
                'addMovementModal'
            );

        modal.classList.add('show');

        document.body.classList.add(
            'overflow-hidden'
        );

    }


    function closeMovementModal() {

        const modal =
            document.getElementById(
                'addMovementModal'
            );

        modal.classList.remove('show');

        document.body.classList.remove(
            'overflow-hidden'
        );

    }



    /* =====================================================
       ADD / REMOVE MOVEMENT ITEMS
    ===================================================== */

    let movementItemIndex = 1;


    function addMovementItem() {

        const container =
            document.getElementById(
                'movementItemsContainer'
            );


        const item =
            document.createElement('div');


        item.className =
            'movement-item grid grid-cols-[1fr_110px_40px] gap-2 items-end';


        item.innerHTML = `

            <div>

                <label class="
                    block
                    text-xs
                    font-semibold
                    text-gray-500
                    mb-1
                ">
                    Inventory Item
                </label>

                <select
                    name="items[${movementItemIndex}][inventory_item_id]"
                    required
                    class="
                        w-full
                        border
                        border-gray-200
                        rounded-xl
                        px-3 py-2.5
                        text-sm
                        outline-none
                        focus:border-[#0e243a]
                    "
                >

                    <option value="">
                        Select item
                    </option>

                    @foreach($inventoryItems as $item)

                        <option value="{{$item['id'] }}">

                          {{ $item['name'] }}
                            ({{ $item['current_quantity'] }} {{ $item['unit'] }})

                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="
                    block
                    text-xs
                    font-semibold
                    text-gray-500
                    mb-1
                ">
                    Quantity
                </label>

                <input
                    type="number"
                    name="items[${movementItemIndex}][quantity]"
                    min="1"
                    required
                    placeholder="0"
                    class="
                        w-full
                        border
                        border-gray-200
                        rounded-xl
                        px-3 py-2.5
                        text-sm
                        outline-none
                        focus:border-[#0e243a]
                    "
                >

            </div>


            <button
                type="button"
                onclick="removeMovementItem(this)"
                class="
                    w-10 h-10
                    rounded-xl
                    bg-gray-100
                    hover:bg-red-50
                    hover:text-red-600
                    text-gray-400
                    flex items-center
                    justify-center
                    transition
                "
            >

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12">
                    </path>

                </svg>

            </button>

        `;


        container.appendChild(item);

        movementItemIndex++;

    }


    function removeMovementItem(button) {

        const items =
            document.querySelectorAll(
                '.movement-item'
            );


        if (items.length <= 1) {

            return;

        }


        button
            .closest('.movement-item')
            .remove();

    }



    /* =====================================================
       DATE FORMAT
    ===================================================== */

    function formatDate(dateString) {

        if (!dateString) {
            return '—';
        }

        const date =
            new Date(dateString + 'T00:00:00');

        return date.toLocaleDateString(
            'en-US',
            {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }
        );

    }



    /* =====================================================
       CLOSE MODAL WHEN CLICKING OUTSIDE
    ===================================================== */

    document
        .getElementById('movementDetailsModal')
        .addEventListener('click', function(e) {

            if (e.target === this) {

                closeMovementDetails();

            }

        });


    document
        .getElementById('addMovementModal')
        .addEventListener('click', function(e) {

            if (e.target === this) {

                closeMovementModal();

            }

        });


    /* =====================================================
       ESC KEY
    ===================================================== */

    document.addEventListener(
        'keydown',
        function(e) {

            if (e.key === 'Escape') {

                closeMovementDetails();

                closeMovementModal();

            }

        }
    );
