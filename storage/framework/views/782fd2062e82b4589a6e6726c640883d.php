<!-- Log Activity Modal -->
<div id="logActivityModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">

    <div class="bg-[#0e243a] w-full max-w-xl rounded-2xl p-6 text-white shadow-lg">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-[#f2c94c]">Log Volunteer Activity</h2>

            <button onclick="closeLogActivityModal()" class="text-white text-2xl">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form action="/track-activity/store" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>

            <!-- Volunteer -->
            <div>
                <label class="block text-sm mb-1">Volunteer</label>
                <select name="volunteer_id" class="w-full p-3 rounded-xl text-black">
                    <option disabled selected>Select Volunteer</option>
                    <?php $__currentLoopData = $volunteers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $volunteer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($volunteer['id']); ?>">
                            <?php echo e($volunteer['first_name']); ?> <?php echo e($volunteer['last_name']); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Activity -->
            <div>
                <label class="block text-sm mb-1">Activity</label>
                <select name="activity_id" class="w-full p-3 rounded-xl text-black">
                    <option disabled selected>Select Activity</option>
                    <?php $__currentLoopData = $activities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($activity['id']); ?>">
                            <?php echo e($activity['name']); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Time In -->
            <div>
                <label class="block text-sm mb-1">Time In</label>
                <input type="datetime-local" name="time_in"
                       class="w-full p-3 rounded-xl text-black">
            </div>

            <!-- Time Out -->
            <div>
                <label class="block text-sm mb-1">Time Out</label>
                <input type="datetime-local" name="time_out"
                       class="w-full p-3 rounded-xl text-black">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm mb-1">Status</label>
                <select name="status" class="w-full p-3 rounded-xl text-black">
                    <option value="On Going">On Going</option>
                    <option value="Completed">Completed</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>


            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="button"
                        onclick="closeLogActivityModal()"
                        class="px-5 py-2 rounded-full bg-gray-400 text-black">
                    Cancel
                </button>

                <button type="submit"
                        class="px-6 py-2 rounded-full bg-[#f2c94c] font-semibold text-[#0e243a]">
                    Save
                </button>
            </div>

        </form>
    </div>
</div><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-elisha\resources\views/components/log-activity-modal.blade.php ENDPATH**/ ?>