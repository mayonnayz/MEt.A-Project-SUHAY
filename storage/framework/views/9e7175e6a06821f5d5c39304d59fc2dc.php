<div id="activityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-4xl rounded-2xl p-8 max-h-[85vh] overflow-hidden">

        <h2 id="modalTitle" class="text-2xl font-bold mb-4"></h2>

        <div id="activityList" class="space-y-3 max-h-[65vh] overflow-y-auto pr-2"></div>

        <button onclick="closeModal()" 
                class="mt-6 bg-gray-500 text-white px-4 py-2 rounded">
            Close
        </button>

    </div>
</div>


<div id="volunteerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl rounded-2xl p-8 max-h-[85vh] overflow-hidden">

        <h2 class="text-2xl font-bold mb-4">Select Volunteer</h2>

        <div id="volunteerList" class="space-y-3 max-h-[65vh] overflow-y-auto pr-2"></div>

        <div class="mt-6 text-right">
            <button onclick="closeVolunteerModal()" 
                class="bg-gray-400 text-white px-4 py-2 rounded">
                Close
            </button>
        </div>

    </div>
</div><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/components/activity-modal.blade.php ENDPATH**/ ?>