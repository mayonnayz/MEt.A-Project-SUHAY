<!-- MANAGE PAYMENT ACCOUNTS MODAL -->

<div id="manageAccountsModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[60] p-4">

    <div id="manageAccountsModalBox"
         class="bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl transform scale-95 transition duration-200">

        <!-- HEADER -->
        <div class="sticky top-0 bg-white border-b px-8 py-6 flex items-center justify-between z-10">

            <div>
                <h2 class="text-2xl font-extrabold text-[#0e243a]">
                    Manage Payment Accounts
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Add or update your organization's payment accounts.
                </p>
            </div>

            <button
                type="button"
                onclick="closeManageAccountsModal()"
                class="text-gray-400 hover:text-[#0e243a] text-3xl font-bold"
            >
                &times;
            </button>

        </div>


        <!-- CONTENT -->
        <div class="p-8">

            <!-- ADD ACCOUNT BUTTON -->
            <div class="flex justify-end mb-6">

                <button
                    type="button"
                    onclick="openAddAccountForm()"
                    class="bg-[#f2c94c] text-[#0e243a] px-6 py-3 rounded-xl font-bold hover:bg-[#e6bd43] transition"
                >
                    + Add Payment Account
                </button>

            </div>


            <!-- ADD ACCOUNT FORM -->
            <div id="addAccountForm"
                 class="hidden bg-gray-100 rounded-2xl p-6 mb-8">

                <h3 class="text-lg font-bold text-[#0e243a] mb-5">
                    Add Payment Account
                </h3>

                <form
                    action="<?php echo e(url('/sm-ngos/accounts')); ?>"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <?php echo csrf_field(); ?>

                    <div class="grid md:grid-cols-2 gap-5">

                        <!-- TYPE -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                                Account Type
                            </label>

                            <select
                                name="type"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-[#f2c94c]"
                            >
                                <option value="">Select account type</option>
                                <option value="GCASH">GCash</option>
                                <option value="BPI">BPI</option>
                                <option value="BDO">BDO</option>
                                <option value="UNIONBANK">UnionBank</option>
                                <option value="METROBANK">Metrobank</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>


                        <!-- ACCOUNT NAME -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                                Account Name
                            </label>

                            <input
                                type="text"
                                name="account_name"
                                required
                                placeholder="e.g. TamsNGO"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                                       focus:outline-none focus:ring-2 focus:ring-[#f2c94c]"
                            >
                        </div>


                        <!-- ACCOUNT NUMBER -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                                Account Number
                            </label>

                            <input
                                type="text"
                                name="account_number"
                                required
                                placeholder="Enter account number"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                                       focus:outline-none focus:ring-2 focus:ring-[#f2c94c]"
                            >
                        </div>


                        <!-- QR CODE -->
                        <div>
                            <label class="block text-sm font-semibold text-[#0e243a] mb-2">
                                QR Code
                                <span class="text-gray-400 font-normal">
                                    (Optional)
                                </span>
                            </label>

                            <input
                                type="file"
                                name="qr_code"
                                accept="image/png,image/jpeg,image/jpg"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white"
                            >

                            <p class="text-xs text-gray-500 mt-2">
                                Upload a PNG or JPG QR code.
                            </p>
                        </div>

                    </div>


                    <!-- FORM BUTTONS -->
                    <div class="flex justify-end gap-3 mt-6">

                        <button
                            type="button"
                            onclick="closeAddAccountForm()"
                            class="px-6 py-3 rounded-xl font-semibold bg-white text-gray-600 hover:bg-gray-200"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="px-7 py-3 rounded-xl font-bold bg-[#0e243a] text-white hover:bg-[#173652]"
                        >
                            Add Account
                        </button>

                    </div>

                </form>

            </div>


            <!-- EXISTING ACCOUNTS -->
            <div class="space-y-4">

                <?php $__empty_1 = true; $__currentLoopData = $ngo->bank_accounts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="border rounded-2xl p-5">

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">

                            <!-- ACCOUNT INFO -->
                            <div class="flex items-start gap-4">

                                <div class="w-12 h-12 rounded-xl bg-[#0e243a] text-white flex items-center justify-center font-bold">
                                    <?php echo e(strtoupper(substr($account['type'], 0, 1))); ?>

                                </div>

                                <div>

                                    <div class="flex items-center gap-2">

                                        <h4 class="font-bold text-[#0e243a] text-lg">
                                            <?php echo e($account['type']); ?>

                                        </h4>

                                        <?php if(!empty($account['qr_code'])): ?>
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">
                                                QR Available
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <p class="font-semibold text-gray-700">
                                        <?php echo e($account['account_name']); ?>

                                    </p>

                                    <p class="text-gray-500">
                                        <?php echo e($account['account_number']); ?>

                                    </p>

                                </div>

                            </div>


                            <!-- ACTIONS -->
                            <div class="flex gap-2">

                                <button
                                    type="button"
                                    onclick="openEditAccountModal(<?php echo e($account['id']); ?>)"
                                    class="px-4 py-2 rounded-lg bg-gray-100 text-[#0e243a] font-semibold hover:bg-gray-200"
                                >
                                    Edit
                                </button>

                                <form
                                    action="<?php echo e(url('/sm-ngos/accounts/' . $account['id'])); ?>"
                                    method="POST"
                                    onsubmit="return confirm('Delete this payment account?')"
                                >

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button
                                        type="submit"
                                        class="px-4 py-2 rounded-lg bg-red-100 text-red-600 font-semibold hover:bg-red-200"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div class="text-center py-10 bg-gray-50 rounded-2xl">

                        <p class="text-gray-500">
                            No payment accounts have been added yet.
                        </p>

                        <button
                            type="button"
                            onclick="openAddAccountForm()"
                            class="mt-4 text-[#0e243a] font-bold underline"
                        >
                            Add your first account
                        </button>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="border-t px-8 py-5 flex justify-end">

            <button
                type="button"
                onclick="closeManageAccountsModal()"
                class="bg-gray-100 text-gray-700 px-7 py-3 rounded-xl font-bold hover:bg-gray-200"
            >
                Close
            </button>

        </div>

    </div>

</div><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-main\MEt.A-Project-SUHAY-main\resources\views/components/manage-accounts-modal.blade.php ENDPATH**/ ?>