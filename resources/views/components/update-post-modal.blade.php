
{{-- CREATE NGO UPDATE MODAL --}}
<div id="updatePostModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">

    <div class="flex max-h-[92vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
        role="dialog" aria-modal="true" aria-labelledby="updatePostModalTitle">

        {{-- HEADER --}}
        <div class="flex items-start justify-between border-b border-gray-100 px-6 py-5">
            <div>
                <h2 id="updatePostModalTitle" class="text-xl font-bold text-[#0e243a]">
                    Create NGO Update
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Share news, announcements, and stories with your community.
                </p>
            </div>

            <button type="button" onclick="requestCloseUpdatePostModal()"
                class="rounded-lg px-3 py-1 text-2xl text-gray-400 hover:bg-gray-100"
                aria-label="Close composer">
                &times;
            </button>
        </div>

        {{-- FORM --}}
        <form id="updatePostForm" method="POST"
            action="{{ url('/update-posts') }}"
            enctype="multipart/form-data"
            class="flex min-h-0 flex-1 flex-col">

            @csrf
            <input type="hidden" id="updatePostStatus" name="status" value="1">

            <div class="space-y-5 overflow-y-auto p-6">

                {{-- POST TITLE --}}
                <div>
                    <label for="updatePostTitle"
                        class="mb-2 block text-sm font-semibold text-[#0e243a]">
                        Post title <span class="text-red-500">*</span>
                    </label>

                    <input id="updatePostTitle" name="title" type="text"
                        maxlength="255" required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#d4a017] focus:ring-2 focus:ring-yellow-100"
                        placeholder="Give your update a title">

                    <p class="mt-1 text-right text-xs text-gray-400">
                        <span id="titleCount">0</span>/255
                    </p>
                </div>

                {{-- POST DESCRIPTION --}}
                <div>
                    <label for="updatePostDescription"
                        class="mb-2 block text-sm font-semibold text-[#0e243a]">
                        What's happening? <span class="text-red-500">*</span>
                    </label>

                    <textarea id="updatePostDescription" name="description"
                        rows="5" maxlength="5000" required
                        class="w-full resize-y rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#d4a017] focus:ring-2 focus:ring-yellow-100"
                        placeholder="Write your announcement, activity, or community story..."></textarea>

                    <p class="mt-1 text-right text-xs text-gray-400">
                        <span id="descriptionCount">0</span>/5000
                    </p>
                </div>

                {{-- MULTIPLE IMAGE UPLOAD --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#0e243a]">
                        Add photos
                        <span class="font-normal text-gray-400">
                            (optional, maximum 10)
                        </span>
                    </label>

                    <input
                        id="updatePostImage"
                        name="images[]"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        class="hidden">

                    <div id="imageUploadArea"
                        class="rounded-xl border-2 border-dashed border-gray-300 p-5 text-center transition hover:border-[#d4a017]">

                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50">
                            <svg class="h-6 w-6 text-[#b38720]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m21 15-5-5L5 21"/>
                            </svg>
                        </div>

                        <p class="text-sm font-semibold text-[#0e243a]">
                            Add photos to your post
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            JPG, PNG, or WEBP · Maximum 5 MB per image
                        </p>

                        <button type="button"
                            onclick="document.getElementById('updatePostImage').click()"
                            class="mt-4 rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-[#0e243a] hover:bg-gray-50">
                            Choose images
                        </button>
                    </div>

                    {{-- IMAGE COUNT AND REMOVE ALL --}}
                    <div class="mt-4 flex items-center justify-between gap-3">
                        <p id="imageCount" class="text-xs text-gray-500">
                            0 of 10 images selected
                        </p>

                        <button type="button"
                            id="clearUpdatePostImages"
                            onclick="clearUpdatePostImages()"
                            class="hidden text-xs font-semibold text-red-600 hover:underline">
                            Remove all
                        </button>
                    </div>

                    {{-- IMAGE PREVIEWS --}}
                    <div id="imagePreviewContainer"
                        class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    </div>

                    {{-- IMAGE VALIDATION ERROR --}}
                    <p id="imageError"
                        class="mt-2 hidden text-sm text-red-600"
                        role="alert"></p>
                </div>

                <p class="text-xs text-gray-400">
                    Posts marked for publishing will be visible wherever
                    published NGO updates are displayed.
                </p>
            </div>

            {{-- FOOTER --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 bg-white px-6 py-4 sm:flex-row sm:justify-between">

                <button type="button"
                    onclick="requestCloseUpdatePostModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Cancel
                </button>

                <button type="submit" id="publishUpdateButton"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0e243a] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#183b5d]">
                    Publish
                </button>
            </div>
        </form>
    </div>
</div>

{{-- UNSAVED CHANGES WARNING --}}
<div id="unsavedPostModal"
    class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 p-4">

    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
        role="alertdialog" aria-modal="true" aria-labelledby="unsavedPostTitle">

        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-6 w-6 text-amber-600" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                    stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"/>
            </svg>
        </div>

        <h3 id="unsavedPostTitle" class="text-lg font-bold text-[#0e243a]">
            Leave without publishing?
        </h3>

        <p class="mt-2 text-sm leading-6 text-gray-500">
            You have an unfinished post. Would you like to save it as a draft before leaving?
        </p>

        <div class="mt-6 flex flex-col gap-3">
            <button type="button" onclick="saveUpdatePostDraft()"
                class="w-full rounded-xl bg-[#0e243a] px-4 py-3 text-sm font-semibold text-white hover:bg-[#183b5d]">
                Save as draft
            </button>

            <button type="button" onclick="discardUpdatePost()"
                class="w-full rounded-xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50">
                Discard post
            </button>

            <button type="button" onclick="continueEditingUpdatePost()"
                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Continue editing
            </button>
        </div>
    </div>
</div>
