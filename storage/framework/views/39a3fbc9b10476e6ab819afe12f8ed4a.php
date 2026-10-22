
<div
    id="inventoryModal"
    class="fixed inset-0 z-50 hidden items-center justify-center
           bg-[#0e243a]/50 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-3xl overflow-hidden rounded-[22px]
               border border-gray-200 bg-white shadow-2xl">

        <!-- HEADER -->
        <div class="flex items-center justify-between
                    border-b border-gray-200 px-7 py-6">

            <div>
                <p class="mb-1 text-sm font-bold uppercase tracking-wide
                          text-gray-400">
                    Inventory
                </p>

                
            </div>

            <button
                type="button"
                onclick="closeModal()"
                aria-label="Close modal"
                class="flex h-11 w-11 items-center justify-center
                       rounded-full bg-gray-100 text-gray-500
                       transition hover:bg-gray-200 hover:text-gray-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

        </div>

        <!-- BODY -->
        <div class="space-y-6 px-7 py-7">

            <!-- ITEM NAME AND CATEGORY -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>
                    <label for="item_name"
                           class="mb-2 block text-base font-semibold
                                  text-gray-700">
                        Item Name
                    </label>

                    <input
                        id="item_name"
                        type="text"
                        placeholder="Enter item name"
                        class="w-full rounded-2xl border border-gray-200
                               bg-white px-5 py-4 text-base text-gray-800
                               outline-none transition
                               placeholder:text-gray-400
                               focus:border-[#0e243a]
                               focus:ring-4 focus:ring-[#0e243a]/10">
                </div>

                <div>
                    <label for="category"
                           class="mb-2 block text-base font-semibold
                                  text-gray-700">
                        Category
                    </label>

                    <select
                        id="category"
                        class="w-full rounded-2xl border border-gray-200
                               bg-white px-5 py-4 text-base text-gray-800
                               outline-none transition
                               focus:border-[#0e243a]
                               focus:ring-4 focus:ring-[#0e243a]/10">

                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>
                </div>

            </div>

            <!-- QUANTITY AND UNIT -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>
                    <label for="quantity"
                           class="mb-2 block text-base font-semibold
                                  text-gray-700">
                        Current Quantity
                    </label>

                    <input
                        id="quantity"
                        type="number"
                        placeholder="0"
                        readonly
                        class="w-full cursor-not-allowed rounded-2xl
                               border border-gray-200 bg-gray-50
                               px-5 py-4 text-base text-gray-500
                               outline-none">
                </div>

                <div>
                    <label for="unit"
                           class="mb-2 block text-base font-semibold
                                  text-gray-700">
                        Unit
                    </label>

                    <input
                        id="unit"
                        type="text"
                        placeholder="e.g. pieces, boxes"
                        class="w-full rounded-2xl border border-gray-200
                               bg-white px-5 py-4 text-base text-gray-800
                               outline-none transition
                               placeholder:text-gray-400
                               focus:border-[#0e243a]
                               focus:ring-4 focus:ring-[#0e243a]/10">
                </div>

            </div>

            <!-- MINIMUM STOCK THRESHOLD -->
            <div>
                <label for="threshold"
                       class="mb-2 block text-base font-semibold
                              text-gray-700">
                    Minimum Stock Threshold
                </label>

                <input
                    id="threshold"
                    type="number"
                    min="0"
                    placeholder="Enter minimum quantity"
                    class="w-full rounded-2xl border border-gray-200
                           bg-white px-5 py-4 text-base text-gray-800
                           outline-none transition
                           placeholder:text-gray-400
                           focus:border-[#0e243a]
                           focus:ring-4 focus:ring-[#0e243a]/10">

                <p class="mt-2 text-sm text-gray-400">
                    Items at or below this quantity may require replenishment.
                </p>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="flex flex-col-reverse gap-3
                    border-t border-gray-200 bg-white
                    px-7 py-5 sm:flex-row sm:justify-end">

            <button
                id="closeBtn"
                type="button"
                onclick="closeModal()"
                class="rounded-full bg-gray-100 px-7 py-3
                       text-base font-semibold text-gray-600
                       transition hover:bg-gray-200 hover:text-gray-800">
                Cancel
            </button>

            <button
                id="saveBtn"
                type="button"
                onclick="confirmSave()"
                class="hidden rounded-full bg-[#f2c94c]
                       px-8 py-3 text-base font-bold text-[#0e243a]
                       transition hover:bg-[#e5bb35]
                       focus:outline-none focus:ring-4
                       focus:ring-[#f2c94c]/30">
                Save Changes
            </button>

        </div>

    </div>
</div>
<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/inventory-item-modal.blade.php ENDPATH**/ ?>