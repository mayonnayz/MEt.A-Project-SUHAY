<script>
let confirmCallback = null;

function showConfirmModal({ title, message, onConfirm }) {
    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmMessage').innerText = message;

    confirmCallback = onConfirm;

    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmModal').classList.add('flex');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.getElementById('confirmModal').classList.remove('flex');
}

document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById('confirmYesBtn');

    if (!btn) {
        console.error("confirmYesBtn not found");
        return;
    }

    btn.addEventListener('click', function () {
        if (confirmCallback) confirmCallback();
        closeConfirmModal();
    });
});
</script>