// =============================
// LIVE SEARCH + SKILL FILTER
// =============================
const eventHistoryButton =
    document.getElementById('eventHistoryButton');

if (eventHistoryButton) {
    eventHistoryButton.classList.remove('hidden');
}
const searchInput = document.getElementById('searchInput');
const skillFilter = document.getElementById('skillFilter');

function filterVolunteers() {

    const search = searchInput.value;
    const skill = skillFilter.value;

    let params = new URLSearchParams();

    if (search) {
        params.append('search', search);
    }

    if (skill) {
        params.append('search_skill', skill);
    }

    fetch(`/service-management?${params.toString()}`)
        .then(response => response.text())
        .then(html => {

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newGrid = doc.getElementById('volunteerGrid');

            if (newGrid) {
                document.getElementById('volunteerGrid').innerHTML =
                    newGrid.innerHTML;
            }

        })
        .catch(error => {
            console.error('Filter error:', error);
        });
}


// =============================
// LIVE SEARCH
// =============================

searchInput.addEventListener('input', function () {
    filterVolunteers();
});


// =============================
// SKILL FILTER
// =============================

skillFilter.addEventListener('change', function () {
    filterVolunteers();
});


function openLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.remove('hidden');

    document
        .getElementById('logoutModal')
        .classList.add('flex');

}


function closeLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.add('hidden');

}

function openModal(app) {

    const modal = document.getElementById('appModal');

    const box = document.getElementById('modalBox');

    if (box) {
        box.classList.remove('scale-95');
    }

    const firstName =
        document.getElementById('first_name');

    const lastName =
        document.getElementById('last_name');

    const address =
        document.getElementById('address');

    const contact =
        document.getElementById('contact');

    const email =
        document.getElementById('email');

    const dob =
        document.getElementById('dob');

    const skills =
        document.getElementById('skills_text');

    const eventName =
        document.getElementById('event_name');

    const eventDate =
        document.getElementById('event_date');

    const applicationDate =
        document.getElementById('application_date');


    if (firstName) {
        firstName.innerText =
            app.first_name ?? '---';
    }

    if (lastName) {
        lastName.innerText =
            app.last_name ?? '---';
    }

    if (address) {
        address.innerText =
            app.address ?? '---';
    }

    if (contact) {
        contact.innerText =
            app.contact_number ?? '---';
    }

    if (email) {
        email.innerText =
            app.email ?? '---';
    }

    if (dob) {
        dob.innerText =
            app.birth_date ?? '---';
    }

    if (skills) {
        skills.innerText =
            app.skills ?? '---';
    }

    if (eventName) {
        eventName.innerText =
            app.event_name ?? '---';
    }

    if (eventDate) {
        eventDate.innerText =
            app.event_date ?? '---';
    }

    if (applicationDate) {
        applicationDate.innerText =
            app.application_date ?? '---';
    }


    loadEventHistory(app);


    if (modal) {

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }

}

function loadEventHistory(app) {

    const historyBody =
        document.getElementById('eventHistoryBody');

    if (!historyBody) {
        return;
    }

    historyBody.innerHTML = '';

   let history = app.event_history ?? [];

    history = history.filter(event => {
        return String(event.account_id) === String(app.account_id);
    });

    if (!history.length) {

        historyBody.innerHTML = `
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    No event history found.
                </td>
            </tr>
        `;

        return;
    }

    history.forEach(event => {

        let statusText = '---';

        let statusClass =
            'bg-gray-200 text-gray-800';

        if (String(event.status) === '0') {

            statusText = 'Pending';

            statusClass =
                'bg-yellow-100 text-yellow-800';

        } else if (String(event.status) === '1') {

            statusText = 'Approved';

            statusClass =
                'bg-green-100 text-green-800';

        } else if (String(event.status) === '2') {

            statusText = 'Rejected';

            statusClass =
                'bg-red-100 text-red-800';

        } else if (String(event.status) === '3') {

            statusText = 'Deactivated';

            statusClass =
                'bg-gray-200 text-gray-800';

        }

        historyBody.innerHTML += `
            <tr class="bg-white">

                <td class="p-3 border text-left font-medium">
                    ${event.event_name ?? '---'}
                </td>

                <td class="p-3 border">
                    ${event.application_date ?? '---'}
                </td>

                <td class="p-3 border">
                    ${event.event_date ?? '---'}
                </td>

                <td class="p-3 border">
                    <span class="inline-block px-3 py-1 rounded-full text-sm ${statusClass}">
                        ${statusText}
                    </span>
                </td>

            </tr>
        `;

    });

}


function closeModal() {

    const modal =
        document.getElementById('appModal');

    const box =
        document.getElementById('modalBox');


    if (box) {

        box.classList.add('scale-95');

    }


    setTimeout(() => {

        if (modal) {

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }

    }, 150);

}


function nextPage() {

    const page1 =
        document.getElementById('page1');

    const page2 =
        document.getElementById('page2');


    if (page1) {

        page1.classList.add('hidden');

    }

    if (page2) {

        page2.classList.remove('hidden');

    }

}


function prevPage() {

    const page1 =
        document.getElementById('page1');

    const page2 =
        document.getElementById('page2');


    if (page2) {

        page2.classList.add('hidden');

    }

    if (page1) {

        page1.classList.remove('hidden');

    }

}


function deactivateVolunteer(id) {

    if (!confirm('Are you sure you want to deactivate this volunteer?')) {
        return;
    }


    console.log('Sending account ID:', id);


    fetch(`/volunteers/deactivate/${id}`, {

        method: 'PATCH',

        headers: {

            'X-CSRF-TOKEN':
                '{{ csrf_token() }}',

            'Accept':
                'application/json'

        }

    })

    .then(async response => {

        console.log('STATUS:', response.status);

        const text =
            await response.text();

        console.log(
            'RAW RESPONSE:',
            text
        );


        let data;

        try {

            data = JSON.parse(text);

        } catch (error) {

            throw new Error(
                'Server returned an invalid response.'
            );

        }


        if (!response.ok) {

            throw new Error(
                data.message ||
                'Failed to deactivate volunteer.'
            );

        }


        return data;

    })

    .then(data => {

        console.log(
            'RESPONSE:',
            data
        );

        alert(
            data.message ||
            'Volunteer deactivated successfully.'
        );

        location.reload();

    })

    .catch(error => {

        console.error(
            'ERROR:',
            error
        );

        alert(
            error.message ||
            'Request failed. Please check the console.'
        );

    });

}