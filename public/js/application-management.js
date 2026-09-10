const csrfToken = document
.querySelector('meta[name="csrf-token"]')
.getAttribute('content');

function applyFilters() {
const status = document.getElementById('statusFilter').value;

const skill = document
    .getElementById('skillFilter')
    .value
    .toLowerCase();

const event = document
    .getElementById('eventFilter')
    .value
    .toLowerCase();

const rows = document.querySelectorAll('tbody tr[data-status]');
const noResultsRow = document.getElementById('noResultsRow');

let visibleCount = 0;

rows.forEach(row => {
    const rowStatus = String(row.dataset.status);

    const rowSkills =
        (row.dataset.skills || '').toLowerCase();

    const rowEvent =
        (row.dataset.event || '').toLowerCase();

    const statusMatch =
        !status || rowStatus === status;

    const skillMatch =
        !skill || rowSkills.includes(skill);

    const eventMatch =
        !event || rowEvent === event;

    if (
        statusMatch &&
        skillMatch &&
        eventMatch
    ) {
        row.classList.remove('hidden');
        visibleCount++;
    } else {
        row.classList.add('hidden');
    }
});

if (noResultsRow) {
    noResultsRow.classList.toggle(
        'hidden',
        visibleCount !== 0
    );
}

renumberRows();


}

function renumberRows() {
let count = 1;

document
    .querySelectorAll('tbody tr[data-status]')
    .forEach(row => {

        if (!row.classList.contains('hidden')) {

            const cell =
                row.querySelector('.row-number');

            if (cell) {
                cell.innerText = count++;
            }
        }
    });


}

document.addEventListener(
'DOMContentLoaded',
function () {
applyFilters();
}
);

function openAppModal(row) {
let app;

try {
    app = JSON.parse(row.dataset.app);
} catch (error) {
    console.error(
        'Invalid application data:',
        error
    );

    return;
}

const setText = (id, value) => {
    const element =
        document.getElementById(id);

    if (element) {
        element.innerText =
            value !== null &&
            value !== undefined &&
            value !== ''
                ? value
                : '---';
    }
};

setText(
    'first_name',
    app.first_name
);

setText(
    'last_name',
    app.last_name
);

setText(
    'event_name',
    app.event_name
);

setText(
    'address',
    app.address
);

setText(
    'contact',
    app.contact_number
);

setText(
    'email',
    app.email
);

setText(
    'dob',
    app.birth_date
);

setText(
    'application_date',
    app.application_date
);

setText(
    'skills_text',
    app.skills
);

setText(
    'remarks_text',
    app.remarks
);

const eventHistoryButton =
    document.getElementById(
        'eventHistoryButton'
    );

if (eventHistoryButton) {
    eventHistoryButton.classList.add(
        'hidden'
    );
}

const page1 =
    document.getElementById('page1');

const page2 =
    document.getElementById('page2');

if (page1) {
    page1.classList.remove('hidden');
}

if (page2) {
    page2.classList.add('hidden');
}

createModalActions(app);

const modal =
    document.getElementById('appModal');

const box =
    document.getElementById('modalBox');

if (modal) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

if (box) {
    box.classList.remove('scale-95');
    box.classList.add('scale-100');
}


}

function createModalActions(app) {
const actions =
document.getElementById('modalActions');


if (!actions) {
    return;
}

actions.innerHTML = '';

if (app.status == 0) {

    actions.innerHTML = `
        <button
            type="button"
            onclick="updateStatus(${app.id}, 'approve')"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full"
        >
            Approve
        </button>

        <button
            type="button"
            onclick="updateStatus(${app.id}, 'reject')"
            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-full"
        >
            Reject
        </button>
    `;

} else if (app.status == 1) {

    actions.innerHTML = `
        <button
            type="button"
            onclick="archiveApplication(${app.id})"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-full"
        >
            Deactivate
        </button>
    `;

} else if (app.status == 2) {

    actions.innerHTML = `
        <button
            type="button"
            onclick="restoreApplication(${app.id})"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-full"
        >
            Restore
        </button>

        <button
            type="button"
            onclick="archiveApplication(${app.id})"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-full"
        >
            Deactivate
        </button>
    `;

} else if (app.status == 3) {

    actions.innerHTML = `
        <button
            type="button"
            onclick="restoreApplication(${app.id})"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-full"
        >
            Restore
        </button>
    `;
}


}

function closeModal() {
const modal =
document.getElementById('appModal');


const box =
    document.getElementById('modalBox');

if (box) {
    box.classList.remove('scale-100');
    box.classList.add('scale-95');
}

setTimeout(() => {

    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

}, 150);


}

async function sendRequest(url) {
const response = await fetch(
url,
{
method: 'PATCH',


        credentials: 'same-origin',

        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },

        body: JSON.stringify({})
    }
);

const contentType =
    response.headers.get(
        'content-type'
    ) || '';

let data;

if (
    contentType.includes(
        'application/json'
    )
) {

    data = await response.json();

} else {

    const text =
        await response.text();

    throw new Error(
        text ||
        `Request failed with status ${response.status}`
    );
}

if (!response.ok) {
    throw new Error(
        data.message ||
        'Request failed.'
    );
}

return data;

}

function updateStatus(id, action) {
const message =
action === 'approve'
? 'Are you sure you want to APPROVE this application?'
: 'Are you sure you want to REJECT this application?';


if (!confirm(message)) {
    return;
}

const url =
    action === 'approve'
        ? `/applications/approve/${id}`
        : `/applications/reject/${id}`;

sendRequest(url)

    .then(data => {

        if (data.success) {

            alert(
                'Status updated successfully.'
            );

            closeModal();

            window.location.reload();

        } else {

            alert(
                data.message ||
                'Failed to update application.'
            );
        }
    })

    .catch(error => {

        console.error(
            'Status update error:',
            error
        );

        alert(
            error.message ||
            'Something went wrong.'
        );
    });


}

function archiveApplication(id) {
if (
!confirm(
'Deactivate this application?'
)
) {
return;
}


sendRequest(
    `/applications/archive/${id}`
)

    .then(data => {

        if (data.success) {

            alert(
                'Application deactivated successfully.'
            );

            closeModal();

            window.location.reload();

        } else {

            alert(
                data.message ||
                'Failed to deactivate application.'
            );
        }
    })

    .catch(error => {

        console.error(
            'Deactivate error:',
            error
        );

        alert(
            error.message ||
            'Something went wrong.'
        );
    });


}

function restoreApplication(id) {
if (
!confirm(
'Restore this application back to PENDING?'
)
) {
return;
}


sendRequest(
    `/applications/restore/${id}`
)

    .then(data => {

        if (data.success) {

            alert(
                'Application restored successfully.'
            );

            closeModal();

            window.location.reload();

        } else {

            alert(
                data.message ||
                'Failed to restore application.'
            );
        }
    })

    .catch(error => {

        console.error(
            'Restore error:',
            error
        );

        alert(
            error.message ||
            'Something went wrong.'
        );
    });


}
