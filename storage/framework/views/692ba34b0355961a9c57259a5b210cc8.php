

<div
    id="editUpdatePostModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
>
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">

        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-bold text-[#0e243a]">
                Edit NGO Update
            </h2>

            <button
                type="button"
                onclick="closeEditUpdatePostModal()"
                class="text-2xl text-gray-400 hover:text-gray-700"
                aria-label="Close modal"
            >
                &times;
            </button>
        </div>

        <form
            id="editUpdatePostForm"
            method="POST"
            action=""
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div class="mb-4">
                <label
                    for="editUpdatePostTitle"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Post Title
                </label>

                <input
                    type="text"
                    id="editUpdatePostTitle"
                    name="title"
                    maxlength="255"
                    required
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-[#0e243a] focus:outline-none"
                >
            </div>

            <div class="mb-5">
                <label
                    for="editUpdatePostDescription"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Description
                </label>

                <textarea
                    id="editUpdatePostDescription"
                    name="description"
                    rows="6"
                    maxlength="5000"
                    required
                    class="w-full resize-y rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-[#0e243a] focus:outline-none"
                ></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeEditUpdatePostModal()"
                    class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#0e243a] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#183b5d]"
                >
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>
<?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/components/edit-posts-modal.blade.php ENDPATH**/ ?>