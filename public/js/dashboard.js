
/* =========================================
   NGO UPDATE MODAL
========================================= */

const updatePostModal = document.getElementById('updatePostModal');
const unsavedPostModal = document.getElementById('unsavedPostModal');
const updatePostForm = document.getElementById('updatePostForm');

const updatePostTitle = document.getElementById('updatePostTitle');
const updatePostDescription = document.getElementById('updatePostDescription');
const updatePostStatus = document.getElementById('updatePostStatus');
const updatePostImage = document.getElementById('updatePostImage');

let isSubmittingUpdatePost = false;


/* =========================================
   UPDATE POST MODAL CONTROLS
========================================= */

function openUpdatePostModal() {
    if (!updatePostModal) return;

    updatePostModal.classList.remove('hidden');
    updatePostModal.classList.add('flex');

    document.body.classList.add('overflow-hidden');

    updatePostTitle?.focus();
}

function closeUpdatePostModal() {
    if (!updatePostModal) return;

    updatePostModal.classList.add('hidden');
    updatePostModal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}

function openUnsavedPostModal() {
    if (!unsavedPostModal) return;

    unsavedPostModal.classList.remove('hidden');
    unsavedPostModal.classList.add('flex');
}

function closeUnsavedPostModal() {
    if (!unsavedPostModal) return;

    unsavedPostModal.classList.add('hidden');
    unsavedPostModal.classList.remove('flex');
}

function hasUnsavedUpdatePost() {
    const hasTitle = Boolean(updatePostTitle?.value.trim());
    const hasDescription = Boolean(updatePostDescription?.value.trim());
    const hasImages = selectedUpdatePostImages.length > 0;

    return hasTitle || hasDescription || hasImages;
}

function requestCloseUpdatePostModal() {
    if (isSubmittingUpdatePost) return;

    if (hasUnsavedUpdatePost()) {
        openUnsavedPostModal();
        return;
    }

    closeUpdatePostModal();
}

function continueEditingUpdatePost() {
    closeUnsavedPostModal();
    updatePostTitle?.focus();
}

function discardUpdatePost() {
    if (!updatePostForm) return;

    updatePostForm.reset();

    if (updatePostStatus) {
        updatePostStatus.value = '1';
    }

    clearUpdatePostImages();
    updateCharacterCounts();

    isSubmittingUpdatePost = false;

    const publishButton = document.getElementById('publishUpdateButton');

    if (publishButton) {
        publishButton.disabled = false;
        publishButton.textContent = 'Publish';
        publishButton.classList.remove(
            'opacity-60',
            'cursor-not-allowed'
        );
    }

    closeUnsavedPostModal();
    closeUpdatePostModal();
}

function saveUpdatePostDraft() {
    if (!updatePostForm || isSubmittingUpdatePost) return;

    if (updatePostStatus) {
        updatePostStatus.value = '0';
    }

    updatePostForm.requestSubmit();
}


/* =========================================
   UPDATE POST FORM SUBMISSION
========================================= */

updatePostForm?.addEventListener('submit', function (event) {
    if (isSubmittingUpdatePost) {
        event.preventDefault();
        return;
    }

    // Ensure the actual file input contains every selected image.
    syncUpdatePostImages();

    console.log('NGO update form submission:', {
        selectedImages: selectedUpdatePostImages.length,
        inputFiles: updatePostImage?.files.length ?? 0,
        formDataImages: new FormData(updatePostForm)
            .getAll('images[]')
            .filter(item => item instanceof File && item.size > 0)
            .map(file => file.name)
    });

    isSubmittingUpdatePost = true;

    const publishButton = document.getElementById('publishUpdateButton');

    if (publishButton) {
        publishButton.disabled = true;
        publishButton.textContent = 'Submitting...';

        publishButton.classList.add(
            'opacity-60',
            'cursor-not-allowed'
        );
    }
});


/* =========================================
   EDIT UPDATE POST MODAL
========================================= */

function openEditUpdatePostModal(id, title, description) {
    const modal = document.getElementById('editUpdatePostModal');
    const form = document.getElementById('editUpdatePostForm');

    if (!modal || !form) return;

    document.getElementById('editUpdatePostTitle').value = title;
    document.getElementById('editUpdatePostDescription').value = description;

    form.action = `/update-posts/${id}`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.classList.add('overflow-hidden');
}

