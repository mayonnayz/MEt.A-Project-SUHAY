
<div
    id="confirmModal"
    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50"
>
    <div class="bg-white w-[400px] max-w-[90%] rounded-xl p-6 shadow-lg">
        <h2
            id="confirmTitle"
            class="text-lg font-bold mb-2"
        >
            Confirm Action
        </h2>

        <p
            id="confirmMessage"
            class="text-gray-600 mb-6"
        >
            Are you sure?
        </p>

        <div class="flex justify-end gap-3">
            <button
                id="confirmCancelBtn"
                type="button"
                class="px-4 py-2 bg-gray-200 rounded-lg"
                onclick="closeConfirmModal()"
            >
                Cancel
            </button>

            <button
                id="confirmYesBtn"
                type="button"
                class="px-4 py-2 bg-[#f2c94c] rounded-lg font-semibold"
            >
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    let confirmCallback = null;

    function showConfirmModal({
        title = "Confirm Action",
        message = "Are you sure?",
        confirmText = "Confirm",
        cancelText = "Cancel",
        showCancel = true,
        onConfirm = null
    }) {
        const modal = document.getElementById("confirmModal");
        const titleElement = document.getElementById("confirmTitle");
        const messageElement = document.getElementById("confirmMessage");
        const yesButton = document.getElementById("confirmYesBtn");
        const cancelButton = document.getElementById("confirmCancelBtn");

        titleElement.textContent = title;
        messageElement.textContent = message;
        yesButton.textContent = confirmText;
        cancelButton.textContent = cancelText;

        cancelButton.classList.toggle("hidden", !showCancel);

        confirmCallback = onConfirm;

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }

    function closeConfirmModal() {
        const modal = document.getElementById("confirmModal");
        const yesButton = document.getElementById("confirmYesBtn");

        modal.classList.add("hidden");
        modal.classList.remove("flex");

        confirmCallback = null;

        yesButton.disabled = false;
        yesButton.textContent = "Confirm";
    }

    document.addEventListener("DOMContentLoaded", function () {
        const yesButton = document.getElementById("confirmYesBtn");

        if (!yesButton) {
            console.error("confirmYesBtn not found");
            return;
        }

        yesButton.addEventListener("click", async function () {
            if (!confirmCallback || yesButton.disabled) {
                return;
            }

            const callback = confirmCallback;

            yesButton.disabled = true;

            try {
                const result = await callback();

                // Simple confirmation, such as Done or Understood.
                if (result === true) {
                    closeConfirmModal();
                    return;
                }

                // Successful assignment:
                // close the current modal first, then show success.
                if (result && result.success === true) {
                    closeConfirmModal();

                    showConfirmModal({
                        title: "Assignment Successful",
                        message: result.message ||
                            "The volunteer has been assigned successfully.",
                        confirmText: "Done",
                        showCancel: false,
                        onConfirm: async () => true
                    });

                    return;
                }

                // If the action failed, keep the current modal open
                // only if it is still the same modal operation.
                yesButton.disabled = false;

            } catch (error) {
                console.error("Confirmation action failed:", error);

                yesButton.disabled = false;
                document.getElementById("confirmTitle").textContent =
                    "Something Went Wrong";

                document.getElementById("confirmMessage").textContent =
                    "The action could not be completed. Please try again.";
            }
        });
    });
</script>
