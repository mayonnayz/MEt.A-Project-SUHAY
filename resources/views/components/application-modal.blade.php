<!-- VIEW APPLICATION MODAL (UPDATED MATCHED VERSION) -->
<div id="appModal" style="display:none;" class="fixed inset-0 bg-black/40 items-center justify-center z-50">

    <div id="modalBox"
         class="bg-[#f8f8f8] w-[650px] max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-700 p-6 relative">

        <!-- HEADER -->
        <div class="text-center mb-3">
            <div class="flex justify-center">
                <img src="{{ asset('images/suhayLogo.png') }}" alt="SUHAY" class="h-28 w-28 object-contain"/>
            </div>
            <div class="text-sm font-semibold">Volunteer Application Form</div>
        </div>

        <!-- PERSONAL INFO -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Personal Information</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">
                <tbody id="modalContent"></tbody>
            </table>
        </div>

        <!-- APPLICATION DETAILS -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Application Details</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">

                <tr>
                    <td class="label p-1">Availability</td>
                    <td class="border p-1" id="day"></td>
                </tr>

                <tr>
                    <td class="label p-1">Skills</td>
                    <td class="border p-1" id="skills_text"></td>
                </tr>

                <tr>
                    <td class="label p-1">Interests</td>
                    <td class="border p-1" id="interests_text"></td>
                </tr>

            </table>
        </div>

        <!-- VOLUNTEER EXPERIENCE -->
        <div class="mt-4">
            <div class="text-xs font-semibold mb-1">Volunteer Experience</div>
            <table class="w-full text-xs border border-gray-400 border-collapse">

                <tr>
                    <td class="label p-1">Has Experience?</td>
                    <td class="border p-1" id="experience"></td>
                </tr>

                <tr>
                    <td class="label p-1">Experience Details</td>
                    <td class="border p-1 whitespace-pre-wrap" id="reason"></td>
                </tr>

            </table>
        </div>

        <!-- ACTION -->
        <div class="mt-6 flex justify-center">
            <button onclick="closeModal()"
                    class="px-8 py-2 rounded-full bg-[#0e243a] text-white font-bold text-sm hover:bg-[#091826] transition">
                Close
            </button>
        </div>

    </div>
</div>