function closeEditUpdatePostModal() {
    const modal = document.getElementById('editUpdatePostModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}


/* =========================================
   MODAL BACKDROP AND ESCAPE KEY
========================================= */

updatePostModal?.addEventListener('click', function (event) {
    if (event.target === updatePostModal) {
        requestCloseUpdatePostModal();
    }
});

unsavedPostModal?.addEventListener('click', function (event) {
    if (event.target === unsavedPostModal) {
        closeUnsavedPostModal();
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;

    if (unsavedPostModal?.classList.contains('flex')) {
        continueEditingUpdatePost();
    } else if (updatePostModal?.classList.contains('flex')) {
        requestCloseUpdatePostModal();
    } else {
        closeLogoutModal();
    }
});


/* =========================================
   CHARACTER COUNTERS
========================================= */

function updateCharacterCounts() {
    const titleCount = document.getElementById('titleCount');
    const descriptionCount = document.getElementById('descriptionCount');

    if (titleCount && updatePostTitle) {
        titleCount.textContent = updatePostTitle.value.length;
    }

    if (descriptionCount && updatePostDescription) {
        descriptionCount.textContent = updatePostDescription.value.length;
    }
}

updatePostTitle?.addEventListener('input', updateCharacterCounts);
updatePostDescription?.addEventListener('input', updateCharacterCounts);

updateCharacterCounts();


/* =========================================
   MULTIPLE IMAGE UPLOAD AND PREVIEW
========================================= */

const imagePreviewContainer = document.getElementById('imagePreviewContainer');
const imageCount = document.getElementById('imageCount');
const clearImagesButton = document.getElementById('clearUpdatePostImages');

const MAX_POST_IMAGES = 10;
const MAX_POST_IMAGE_SIZE = 5 * 1024 * 1024;

let selectedUpdatePostImages = [];


/* =========================================
   IMAGE ERROR MESSAGES
========================================= */

function showImageError(message) {
    const imageError = document.getElementById('imageError');

    if (!imageError) return;

    imageError.textContent = message;
    imageError.classList.remove('hidden');
}

function clearImageError() {
    const imageError = document.getElementById('imageError');

    if (!imageError) return;

    imageError.textContent = '';
    imageError.classList.add('hidden');
}


/* =========================================
   SYNCHRONIZE SELECTED FILES
========================================= */

function syncUpdatePostImages() {
    if (!updatePostImage) return;

    const dataTransfer = new DataTransfer();

    selectedUpdatePostImages.forEach(file => {
        dataTransfer.items.add(file);
    });

    updatePostImage.files = dataTransfer.files;
}


/* =========================================
   RENDER IMAGE PREVIEWS
========================================= */

function renderUpdatePostImagePreviews() {
    if (!imagePreviewContainer) return;

    // Release existing preview URLs to avoid memory leaks.
    imagePreviewContainer
        .querySelectorAll('img[data-preview-url]')
        .forEach(img => {
            URL.revokeObjectURL(img.dataset.previewUrl);
        });

    imagePreviewContainer.innerHTML = '';

    selectedUpdatePostImages.forEach((file, index) => {
        const wrapper = document.createElement('div');

        wrapper.className =
            'relative overflow-hidden rounded-xl border border-gray-200 bg-gray-50';

        const preview = document.createElement('img');
        const previewUrl = URL.createObjectURL(file);

        preview.src = previewUrl;
        preview.dataset.previewUrl = previewUrl;
        preview.alt = `Selected post image ${index + 1}`;

        preview.className = 'h-36 w-full object-cover';

        const removeButton = document.createElement('button');

        removeButton.type = 'button';
        removeButton.textContent = '×';

        removeButton.setAttribute(
            'aria-label',
            `Remove image ${index + 1}`
        );

        removeButton.className =
            'absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-white text-lg font-bold text-red-600 shadow hover:bg-red-50';

        removeButton.addEventListener('click', function () {
            selectedUpdatePostImages.splice(index, 1);

            syncUpdatePostImages();
            renderUpdatePostImagePreviews();
            clearImageError();
        });

        const filename = document.createElement('p');

        filename.className = 'truncate px-2 py-2 text-xs text-gray-600';
        filename.textContent = file.name;

        wrapper.append(preview, removeButton, filename);
        imagePreviewContainer.appendChild(wrapper);
    });

    if (imageCount) {
        imageCount.textContent =
            `${selectedUpdatePostImages.length} of ${MAX_POST_IMAGES} images selected`;
    }

    clearImagesButton?.classList.toggle(
        'hidden',
        selectedUpdatePostImages.length === 0
    );
}


/* =========================================
   IMAGE INPUT CHANGE HANDLER
========================================= */

updatePostImage?.addEventListener('change', function () {
    clearImageError();

    const incomingFiles = Array.from(this.files || []);

    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    const existingFiles = new Set(
        selectedUpdatePostImages.map(file =>
            `${file.name}|${file.size}|${file.lastModified}`
        )
    );

    const validFiles = [];
    let errorMessage = '';

    for (const file of incomingFiles) {
        if (!allowedTypes.includes(file.type)) {
            errorMessage =
                'Only JPG, PNG, and WEBP images are allowed.';
            continue;
        }

        if (file.size > MAX_POST_IMAGE_SIZE) {
            errorMessage =
                `${file.name} exceeds the 5 MB limit.`;
            continue;
        }

        const fileKey =
            `${file.name}|${file.size}|${file.lastModified}`;

        if (existingFiles.has(fileKey)) {
            continue;
        }

        existingFiles.add(fileKey);
        validFiles.push(file);
    }

    const remainingSlots =
        MAX_POST_IMAGES - selectedUpdatePostImages.length;

    if (validFiles.length > remainingSlots) {
        errorMessage =
            'You can upload a maximum of 10 images per post.';
    }

    selectedUpdatePostImages.push(
        ...validFiles.slice(0, remainingSlots)
    );

    // Preserve all selected files in the actual form input.
    syncUpdatePostImages();
    renderUpdatePostImagePreviews();

    if (errorMessage) {
        showImageError(errorMessage);
    }

    // Do not clear this.value here.
});


/* =========================================
   CLEAR ALL SELECTED IMAGES
========================================= */

function clearUpdatePostImages() {
    selectedUpdatePostImages = [];

    if (updatePostImage) {
        updatePostImage.value = '';
    }

    syncUpdatePostImages();
    renderUpdatePostImagePreviews();
    clearImageError();
}

// Compatibility with existing buttons or scripts.
function removeUpdatePostImage() {
    clearUpdatePostImages();
}


/* =========================================
   UPDATE POSTS FILTER AND EXPANSION
========================================= */

let currentUpdateFilter = 'all';
let updatePostsExpanded = false;

function filterUpdatePosts(status, button) {
    currentUpdateFilter = status;
    updatePostsExpanded = false;

    document.querySelectorAll('.update-filter-btn').forEach(btn => {
        const active = btn.dataset.filter === status;

        btn.classList.toggle('bg-[#0e243a]', active);
        btn.classList.toggle('text-white', active);

        btn.classList.toggle('border', !active);
        btn.classList.toggle('border-gray-200', !active);
        btn.classList.toggle('bg-white', !active);
        btn.classList.toggle('text-gray-600', !active);
    });

    renderUpdatePosts();
}

function renderUpdatePosts() {
    const posts = Array.from(
        document.querySelectorAll('.update-post-item')
    );

    const toggleContainer = document.getElementById(
        'updatePostsToggleContainer'
    );

    const toggleButton = document.getElementById(
        'toggleUpdatePostsBtn'
    );

    const toggleText = document.getElementById(
        'toggleUpdatePostsText'
    );

    const toggleIcon = document.getElementById(
        'toggleUpdatePostsIcon'
    );

    const emptyMessage = document.getElementById(
        'filteredUpdatePostsEmpty'
    );

    const filteredPosts = posts.filter(post => {
        return currentUpdateFilter === 'all' ||
            post.dataset.status === currentUpdateFilter;
    });

    posts.forEach(post => post.classList.add('hidden'));

    const visiblePosts = updatePostsExpanded
        ? filteredPosts
        : filteredPosts.slice(0, 1);

    visiblePosts.forEach(post => post.classList.remove('hidden'));

    const hasMore = filteredPosts.length > 1;

    toggleContainer?.classList.toggle('hidden', !hasMore);

    if (toggleButton) {
        toggleButton.setAttribute(
            'aria-expanded',
            String(updatePostsExpanded)
        );
    }

    if (toggleText) {
        toggleText.textContent = updatePostsExpanded
            ? 'See Less'
            : 'See More Updates';
    }

    if (toggleIcon) {
        toggleIcon.classList.toggle(
            'rotate-180',
            updatePostsExpanded
        );
    }

    if (emptyMessage) {
        emptyMessage.classList.toggle(
            'hidden',
            filteredPosts.length !== 0
        );
    }
}

function toggleUpdatePosts() {
    updatePostsExpanded = !updatePostsExpanded;
    renderUpdatePosts();
}


/* =========================================
   LOGOUT MODAL
========================================= */

function openLogoutModal() {
    const modal = document.getElementById('logoutModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


/* =========================================
   INITIALIZE
========================================= */

document.addEventListener('DOMContentLoaded', function () {
    renderUpdatePosts();
    updateCharacterCounts();
    renderUpdatePostImagePreviews();
});
