<!-- Log Activity Modal -->
<div id="logActivityModal"
     class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">

    <div class="bg-[#0e243a] w-full max-w-xl rounded-2xl p-6 text-white shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">

            <h2 id="logActivityModalTitle"
                class="text-xl font-bold text-[#f2c94c]">
                Log Volunteer Activity
            </h2>

            <button type="button"
                    onclick="closeLogActivityModal()"
                    class="text-white text-2xl">
                &times;
            </button>

        </div>


        <!-- Form -->
        <form id="logActivityForm"
              action="/track-activity/store"
              method="POST"
              class="space-y-4">

            <?php echo csrf_field(); ?>

            <!-- Assignment ID -->
            <!-- Empty when adding, filled when editing -->
            <input type="hidden"
                   id="activityAssignmentId"
                   name="assignment_id">


            <!-- Volunteer -->
            <div>
                <label class="block text-sm mb-1">
                    Volunteer
                </label>

                <select id="activityVolunteer"
                        name="account_id"
                        class="w-full p-3 rounded-xl text-black">

                    <option value="" disabled>
                        Select Volunteer
                    </option>

                    <?php $__currentLoopData = $volunteers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $volunteer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($volunteer['id']); ?>">
                            <?php echo e($volunteer['first_name']); ?>

                            <?php echo e($volunteer['last_name']); ?>

                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>
            </div>


            <!-- Activity -->
            <div>
                <label class="block text-sm mb-1">
                    Activity
                </label>

                <select id="activitySelect"
                        name="activity_id"
                        class="w-full p-3 rounded-xl text-black">

                    <option value="" disabled>
                        Select Activity
                    </option>

                    <?php $__currentLoopData = $activities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($activity['id']); ?>">
                            <?php echo e($activity['name']); ?>

                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>
            </div>


           <div>
    <label class="block text-sm mb-1">
        Time In
    </label>

    <input id="activityTimeIn"
           type="time"
           name="time_in"
           disabled
           class="w-full p-3 rounded-xl text-black disabled:bg-gray-400 disabled:cursor-not-allowed">

    <p id="timeDisabledMessage"
       class="text-xs text-gray-300 mt-1">
        Time In and Time Out will be available on the activity date.
    </p>
</div>


<div>
    <label class="block text-sm mb-1">
        Time Out
    </label>

    <input id="activityTimeOut"
           type="time"
           name="time_out"
           disabled
           class="w-full p-3 rounded-xl text-black disabled:bg-gray-400 disabled:cursor-not-allowed">
</div>

            <!-- Status -->
            <div>
                <label class="block text-sm mb-1">
                    Status
                </label>

                <select id="activityStatus"
                        name="status"
                        class="w-full p-3 rounded-xl text-black">

                    <option value="0">
                        On Going
                    </option>

                    <option value="1">
                        Completed
                    </option>

                    <option value="2">
                        Absent
                    </option>

                </select>
            </div>


            <!-- Buttons -->
                <div class="flex justify-between gap-3 pt-2">

                    <!-- Remove Assignment -->
                    <button
                        id="removeAssignmentButton"
                        type="button"
                        class="hidden px-5 py-2 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600"
                    >
                        Remove Assignment
                    </button>


                    <div class="flex gap-3 ml-auto">

                        <button
                            type="button"
                            onclick="closeLogActivityModal()"
                            class="px-5 py-2 rounded-full bg-gray-400 text-black"
                        >
                            Cancel
                        </button>


                        <button
                            id="logActivitySubmitButton"
                            type="submit"
                            class="px-6 py-2 rounded-full bg-[#f2c94c] font-semibold text-[#0e243a]"
                        >
                            Save
                        </button>

                    </div>

                </div>

        </form>

    </div>
</div><?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/components/log-activity-modal.blade.php ENDPATH**/ ?>