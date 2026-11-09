<div id="appModal"
     class="fixed inset-0 hidden items-center justify-center bg-black/40 z-[9999] p-4">

    <div id="modalBox"
         class="relative bg-white w-full max-w-[750px] rounded-2xl overflow-hidden shadow-xl p-6 transform transition-all duration-200 scale-95 max-h-[90vh] overflow-y-auto">

        <button
            type="button"
            onclick="closeModal()"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-4xl font-bold leading-none transition">
            &times;
        </button>

        <div class="flex justify-center mb-2">
            <img
                src="{{ asset('images/suhayLogo.png') }}"
                class="w-[160px] mx-auto mb-2"
                alt="Suhay Logo">
        </div>

        <div id="page1">

            <h2 class="text-xl text-center mb-4">
                Volunteer Information
            </h2>

            <table class="w-full border text-sm mb-4 table-fixed">

                <tr>
                    <td class="bg-[#0e243a] text-white p-2 w-1/4">
                        First Name
                    </td>
                    <td class="border p-2" id="first_name">---</td>
                </tr>

                <tr>
                    <td class="bg-[#0e243a] text-white p-2">
                        Last Name
                    </td>
                    <td class="border p-2" id="last_name">---</td>
                </tr>

                <tr>
                    <td class="bg-[#0e243a] text-white p-2">
                        Skills
                    </td>
                    <td class="border p-2" id="skills_text">---</td>
                </tr>

                <tr>
                    <td class="bg-[#0e243a] text-white p-2">
                        Address
                    </td>
                    <td class="border p-2" id="address">---</td>
                </tr>

                <tr>
                    <td class="bg-[#0e243a] text-white p-2">
                        Contact Number
                    </td>
                    <td class="border p-2" id="contact">---</td>
                </tr>

                <tr>
                    <td class="bg-[#0e243a] text-white p-2">
                        Email Address
                    </td>
                    <td class="border p-2" id="email">---</td>
                </tr>

                <tr>
                    <td class="bg-[#0e243a] text-white p-2">
                        Date of Birth
                    </td>
                    <td class="border p-2" id="dob">---</td>
                </tr>

            </table>

            <div id="modalActions"
                 class="flex justify-end gap-2 mb-4">
            </div>

            <div class="flex justify-between items-center">

                <div></div>

                <button
                    id="eventHistoryButton"
                    type="button"
                    onclick="nextPage()"
                    class="bg-[#0e243a] text-white px-6 py-2 rounded-full">
                    Event History
                </button>

            </div>

        </div>

        <div id="page2" class="hidden">

            <h2 class="text-xl text-center mb-4">
                Event History
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full border text-sm">

                    <thead class="bg-[#0e243a] text-white">

                        <tr>
                            <th class="p-3 border">Event</th>
                            <th class="p-3 border">Application Date</th>
                            <th class="p-3 border">Event Date</th>
                            <th class="p-3 border">Status</th>
                        </tr>

                    </thead>

                    <tbody id="eventHistoryBody">

                        <tr>
                            <td colspan="4"
                                class="p-4 text-center text-gray-500">
                                No event history found.
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="flex justify-between items-center mt-4">

                <button
                    type="button"
                    onclick="prevPage()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-full transition">
                    Back
                </button>

            </div>

        </div>

    </div>

</div>


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
