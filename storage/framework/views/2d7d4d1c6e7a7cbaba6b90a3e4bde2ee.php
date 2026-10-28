
<div
    id="confirmModal"
    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-[100] p-4"
>
    <div class="bg-white w-[400px] max-w-[90%] rounded-xl p-6 shadow-lg">
        <h2 id="confirmTitle" class="text-lg font-bold mb-2 text-[#0e243a]">
            Confirm Action
        </h2>

        <p id="confirmMessage" class="text-sm text-gray-600 mb-6">
            Are you sure you want to continue?
        </p>

        <div class="flex justify-end gap-3">
            <button
                id="confirmCancelBtn"
                type="button"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm"
                onclick="closeConfirmModal()"
            >
                Cancel
            </button>

            <button
                id="confirmYesBtn"
                type="button"
                class="px-4 py-2 bg-[#f2c94c] hover:bg-[#e5bb3e] rounded-lg font-semibold text-sm disabled:opacity-50"
            >
                Confirm
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/confirm-modal.blade.php ENDPATH**/ ?>