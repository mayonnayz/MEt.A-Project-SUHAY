<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventory</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>


<body class="bg-gray-100">

<div class="flex min-h-screen">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="flex-1 p-6 lg:p-8 bg-gray-100 min-h-screen">

        <?php echo $__env->make('components.header', ['title' => 'Inventory'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <!-- ==========================================
             TABS
        =========================================== -->
<div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 shadow-sm">

    
    <button
        type="button"
        class="bg-gray-200
               text-[#0e243a]
               px-6 py-2
               rounded-full
               font-semibold">
        Master List
    </button>

    
    <a
        href="<?php echo e(route('inventory.movement')); ?>"
        class="bg-[#f2c94c]
               hover:bg-[#e5bb35]
               text-[#0e243a]
               px-6 py-2
               rounded-full
               font-semibold
               transition">
        Inventory Movement
    </a>

</div>


<!-- ==========================================
     MAIN CONTAINER
=========================================== -->

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">


    <!-- ==========================================
         SEARCH / FILTER / ADD ITEM
    =========================================== -->

    <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">


            <!-- ======================================
                 LEFT: SEARCH + CATEGORY
            ======================================= -->

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">


                <!-- SEARCH -->

                <div
                    class="relative flex items-center
                           bg-white
                           border border-gray-200
                           rounded-xl
                           w-full sm:w-72
                           focus-within:border-[#0e243a]
                           focus-within:ring-2
                           focus-within:ring-[#0e243a]/10
                           transition">

                    <img
                        src="/images/searchbar.png"
                        alt="Search"
                        class="absolute left-4 w-4 h-4 object-contain">

                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Search inventory..."
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


                <!-- CATEGORY -->

                <select
                    id="categoryFilter"
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
                        All Categories
                    </option>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e(strtolower($category)); ?>">
                            <?php echo e($category); ?>

                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

            </div>


            <!-- ======================================
                 RIGHT: TOTAL + ADD ITEM
            ======================================= -->

            <div
                class="flex items-center
                       justify-between
                       md:justify-end
                       gap-4
                       w-full
                       md:w-auto">


                <!-- TOTAL ITEMS -->

                <div
                    id="inventoryCount"
                    class="text-sm text-gray-500 whitespace-nowrap">

                    <span class="font-semibold text-[#0e243a]">
                        <?php echo e($inventory->total()); ?>

                    </span>

                    items

                </div>


                <!-- ADD ITEM -->

                <button
                    type="button"
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

                    Add Item

                </button>

            </div>

        </div>

    </div>


            <!-- ==========================================
                 TABLE
            =========================================== -->

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead>

                        <tr class="bg-gray-50 border-b border-gray-200">

                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                #
                            </th>

                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Item Name
                            </th>

                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Category
                            </th>

                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Quantity
                            </th>

                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Unit
                            </th>

                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Status
                            </th>

                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Last Updated
                            </th>

                        </tr>

                    </thead>


                  <tbody
                        id="inventoryTableBody"
                        class="divide-y divide-gray-100">

                        <?php $__empty_1 = true; $__currentLoopData = $inventory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr
                                data-item-id="<?php echo e($item->id); ?>"
                                data-name="<?php echo e(strtolower($item->name)); ?>"
                                data-category="<?php echo e(strtolower($item->category)); ?>"
                                data-unit="<?php echo e(strtolower($item->unit)); ?>"
                                class="inventory-row
                                    cursor-pointer
                                    transition duration-150
                                    hover:bg-gray-50
                                    
                                    <?php if($item->stock_status == 'Low Stock' || $item->stock_status == 'No Stock'): ?>
                                        bg-red-50/60
                                    <?php endif; ?>"

                                onclick="openModal(<?php echo \Illuminate\Support\Js::from($item)->toHtml() ?>)"
                            >


                                <!-- NUMBER -->

                                <td class="py-4 px-6 text-gray-500 font-medium">

                                    <?php echo e($loop->iteration); ?>


                                </td>


                                <!-- ITEM NAME -->

                                <td class="py-4 px-4">

                                    <div class="font-semibold text-[#0e243a]">

                                        <?php echo e($item->name); ?>


                                    </div>

                                </td>


                                <!-- CATEGORY -->

                                <td class="py-4 px-4 text-gray-600">

                                    <?php echo e($item->category); ?>


                                </td>


                                <!-- QUANTITY -->

                                <td
                                    class="py-4 px-4 font-semibold
                                    <?php if($item->stock_status != 'In Stock'): ?>
                                        text-red-600
                                    <?php else: ?>
                                        text-gray-700
                                    <?php endif; ?>">

                                    <?php echo e($item->current_quantity); ?>


                                </td>


                                <!-- UNIT -->

                                <td class="py-4 px-4 text-gray-600">

                                    <?php echo e($item->unit); ?>


                                </td>


                                <!-- STATUS -->

                                <td class="py-4 px-4">

                                    <?php if($item->stock_status == 'In Stock'): ?>

                                        <span
                                            class="inline-flex items-center
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

                                            In Stock

                                        </span>

                                    <?php elseif($item->stock_status == 'Low Stock'): ?>

                                        <span
                                            class="inline-flex items-center
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

                                            Low Stock

                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="inline-flex items-center
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

                                            No Stock

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- LAST UPDATED -->

                                <td class="py-4 px-6 text-gray-500">

                                    <?php echo e($item->last_movement_date); ?>


                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="py-12 text-center text-gray-500">

                                    <p class="font-semibold">
                                        No inventory items found
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        Try adjusting your search or category filter.
                                    </p>

                                </td>

                            </tr>

                        <?php endif; ?>

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
                        <?php echo e($inventory->firstItem() ?? 0); ?>

                    </span>

                    to

                    <span class="font-semibold text-gray-700">
                        <?php echo e($inventory->lastItem() ?? 0); ?>

                    </span>

                    of

                    <span class="font-semibold text-gray-700">
                        <?php echo e($inventory->total()); ?>

                    </span>

                    items

                </div>


                <div>

                    <?php echo e($inventory->links()); ?>


                </div>

            </div>

        </div>

    </div>

</div>


<!-- ==========================================
     TOAST
=========================================== -->

<div
    id="toast"
    class="hidden fixed bottom-5 right-5
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



<?php echo $__env->make('components.inventory-item-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.confirm-modal-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<script>

    window.inventoryCategories =
        <?php echo json_encode($categories->values(), 15, 512) ?>;

</script>



<script src="<?php echo e(asset('js/masterlist.js')); ?>"></script>


</body>

</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/inventory-master-list.blade.php ENDPATH**/ ?>