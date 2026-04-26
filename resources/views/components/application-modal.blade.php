<div id="appModal" class="fixed inset-0 bg-black/40 hidden flex items-start justify-center z-50 overflow-y-auto py-10">
<div id="modalBox"
     class="bg-white w-[750px] rounded-2xl overflow-hidden shadow-xl p-6 transform transition-all duration-200 scale-95 max-h-[90vh] overflow-y-auto">        
     <div class="flex justify-center mb-2">
            <img src="{{ asset('images/suhayLogo.png') }}" class="w-[160px] mx-auto mb-2">
        </div>

        <h2 class="text-xl text-center mb-4">Volunteer Application Form</h2>

        {{-- PAGE 1 --}}
        <div id="page1">

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
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Gender</td>
                    <td class="border p-2" id="gender">---</td>
                </tr>
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Occupation</td>
                    <td class="border p-2" id="occupation">---</td>
                </tr>
            </table>

            <p class="mb-2">Availability</p>
            <table class="w-full border text-sm mb-4">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">DAY</td>
                    <td class="bg-[#0e243a] text-white p-2">TIME</td>
                </tr>
                <tr>
                    <td class="border p-2" id="day">---</td>
                    <td class="border p-2" id="time">---</td>
                </tr>
            </table>

            <p class="mb-2">General Information</p>
            <table class="w-full border text-sm">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">QUESTION</td>
                    <td class="bg-[#0e243a] text-white p-2">ANSWER</td>
                </tr>
                <tr>
                    <td class="border p-2">Do you have volunteering experience?</td>
                    <td class="border p-2" id="experience">---</td>
                </tr>
                <tr>
                    <td class="border p-2">Why do you want to volunteer?</td>
                    <td class="border p-2" id="reason">---</td>
                </tr>
            </table>

            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closeModal()" class="bg-gray-400 px-4 py-2 rounded-lg text-white">
                    Close
                </button>
                <button onclick="nextPage()" class="bg-[#f2c94c] px-4 py-2 rounded-lg">
                    Next
                </button>
            </div>

        </div>

        {{-- PAGE 2 --}}
        <div id="page2" class="hidden">

            <p class="mb-2">Skills</p>
            <table class="w-full border text-sm mb-4">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Skill</td>
                    <td class="bg-[#0e243a] text-white p-2 text-center">Select</td>
                </tr>
                <tr>
                    <td class="border p-2">Teaching / Tutoring</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
                <tr>
                    <td class="border p-2">Medical / First Aid</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
                <tr>
                    <td class="border p-2">Cooking / Food Prep</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
                <tr>
                    <td class="border p-2">Event Organization</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
            </table>

            <p class="mb-2">Interests</p>
            <table class="w-full border text-sm">
                <tr>
                    <td class="bg-[#0e243a] text-white p-2">Interest</td>
                    <td class="bg-[#0e243a] text-white p-2 text-center">Select</td>
                </tr>
                <tr>
                    <td class="border p-2">Community Outreach</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
                <tr>
                    <td class="border p-2">Disaster Relief</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
                <tr>
                    <td class="border p-2">Feeding Programs</td>
                    <td class="border text-center"><input type="checkbox"></td>
                </tr>
            </table>

            <div class="flex justify-end gap-3 mt-6">
                <button onclick="prevPage()" class="bg-gray-400 px-4 py-2 rounded-lg text-white">
                    Back
                </button>

                <button onclick="closeModal()" class="bg-red-500 px-4 py-2 rounded-lg text-white">
                    Close
                </button>
            </div>

        </div>

    </div>
</div>