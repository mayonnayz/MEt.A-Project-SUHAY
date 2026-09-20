const form = document.getElementById('volunteerForm');
const backBtn = document.getElementById('backBtn');

function isFormDirty() {

    const inputs = form.querySelectorAll(
        'input, textarea, select'
    );

    for (let input of inputs) {

        if (
            input.type === 'checkbox' &&
            input.checked
        ) {
            return true;
        }

        if (
            input.type !== 'checkbox' &&
            input.type !== 'hidden' &&
            input.value.trim() !== ''
        ) {
            return true;
        }
    }

    return false;
}


backBtn.addEventListener('click', function (e) {

    if (isFormDirty()) {

        const confirmLeave = confirm(
            'Are you sure you want to go back? All entered data will be lost.'
        );

        if (!confirmLeave) {
            e.preventDefault();
        }
    }

});