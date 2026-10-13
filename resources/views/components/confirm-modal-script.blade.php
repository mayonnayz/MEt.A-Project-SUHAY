
<script>
    let confirmCallback = null;
    let confirmBusy = false;

    function showConfirmModal({
        title = 'Confirm Action',
        message = 'Are you sure you want to continue?',
        confirmText = 'Confirm',
        onConfirm
    }) {
        const modal = document.getElementById('confirmModal');
        const button = document.getElementById('confirmYesBtn');

        if (!modal || !button) {
            console.error('Confirmation modal is missing.');
            return;
        }

        confirmCallback = onConfirm;
        confirmBusy = false;

        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;
        button.textContent = confirmText;
        button.disabled = false;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirmModal() {
        if (confirmBusy) return;

        const modal = document.getElementById('confirmModal');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        confirmCallback = null;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('confirmYesBtn');

        if (!button) return;

        button.addEventListener('click', async function () {
            if (!confirmCallback || confirmBusy) return;

            const callback = confirmCallback;

            confirmBusy = true;
            button.disabled = true;
            button.textContent = 'Processing...';

            try {
                const result = await callback();

                    if (result === true) {
                        closeConfirmModal();
                    }
            } finally {
                confirmBusy = false;
                button.disabled = false;

                if (confirmCallback === callback) {
                    button.textContent = 'Confirm';
                }
            }
        });
    });

    window.showConfirmModal = showConfirmModal;
    window.closeConfirmModal = closeConfirmModal;
</script>
