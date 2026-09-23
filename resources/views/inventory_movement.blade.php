
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventory Movement</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .movement-row {
            transition: all 0.15s ease;
        }

        .movement-row:hover {
            transform: translateY(-1px);
        }

        /* Modal animation */
        .modal-overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: translateY(15px) scale(0.98);
            opacity: 0;
            transition: all 0.2s ease;
        }

        .modal-overlay.show .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    </style>
</head>


<body class="bg-gray-100">

<div class="flex min-h-screen">

    @include('components.nav')


    <!-- ==========================================
         MAIN CONTENT
    =========================================== -->

    <div class="flex-1 p-6 lg:p-8 bg-gray-100 min-h-screen">

        @include('components.header', ['title' => 'Inventory'])


        <!-- ==========================================
             TABS
        =========================================== -->

    <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 shadow-sm">

    {{-- MASTER LIST --}}
    <a
        href="{{ route('inventory.master') }}"
        class="bg-[#f2c94c]
               hover:bg-[#e5bb35]
               text-[#0e243a]
               px-6 py-2
               rounded-full
               font-semibold
               transition">
        Master List
    </a>

    {{-- INVENTORY MOVEMENT: ACTIVE --}}
    <button
        type="button"
        class="bg-gray-200
               text-[#0e243a]
               px-6 py-2
               rounded-full
               font-semibold">
        Inventory Movement
    </button>

</div>


        <!-- ==========================================
             MAIN CARD
        =========================================== -->

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">


            <!-- ==========================================
                 FILTERS / HEADER
            =========================================== -->

            <div
                class="px-6 py-5
                       border-b border-gray-200
                       flex flex-col
                       lg:flex-row
                       lg:items-center
                       lg:justify-between
                       gap-4">


                <!-- LEFT -->

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">


                    <!-- SEARCH -->

                    <div
                        class="relative flex items-center
                               bg-white
                               border border-gray-200
                               rounded-xl
                               w-full sm:w-80
                               focus-within:border-[#0e243a]
                               focus-within:ring-2
                               focus-within:ring-[#0e243a]/10
                               transition">

                        <svg
                            class="absolute left-4 w-4 h-4 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 4a7.5 7.5 0 016.15 12.65z">
                            </path>

                        </svg>

                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Search movements..."
                            autocomplete="off"
                            class="w-full
                                   bg-transparent
                                   outline-none
                                   text-sm
                                   text-gray-700
                                   placeholder-gray-400
                                   pl-11
                                   pr-4
                                   py-3">

                    </div>


                    <!-- MOVEMENT TYPE -->

                    <select
                        id="movementFilter"
                        class="bg-white
                               border border-gray-200
                               rounded-xl
                               px-4
                               py-3
                               text-sm
                               text-gray-600
                               outline-none
                               focus:border-[#0e243a]
                               focus:ring-2
                               focus:ring-[#0e243a]/10
                               transition">

                        <option value="">
                            All Movement Types
                        </option>

                        <option value="inbound">
                            Inbound
                        </option>

                        <option value="outbound">
                            Outbound
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                    </select>


                    <!-- DATE -->

                    <input
                        id="dateFilter"
                        type="date"
                        class="bg-white
                               border border-gray-200
                               rounded-xl
                               px-4
                               py-3
                               text-sm
                               text-gray-600
                               outline-none
                               focus:border-[#0e243a]
                               focus:ring-2
                               focus:ring-[#0e243a]/10
                               transition">

                </div>


                <!-- RIGHT -->

                <div
                    class="flex items-center
                           justify-between
                           lg:justify-end
                           gap-4
                           w-full
                           lg:w-auto">


                    <!-- TOTAL -->

                    <div
                        id="movementCount"
                        class="text-sm
                               text-gray-500
                               whitespace-nowrap">

                        <span class="font-semibold text-[#0e243a]">
                            {{ $movements->total() }}
                        </span>

                        movements

                    </div>


                    <!-- ADD MOVEMENT -->

                    <button
                        type="button"
                        onclick="openMovementModal()"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               bg-[#f2c94c]
                               hover:bg-[#e5bb35]
                               text-[#0e243a]
                               px-5
                               py-2.5
                               rounded-full
                               font-semibold
                               text-sm
                               shadow-sm
                               transition
                               duration-200
                               hover:shadow-md">

                        <span class="text-lg leading-none">
                            +
                        </span>

                        Add Movement

                    </button>

                </div>

            </div>


            <!-- ==========================================
                 TABLE
            =========================================== -->

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead>

                        <tr class="bg-gray-50 border-b border-gray-200">

                            <th
                                class="py-4 px-6
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                #

                            </th>


                            <th
                                class="py-4 px-4
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                Date

                            </th>


                            <th
                                class="py-4 px-4
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                Movement Type

                            </th>


                            <th
                                class="py-4 px-4
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                Items

                            </th>


                            <th
                                class="py-4 px-4
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                Total Quantity

                            </th>


                            <th
                                class="py-4 px-4
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                Encoded By

                            </th>


                            <th
                                class="py-4 px-6
                                       text-xs
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wide">

                                Remarks

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        id="movementTableBody"
                        class="divide-y divide-gray-100">


                        @forelse($movements as $movement)

                            <tr
                                class="movement-row
                                       cursor-pointer
                                       hover:bg-gray-50"

                                data-movement-id="{{ $movement->id }}"

                                data-search="
                                    {{ strtolower(
                                        ($movement->remarks ?? '') . ' ' .
                                        ($movement->account->name ?? '') . ' ' .
                                        ($movement->movement_type == 0
                                            ? 'inbound'
                                            : ($movement->movement_type == 1
                                                ? 'outbound'
                                                : 'pending'))
                                    ) }}
                                "

                                data-type="{{
                                    $movement->movement_type == 0
                                        ? 'inbound'
                                        : ($movement->movement_type == 1
                                            ? 'outbound'
                                            : 'pending')
                                }}"

                                data-date="{{ $movement->date_updated }}"

                                onclick="openMovementDetails(@js($movement))"
                            >


                                <!-- NUMBER -->

                                <td
                                    class="py-4 px-6
                                           text-gray-500
                                           font-medium">

                                    {{ $movements->firstItem() + $loop->index }}

                                </td>


                                <!-- DATE -->

                                <td class="py-4 px-4">

                                    <div class="font-semibold text-[#0e243a]">

                                        {{ \Carbon\Carbon::parse($movement->date_updated)->format('M d, Y') }}

                                    </div>

                                </td>


                                <!-- MOVEMENT TYPE -->

                                <td class="py-4 px-4">

                                    @if($movement->movement_type == 0)

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   bg-green-100
                                                   text-green-700
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-1.5 h-1.5
                                                       bg-green-500
                                                       rounded-full
                                                       mr-2">
                                            </span>

                                            Inbound

                                        </span>

                                    @elseif($movement->movement_type == 1)

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   bg-red-100
                                                   text-red-700
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-1.5 h-1.5
                                                       bg-red-500
                                                       rounded-full
                                                       mr-2">
                                            </span>

                                            Outbound

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   bg-orange-100
                                                   text-orange-700
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-1.5 h-1.5
                                                       bg-orange-500
                                                       rounded-full
                                                       mr-2">
                                            </span>

                                            Pending

                                        </span>

                                    @endif

                                </td>


                                <!-- ITEMS -->

                                <td class="py-4 px-4">

                                    <span class="font-semibold text-gray-700">

                                        {{ $movement->items->count() }}

                                    </span>

                                    <span class="text-gray-400">

                                        item{{ $movement->items->count() != 1 ? 's' : '' }}

                                    </span>

                                </td>


                                <!-- TOTAL QUANTITY -->

                                <td
                                    class="py-4 px-4
                                           font-semibold
                                           text-gray-700">

                                    {{ $movement->items->sum('quantity') }}

                                </td>


                                <!-- ACCOUNT -->

                                <td class="py-4 px-4">

                                    <div class="text-gray-700">

                                        {{ $movement->account->name ?? 'Unknown User' }}

                                    </div>

                                </td>


                                <!-- REMARKS -->

                                <td
                                    class="py-4 px-6
                                           text-gray-500
                                           max-w-xs">

                                    <div class="truncate max-w-[250px]">

                                        {{ $movement->remarks ?: '—' }}

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="py-12
                                           text-center
                                           text-gray-500">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-12 h-12
                                                   rounded-full
                                                   bg-gray-100
                                                   flex items-center
                                                   justify-center
                                                   mb-3">

                                            <svg
                                                class="w-6 h-6 text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5h6">
                                                </path>

                                            </svg>

                                        </div>

                                        <p class="font-semibold">
                                            No inventory movements found
                                        </p>

                                        <p class="text-sm text-gray-400 mt-1">
                                            Try adjusting your search or filters.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <!-- ==========================================
                 PAGINATION
            =========================================== -->

            <div
                class="px-6 py-4
                       border-t border-gray-200
                       flex flex-col sm:flex-row
                       items-center
                       justify-between
                       gap-3">

                <div class="text-sm text-gray-500">

                    Showing

                    <span class="font-semibold text-gray-700">
                        {{ $movements->firstItem() ?? 0 }}
                    </span>

                    to

                    <span class="font-semibold text-gray-700">
                        {{ $movements->lastItem() ?? 0 }}
                    </span>

                    of

                    <span class="font-semibold text-gray-700">
                        {{ $movements->total() }}
                    </span>

                    movements

                </div>


                <div>

                    {{ $movements->links() }}

                </div>

            </div>

        </div>

    </div>

</div>



<!-- =====================================================
     MOVEMENT DETAILS MODAL
====================================================== -->

<div
    id="movementDetailsModal"
    class="modal-overlay fixed inset-0 z-50
           flex items-center justify-center
           bg-black/40
           backdrop-blur-sm
           p-4">


    <div
        class="modal-content
               bg-white
               rounded-2xl
               shadow-2xl
               w-full
               max-w-2xl
               max-h-[90vh]
               overflow-hidden">


        <!-- HEADER -->

        <div
            class="px-6 py-5
                   border-b border-gray-200
                   flex items-center
                   justify-between">

            <div>

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           text-gray-400
                           font-semibold">

                    Inventory Movement

                </p>

                <h2
                    id="modalMovementTitle"
                    class="text-xl
                           font-bold
                           text-[#0e243a]
                           mt-1">

                    Movement Details

                </h2>

            </div>


            <button
                type="button"
                onclick="closeMovementDetails()"
                class="w-9 h-9
                       rounded-full
                       bg-gray-100
                       hover:bg-gray-200
                       text-gray-500
                       flex items-center
                       justify-center
                       transition">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12">
                    </path>

                </svg>

            </button>

        </div>


        <!-- BODY -->

        <div
            class="p-6 overflow-y-auto max-h-[70vh]">


            <!-- SUMMARY -->

            <div
                class="grid grid-cols-1
                       sm:grid-cols-3
                       gap-3
                       mb-6">


                <div
                    class="bg-gray-50
                           rounded-xl
                           p-4">

                    <p class="text-xs text-gray-400 uppercase font-semibold">
                        Date
                    </p>

                    <p
                        id="modalDate"
                        class="font-semibold text-[#0e243a] mt-1">
                    </p>

                </div>


                <div
                    class="bg-gray-50
                           rounded-xl
                           p-4">

                    <p class="text-xs text-gray-400 uppercase font-semibold">
                        Type
                    </p>

                    <div id="modalType" class="mt-1"></div>

                </div>


                <div
                    class="bg-gray-50
                           rounded-xl
                           p-4">

                    <p class="text-xs text-gray-400 uppercase font-semibold">
                        Encoded By
                    </p>

                    <p
                        id="modalAccount"
                        class="font-semibold text-[#0e243a] mt-1">
                    </p>

                </div>

            </div>


            <!-- ITEMS -->

            <div>

                <div
                    class="flex items-center
                           justify-between
                           mb-3">

                    <h3
                        class="font-semibold
                               text-[#0e243a]">

                        Movement Items

                    </h3>

                    <span
                        id="modalTotalQuantity"
                        class="text-sm
                               text-gray-500">
                    </span>

                </div>


                <div
                    id="modalItems"
                    class="border
                           border-gray-200
                           rounded-xl
                           overflow-hidden">

                </div>

            </div>


            <!-- REMARKS -->

            <div class="mt-5">

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           font-semibold
                           text-gray-400
                           mb-2">

                    Remarks

                </p>

                <div
                    id="modalRemarks"
                    class="bg-gray-50
                           rounded-xl
                           px-4 py-3
                           text-sm
                           text-gray-600">

                </div>

            </div>

        </div>


        <!-- FOOTER -->

        <div
            class="px-6 py-4
                   border-t border-gray-200
                   flex justify-end">

            <button
                type="button"
                onclick="closeMovementDetails()"
                class="px-5 py-2.5
                       rounded-full
                       bg-[#0e243a]
                       hover:bg-[#173652]
                       text-white
                       text-sm
                       font-semibold
                       transition">

                Close

            </button>

        </div>

    </div>

</div>



<!-- =====================================================
     ADD MOVEMENT MODAL
====================================================== -->

<div
    id="addMovementModal"
    class="modal-overlay fixed inset-0 z-50
           flex items-center justify-center
           bg-black/40
           backdrop-blur-sm
           p-4">


    <div
        class="modal-content
               bg-white
               rounded-2xl
               shadow-2xl
               w-full
               max-w-2xl
               max-h-[90vh]
               overflow-hidden">


        <!-- HEADER -->

        <div
            class="px-6 py-5
                   border-b border-gray-200
                   flex items-center
                   justify-between">

            <div>

                <p
                    class="text-xs
                           uppercase
                           tracking-wide
                           text-gray-400
                           font-semibold">

                    Inventory

                </p>

                <h2
                    class="text-xl
                           font-bold
                           text-[#0e243a]
                           mt-1">

                    Add Movement

                </h2>

            </div>


            <button
                type="button"
                onclick="closeMovementModal()"
                class="w-9 h-9
                       rounded-full
                       bg-gray-100
                       hover:bg-gray-200
                       text-gray-500
                       flex items-center
                       justify-center
                       transition">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12">
                    </path>

                </svg>

            </button>

        </div>


        <!-- FORM -->

        <form
            action="{{ route('inventory.movement.store') }}"
            method="POST">

            @csrf


            <div
                class="p-6
                       overflow-y-auto
                       max-h-[70vh]">


                <!-- TYPE + DATE -->

                <div
                    class="grid
                           grid-cols-1
                           sm:grid-cols-2
                           gap-4">


                    <div>

                        <label
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2">

                            Movement Type

                        </label>

                        <select
                            name="movement_type"
                            required
                            class="w-full
                                   border
                                   border-gray-200
                                   rounded-xl
                                   px-4 py-3
                                   text-sm
                                   outline-none
                                   focus:border-[#0e243a]
                                   focus:ring-2
                                   focus:ring-[#0e243a]/10">

                            <option value="0">
                                Inbound
                            </option>

                            <option value="1">
                                Outbound
                            </option>

                            <option value="2">
                                Pending
                            </option>

                        </select>

                    </div>


                    <div>

                        <label
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2">

                            Date

                        </label>

                        <input
                            type="date"
                            name="date_updated"
                            value="{{ date('Y-m-d') }}"
                            required
                            class="w-full
                                   border
                                   border-gray-200
                                   rounded-xl
                                   px-4 py-3
                                   text-sm
                                   outline-none
                                   focus:border-[#0e243a]
                                   focus:ring-2
                                   focus:ring-[#0e243a]/10">

                    </div>

                </div>


                <!-- REMARKS -->

                <div class="mt-4">

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-700
                               mb-2">

                        Remarks

                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        maxlength="300"
                        placeholder="Enter movement remarks..."
                        class="w-full
                               border
                               border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-sm
                               outline-none
                               resize-none
                               focus:border-[#0e243a]
                               focus:ring-2
                               focus:ring-[#0e243a]/10"></textarea>

                </div>


                <!-- ITEMS -->

                <div class="mt-6">

                    <div
                        class="flex items-center
                               justify-between
                               mb-3">

                        <div>

                            <h3
                                class="font-semibold
                                       text-[#0e243a]">

                                Items

                            </h3>

                            <p
                                class="text-xs
                                       text-gray-400
                                       mt-1">

                                Select the inventory items included in this movement.

                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="addMovementItem()"
                            class="text-sm
                                   font-semibold
                                   text-[#0e243a]
                                   hover:text-[#d4a017]
                                   transition">

                            + Add Item

                        </button>

                    </div>


                    <div
                        id="movementItemsContainer"
                        class="space-y-3">


                        <!-- FIRST ITEM -->

                        <div
                            class="movement-item
                                   grid
                                   grid-cols-[1fr_110px_40px]
                                   gap-2
                                   items-end">

                            <div>

                                <label
                                    class="block
                                           text-xs
                                           font-semibold
                                           text-gray-500
                                           mb-1">

                                    Inventory Item

                                </label>

                                <select
                                    name="items[0][inventory_item_id]"
                                    required
                                    class="w-full
                                           border
                                           border-gray-200
                                           rounded-xl
                                           px-3 py-2.5
                                           text-sm
                                           outline-none
                                           focus:border-[#0e243a]">

                                    <option value="">
                                        Select item
                                    </option>

                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item['id'] }}">
                                            {{ $item['name'] }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div>

                                <label
                                    class="block
                                           text-xs
                                           font-semibold
                                           text-gray-500
                                           mb-1">

                                    Quantity

                                </label>

                                <input
                                    type="number"
                                    name="items[0][quantity]"
                                    min="1"
                                    required
                                    placeholder="0"
                                    class="w-full
                                           border
                                           border-gray-200
                                           rounded-xl
                                           px-3 py-2.5
                                           text-sm
                                           outline-none
                                           focus:border-[#0e243a]">

                            </div>


                            <button
                                type="button"
                                onclick="removeMovementItem(this)"
                                class="w-10 h-10
                                       rounded-xl
                                       bg-gray-100
                                       hover:bg-red-50
                                       hover:text-red-600
                                       text-gray-400
                                       flex items-center
                                       justify-center
                                       transition">

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12">
                                    </path>

                                </svg>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->

            <div
                class="px-6 py-4
                       border-t border-gray-200
                       flex justify-end
                       gap-3">

                <button
                    type="button"
                    onclick="closeMovementModal()"
                    class="px-5 py-2.5
                           rounded-full
                           bg-gray-100
                           hover:bg-gray-200
                           text-gray-600
                           text-sm
                           font-semibold
                           transition">

                    Cancel

                </button>


                <button
                    type="submit"
                    class="px-5 py-2.5
                           rounded-full
                           bg-[#f2c94c]
                           hover:bg-[#e5bb35]
                           text-[#0e243a]
                           text-sm
                           font-semibold
                           transition">

                    Save Movement

                </button>

            </div>

        </form>

    </div>

</div>



<!-- =====================================================
     TOAST
====================================================== -->

<div
    id="toast"
    class="hidden fixed
           bottom-5 right-5
           bg-[#0e243a]
           text-white
           px-5 py-3
           rounded-xl
           shadow-lg
           text-sm
           font-medium
           z-50">

    Saved successfully!

</div>



@include('components.logout-modal')


<script src="{{ asset('js/inventory-movement.js') }}"></script>


</body>
</html>

