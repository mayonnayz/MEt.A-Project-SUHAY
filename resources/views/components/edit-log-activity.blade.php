<!-- Edit Activity Modal -->
<div id="editActivityModal"
     class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">

    <div class="bg-[#0e243a] w-full max-w-xl rounded-2xl p-6 text-white shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-[#f2c94c]">
                Edit Volunteer Activity
            </h2>

           
        </div>

        <!-- Form -->
        <form id="editActivityForm"
              action="/track-activity/update"
              method="POST"
              class="space-y-4">

            @csrf
            <input type="hidden" name="activity_id" id="editActivityId">
            <input type="hidden" name="assignment_id" id="editAssignmentId">
            <input type="hidden" name="volunteer_id" id="editVolunteerId">

            <!-- Event (READ ONLY) -->
            <div>
                <label class="block text-sm mb-1">Event</label>
                <input type="text"
                       id="editEventText"
                       class="w-full p-3 rounded-xl text-black bg-gray-200"
                       readonly>
            </div>

            <!-- Volunteer (READ ONLY) -->
            <div>
                <label class="block text-sm mb-1">Volunteer</label>
                <input type="text"
                       id="editVolunteerText"
                       class="w-full p-3 rounded-xl text-black bg-gray-200"
                       readonly>
            </div>

            <!-- Activity (READ ONLY) -->
            <div>
                <label class="block text-sm mb-1">Activity</label>
                <input type="text"
                       id="editActivityText"
                       class="w-full p-3 rounded-xl text-black bg-gray-200"
                       readonly>
            </div>

            <!-- Time In -->
            <div>
                <label class="block text-sm mb-1">Time In</label>
                <input type="time"
                       name="time_in"
                       id="editTimeIn"
                       step="1"
                       class="w-full p-3 rounded-xl text-black">
            </div>

            <!-- Time Out -->
            <div>
                <label class="block text-sm mb-1">Time Out</label>
                <input type="time"
                       name="time_out"
                       id="editTimeOut"
                       step="1"
                       class="w-full p-3 rounded-xl text-black">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm mb-1">Status</label>
                <select id="editStatus"
                        name="status"
                        class="w-full p-3 rounded-xl text-black">

                    <option value="1">On Going</option>
                    <option value="2">Completed</option>
                    <option value="3">Absent</option>

                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-2">

                <button type="button"
                        onclick="closeEditActivityModal()"
                        class="px-5 py-2 rounded-full bg-gray-400 text-black">
                    Cancel
                </button>

                <button type="submit"
                        class="px-6 py-2 rounded-full bg-[#f2c94c] font-semibold text-[#0e243a]">
                    Update
                </button>

            </div>

        </form>
    </div>
        @include('components.time-validation')
</div>