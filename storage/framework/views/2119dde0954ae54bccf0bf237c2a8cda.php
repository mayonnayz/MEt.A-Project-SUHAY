<!-- EDIT NGO MODAL -->
<div id="editNgoModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div id="ngoModalBox"
         class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl transform scale-95 transition duration-200">

        <!-- HEADER -->
        <div class="sticky top-0 bg-white border-b px-8 py-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-extrabold text-[#0e243a]">
                    Edit Organization Details
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Update your organization's information
                </p>
            </div>

            <button
                type="button"
                onclick="closeEditNgoModal()"
                class="text-gray-400 hover:text-[#0e243a] text-3xl font-bold"
            >
                &times;
            </button>

        </div>


        <!-- FORM -->
        <form action="<?php echo e(url('/sm-ngos/update')); ?>" method="POST">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div class="p-8 space-y-6">

                <!-- ORGANIZATION NAME -->
                <div>
                    <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                        Organization Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="<?php echo e($ngo->name ?? ''); ?>"
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-[#f2c94c]
                               focus:border-transparent"
                    >
                </div>


                <!-- DESCRIPTION -->
                <div>
                    <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                        Organization Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-[#f2c94c]
                               focus:border-transparent resize-none"
                    ><?php echo e($ngo->description ?? ''); ?></textarea>
                </div>


                <!-- CONTACT NUMBER -->
                <div>
                    <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                        Contact Number
                    </label>

                    <input
                        type="text"
                        name="contact_number"
                        value="<?php echo e($ngo->contact_number ?? ''); ?>"
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-[#f2c94c]
                               focus:border-transparent"
                    >
                </div>


                <!-- ADDRESS -->
                <div>
                    <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                        Complete Address
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-[#f2c94c]
                               focus:border-transparent resize-none"
                    ><?php echo e($ngo->address ?? ''); ?></textarea>
                </div>

            </div>


            <!-- FOOTER -->
            <div class="border-t px-8 py-5 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeEditNgoModal()"
                    class="px-6 py-3 rounded-xl font-semibold text-gray-600
                           bg-gray-100 hover:bg-gray-200 transition"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-7 py-3 rounded-xl font-bold
                           bg-[#f2c94c] text-[#0e243a]
                           hover:bg-[#e6bd43] transition"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>
</div><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/components/edit-ngo-modal.blade.php ENDPATH**/ ?>