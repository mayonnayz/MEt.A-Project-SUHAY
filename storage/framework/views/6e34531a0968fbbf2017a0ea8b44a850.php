
<script>
    (() => {
        let confirmCallback = null;
        let confirmBusy = false;

        function showConfirmModal({
            title = 'Confirm Action',
            message = 'Are you sure you want to continue?',
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            showCancel = true,
            onConfirm = null
        } = {}) {
            const modal = document.getElementById('confirmModal');
            const titleElement = document.getElementById('confirmTitle');
            const messageElement = document.getElementById('confirmMessage');
            const yesButton = document.getElementById('confirmYesBtn');
            const cancelButton = document.getElementById('confirmCancelBtn');

            if (!modal || !titleElement || !messageElement || !yesButton || !cancelButton) {
                console.error('Confirmation modal elements are missing.');
                return;
            }

            if (confirmBusy) return;

            confirmCallback = onConfirm;

            titleElement.textContent = title;
            messageElement.textContent = message;
            yesButton.textContent = confirmText;
            cancelButton.textContent = cancelText;

            cancelButton.classList.toggle('hidden', !showCancel);

            yesButton.disabled = false;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmModal() {
            if (confirmBusy) return;

            const modal = document.getElementById('confirmModal');
            const yesButton = document.getElementById('confirmYesBtn');

            modal?.classList.add('hidden');
            modal?.classList.remove('flex');

            confirmCallback = null;

            if (yesButton) {
                yesButton.disabled = false;
                yesButton.textContent = 'Confirm';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const yesButton = document.getElementById('confirmYesBtn');

            if (!yesButton) {
                console.error('confirmYesBtn not found.');
                return;
            }

            yesButton.addEventListener('click', async function () {
                if (!confirmCallback || confirmBusy) return;

                const callback = confirmCallback;

                confirmBusy = true;
                yesButton.disabled = true;
                yesButton.textContent = 'Processing...';

                try {
                    const result = await callback();

                    if (result === true || result?.success === true) {
                        const modal = document.getElementById('confirmModal');

                        modal?.classList.add('hidden');
                        modal?.classList.remove('flex');

                        confirmCallback = null;
                    } else {
                        yesButton.disabled = false;
                        yesButton.textContent = 'Try Again';
                    }
                } catch (error) {
                    console.error('Confirmation action failed:', error);

                    yesButton.disabled = false;
                    yesButton.textContent = 'Try Again';

                    document.getElementById('confirmTitle').textContent =
                        'Something Went Wrong';

                    document.getElementById('confirmMessage').textContent =
                        'The action could not be completed. Please try again.';
                } finally {
                    confirmBusy = false;

                    if (!confirmCallback) {
                        yesButton.disabled = false;
                        yesButton.textContent = 'Confirm';
                    }
                }
            });
        });

        window.showConfirmModal = showConfirmModal;
        window.closeConfirmModal = closeConfirmModal;
    })();


    
window.confirmUpdatePostAction = function (button) {
    const form = button.closest('form');

    if (!form) return;

    window.showConfirmModal({
        title: form.dataset.confirmTitle || 'Confirm Action',
        message: form.dataset.confirmMessage || 'Are you sure?',
        confirmText: form.dataset.confirmText || 'Confirm',
        onConfirm: async () => {
            form.submit();
            return true;
        }
    });
};

</script>
<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/confirm-modal-script.blade.php ENDPATH**/ ?>