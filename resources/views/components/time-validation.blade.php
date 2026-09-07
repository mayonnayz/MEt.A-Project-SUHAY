<script>
function attachTimeValidation(timeInId, timeOutId) {
    const timeIn = document.getElementById(timeInId);
    const timeOut = document.getElementById(timeOutId);

    if (!timeIn || !timeOut) return;

    timeIn.addEventListener('change', function () {
        timeOut.min = this.value;
    });

    timeOut.addEventListener('change', function () {
        if (timeIn.value && timeOut.value < timeIn.value) {

            showConfirmModal({
                title: "Invalid Time",
                message: "Time Out cannot be earlier than Time In.",
                showCancel: false,
                onConfirm: () => {
                    timeOut.value = "";
                    closeConfirmModal();
                }
            });
        }
    });
}
</script>