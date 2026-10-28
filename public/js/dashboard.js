
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
let imagePreviewUrl = null;

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
    if (!updatePostForm) return false;

    const hasTitle = updatePostTitle?.value.trim() !== '';
    const hasDescription = updatePostDescription?.value.trim() !== '';
    const hasImage = updatePostImage?.files?.length > 0;

    return Boolean(hasTitle || hasDescription || hasImage);
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

    removeUpdatePostImage();
    updateCharacterCounts();

    closeUnsavedPostModal();
    closeUpdatePostModal();
}

function saveUpdatePostDraft() {
    if (!updatePostForm || isSubmittingUpdatePost) return;

    // The backend must accept status 0 as a draft.
    updatePostStatus.value = '0';

    // A draft submission follows the same server endpoint.
    updatePostForm.requestSubmit();
}

/* =========================================
   MODAL DISMISSAL
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
    if (event.key === 'Escape') {
        if (unsavedPostModal?.classList.contains('flex')) {
            continueEditingUpdatePost();
        } else if (updatePostModal?.classList.contains('flex')) {
            requestCloseUpdatePostModal();
        } else {
            closeLogoutModal();
        }
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
   IMAGE UPLOAD AND PREVIEW
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

function removeUpdatePostImage() {
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('updatePostImagePreview');

    if (updatePostImage) {
        updatePostImage.value = '';
    }

    if (imagePreviewUrl) {
        URL.revokeObjectURL(imagePreviewUrl);
        imagePreviewUrl = null;
    }

    if (imagePreview) {
        imagePreview.removeAttribute('src');
    }

    imagePlaceholder?.classList.remove('hidden');
    imagePreviewContainer?.classList.add('hidden');

    clearImageError();
}

updatePostImage?.addEventListener('change', function () {
    clearImageError();

    const file = this.files?.[0];

    if (!file) return;

    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!allowedTypes.includes(file.type)) {
        this.value = '';
        showImageError('Please select a JPG, PNG, or WEBP image.');
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        this.value = '';
        showImageError('The image must not exceed 5 MB.');
        return;
    }

    if (imagePreviewUrl) {
        URL.revokeObjectURL(imagePreviewUrl);
    }

    imagePreviewUrl = URL.createObjectURL(file);

    const imagePreview = document.getElementById('updatePostImagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    if (imagePreview) {
        imagePreview.src = imagePreviewUrl;
    }

    imagePlaceholder?.classList.add('hidden');
    imagePreviewContainer?.classList.remove('hidden');
});

/* =========================================
   FORM SUBMISSION
========================================= */

updatePostForm?.addEventListener('submit', function (event) {
    if (isSubmittingUpdatePost) {
        event.preventDefault();
        return;
    }

    isSubmittingUpdatePost = true;

    const publishButton = document.getElementById('publishUpdateButton');

    if (publishButton) {
        publishButton.disabled = true;
        publishButton.textContent =
            updatePostStatus?.value === '0'
                ? 'Saving draft...'
                : 'Publishing...';

        publishButton.classList.add('opacity-60', 'cursor-not-allowed');
    }
});

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
