<div id="editNgoModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 opacity-0 transition-opacity duration-200">

    <div id="ngoModalBox"
         class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl relative flex flex-col max-h-[90vh]
                transform scale-95 transition-transform duration-200">

        <button onclick="closeEditNgoModal()" 
            class="absolute top-4 right-5 text-gray-500 hover:text-gray-800 text-2xl font-bold">
            ✕
        </button>

        <div class="p-10 pb-4 border-b">
            <h2 class="text-3xl font-extrabold text-[#0e243a]">
                Edit Organization Details
            </h2>

            <p class="text-gray-500 mt-2">
                Update the information below to keep your organization profile up to date.
            </p>
        </div>

        <div class="p-10 pt-6 overflow-y-auto">

            <form method="POST" action="/update-ngo" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Organization Name
                    </label>
                    <input type="text" name="name"
                        value="<?php echo e($ngo->name ?? ''); ?>"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#f2c94c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#f2c94c] focus:outline-none"><?php echo e($ngo->description ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Contact Number
                    </label>
                    <input type="text" name="contact_number"
                        value="<?php echo e($ngo->contact_number ?? ''); ?>"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#f2c94c] focus:outline-none">
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-[#0e243a] mb-4">
                        Payment Details
                    </h3>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Bank Account
                            </label>
                            <input type="text" name="bank_account"
                                value="<?php echo e($ngo->bank_account ?? ''); ?>"
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#f2c94c] focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                GCash Number
                            </label>
                            <input type="text" name="gcash"
                                value="<?php echo e($ngo->gcash ?? ''); ?>"
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#f2c94c] focus:outline-none">
                        </div>

                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Complete Address
                    </label>
                    <textarea name="address" rows="3"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#f2c94c] focus:outline-none"><?php echo e($ngo->address ?? ''); ?></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6">

                    <button type="button" onclick="closeEditNgoModal()"
                        class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>

                    <button type="submit"
                        class="bg-[#f2c94c] text-[#0e243a] px-6 py-3 rounded-xl font-bold shadow hover:bg-[#e6bd43] transition">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>
    </div>
</div><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/components/edit-ngo-modal.blade.php ENDPATH**/ ?>