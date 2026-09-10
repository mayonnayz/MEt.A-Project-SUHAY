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
                src="<?php echo e(asset('images/suhayLogo.png')); ?>"
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

</div><?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/application-modal.blade.php ENDPATH**/ ?>