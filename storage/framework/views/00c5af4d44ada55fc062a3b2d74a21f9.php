<div id="appModal" class="fixed inset-0 bg-black/40 hidden flex items-start justify-center z-50 overflow-y-auto py-10">

    <div id="modalBox"
         class="bg-white w-[750px] rounded-2xl overflow-hidden shadow-xl p-6 transform transition-all duration-200 scale-95 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-center mb-2">
            <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" class="w-[160px] mx-auto mb-2">
        </div>

        <h2 class="text-xl text-center mb-4">Volunteer Application Form</h2>

        
        <div>

            <p class="mb-2">Personal Information</p>

            <table class="w-full border text-sm mb-4 table-fixed">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2 w-1/4">First Name</td>
                    <td class="border p-2" id="first_name">---</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Last Name</td>
                    <td class="border p-2" id="last_name">---</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Address</td>
                    <td class="border p-2" id="address">---</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Contact Number</td>
                    <td class="border p-2" id="contact">---</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Email Address</td>
                    <td class="border p-2" id="email">---</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Date of Birth</td>
                    <td class="border p-2" id="dob">---</td>
                </tr>
            </table>

            <p class="mb-2">Availability</p>
            <div class="border p-3 text-sm mb-4 bg-white rounded-md">
                <span id="availability_text">---</span>
            </div>

            <p class="mb-2">General Information</p>
            <table class="w-full border text-sm mb-4">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">QUESTION</td>
                    <td class="bg-[#0e243a] text-white p-2">ANSWER</td>
                </tr>
                <tr>
                    <td class="border p-2">Do you have volunteering experience?</td>
                    <td class="border p-2" id="experience">---</td>
                </tr>
                <tr>
                    <td class="border p-2">Describe your experience.</td>
                    <td class="border p-2" id="reason">---</td>
                </tr>
            </table>

            <p class="mb-2">Skills</p>
            <div class="border p-2 text-sm mb-4" id="skills_text">---</div>

            <p class="mb-2">Interests</p>
            <div class="border p-2 text-sm mb-6" id="interests_text">---</div>

            <div class="flex justify-end">
                <button onclick="closeModal()" class="bg-red-500 px-4 py-2 rounded-lg text-white">
                    Close
                </button>
            </div>

        </div>
    </div>
</div><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/components/application-modal.blade.php ENDPATH**/ ?>