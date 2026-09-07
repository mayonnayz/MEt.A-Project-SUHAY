<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Applications</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
      rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
</style>


</head>

<body class="bg-gray-200">

<div class="flex">

@include('components.nav')


<div class="flex-1 p-8">

    @include('components.header', [
        'title' => 'Volunteer Management'
    ])


    {{-- ========================================= --}}
    {{-- NAVIGATION --}}
    {{-- ========================================= --}}

    <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-6 flex-wrap">

        <a href="/service-management"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">

            Volunteer Lists

        </a>


        <a href="/applications"
           class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">

            Applications

        </a>


        <a href="/assignments"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">

            Assignments

        </a>


        <a href="/events"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">

            Events

        </a>


        <a href="/track-activity"
           class="bg-[#f2c94c] px-6 py-2 rounded-full font-semibold">

            Track Activity

        </a>

    </div>



    {{-- ========================================= --}}
    {{-- MAIN CONTAINER --}}
    {{-- ========================================= --}}

    <div class="bg-[#0e243a] p-6 rounded-2xl">


        {{-- ========================================= --}}
        {{-- FILTERS --}}
        {{-- ========================================= --}}

        <div class="bg-gray-300 p-6 rounded-xl mb-4 flex justify-between flex-wrap gap-4">

            <div class="flex items-center gap-4">


                {{-- SKILL FILTER --}}

                <select
                    id="skillFilter"
                    class="p-2 border rounded-md"
                    onchange="applyFilters()">

                    <option value="">
                        All Skills
                    </option>


                    @foreach($skills as $skill)

                        <option value="{{ strtolower($skill) }}">

                            {{ $skill }}

                        </option>

                    @endforeach

                </select>



                {{-- STATUS FILTER --}}

                <select
                    id="statusFilter"
                    class="p-2 border rounded-md"
                    onchange="applyFilters()">

                    <option value="">
                        All Status
                    </option>

                    <option value="0">
                        Pending
                    </option>

                    <option value="1">
                        Approved
                    </option>

                    <option value="2">
                        Rejected
                    </option>

                    <option value="3">
                        Archived
                    </option>

                </select>

            </div>

        </div>



        {{-- ========================================= --}}
        {{-- TABLE --}}
        {{-- ========================================= --}}

        <div class="bg-gray-200 rounded-xl p-4 overflow-x-auto">

            <table class="w-full text-center border border-gray-400">


                <thead class="bg-gray-300">

                    <tr>

                        <th class="p-3 border">
                            #
                        </th>

                        <th class="p-3 border">
                            Name
                        </th>

                        <th class="p-3 border">
                            Status
                        </th>

                        <th class="p-3 border">
                            Action
                        </th>

                    </tr>

                </thead>



                <tbody>


                @forelse($applications as $index => $app)


                    <tr
                        class="bg-white border hover:bg-gray-100"

                        data-status="{{ $app['status'] }}"

                        data-skills="{{ strtolower($app['skills'] ?? '') }}"
                    >


                        {{-- NUMBER --}}

                        <td class="p-3 border row-number">

                        </td>



                        {{-- NAME --}}

                        <td class="p-3 border">

                            {{ $app['first_name'] }}
                            {{ $app['last_name'] }}

                        </td>



                        {{-- STATUS --}}

                        <td class="p-3 border">


                            @if($app['status'] == 0)

                                <span
                                    class="px-3 py-1 rounded-full
                                           bg-yellow-200 text-yellow-800 text-sm">

                                    Pending

                                </span>


                            @elseif($app['status'] == 1)

                                <span
                                    class="px-3 py-1 rounded-full
                                           bg-green-200 text-green-800 text-sm">

                                    Approved

                                </span>


                            @elseif($app['status'] == 2)

                                <span
                                    class="px-3 py-1 rounded-full
                                           bg-red-200 text-red-800 text-sm">

                                    Rejected

                                </span>


                            @elseif($app['status'] == 3)

                                <span
                                    class="px-3 py-1 rounded-full
                                           bg-gray-200 text-gray-800 text-sm">

                                    Archived

                                </span>

                            @endif


                        </td>



                        {{-- ACTIONS --}}

                        <td class="p-3 border space-x-2">


                            {{-- ================================= --}}
                            {{-- DATA FOR MODAL --}}
                            {{-- ================================= --}}

                            @php

                                $appData = [

                                    'id' =>
                                        $app['id'] ?? null,

                                    'volunteer_event_id' =>
                                        $app['volunteer_event_id'] ?? null,

                                    'account_id' =>
                                        $app['account_id'] ?? null,

                                    'application_date' =>
                                        $app['application_date'] ?? '',

                                    'first_name' =>
                                        $app['first_name'] ?? '',

                                    'last_name' =>
                                        $app['last_name'] ?? '',

                                    'email' =>
                                        $app['email'] ?? '',

                                    'address' =>
                                        $app['address'] ?? '',

                                    'contact_number' =>
                                        $app['contact_number'] ?? '',

                                    'birth_date' =>
                                        $app['birth_date'] ?? '',

                                    'skills' =>
                                        $app['skills'] ?? '',

                                    'remarks' =>
                                        $app['remarks'] ?? '',

                                    'status' =>
                                        $app['status'] ?? 0,

                                ];

                            @endphp



                            {{-- VIEW --}}

                            <button
                                type="button"

                                class="bg-blue-500
                                       hover:bg-blue-600
                                       px-4 py-1
                                       rounded-full
                                       text-white"

                                onclick="openAppModal(this)"

                                data-app='@json(
                                    $appData,
                                    JSON_HEX_APOS | JSON_HEX_QUOT
                                )'
                            >

                                View

                            </button>



                            {{-- ================================= --}}
                            {{-- PENDING --}}
                            {{-- ================================= --}}

                            @if($app['status'] == 0)


                                <button
                                    type="button"

                                    class="bg-green-500
                                           hover:bg-green-600
                                           px-4 py-1
                                           rounded-full
                                           text-white"

                                    onclick="updateStatus(
                                        {{ $app['id'] }},
                                        'approve'
                                    )">

                                    Approve

                                </button>



                                <button
                                    type="button"

                                    class="bg-red-500
                                           hover:bg-red-600
                                           px-4 py-1
                                           rounded-full
                                           text-white"

                                    onclick="updateStatus(
                                        {{ $app['id'] }},
                                        'reject'
                                    )">

                                    Reject

                                </button>


                            {{-- ================================= --}}
                            {{-- APPROVED --}}
                            {{-- ================================= --}}

                            @elseif($app['status'] == 1)


                                <button
                                    type="button"

                                    class="bg-gray-600
                                           hover:bg-gray-700
                                           px-4 py-1
                                           rounded-full
                                           text-white"

                                    onclick="archiveApplication(
                                        {{ $app['id'] }}
                                    )">

                                    Archive

                                </button>


                            {{-- ================================= --}}
                            {{-- REJECTED --}}
                            {{-- ================================= --}}

                            @elseif($app['status'] == 2)


                                <button
                                    type="button"

                                    class="bg-yellow-500
                                           hover:bg-yellow-600
                                           px-4 py-1
                                           rounded-full
                                           text-white"

                                    onclick="restoreApplication(
                                        {{ $app['id'] }}
                                    )">

                                    Restore

                                </button>



                                <button
                                    type="button"

                                    class="bg-gray-600
                                           hover:bg-gray-700
                                           px-4 py-1
                                           rounded-full
                                           text-white"

                                    onclick="archiveApplication(
                                        {{ $app['id'] }}
                                    )">

                                    Archive

                                </button>


                            {{-- ================================= --}}
                            {{-- ARCHIVED --}}
                            {{-- ================================= --}}

                            @elseif($app['status'] == 3)


                                <button
                                    type="button"

                                    class="bg-yellow-500
                                           hover:bg-yellow-600
                                           px-4 py-1
                                           rounded-full
                                           text-white"

                                    onclick="restoreApplication(
                                        {{ $app['id'] }}
                                    )">

                                    Restore

                                </button>

                            @endif


                        </td>


                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="4"
                            class="p-4 text-gray-500 text-center">

                            No applications found.

                        </td>

                    </tr>


                @endforelse



                {{-- NO FILTER RESULTS --}}

                <tr
                    id="noResultsRow"
                    class="hidden">

                    <td
                        colspan="4"
                        class="p-4 text-gray-500 text-center">

                        No applications found.

                    </td>

                </tr>


                </tbody>

            </table>

        </div>

    </div>

</div>
```

</div>

{{-- ========================================= --}}
{{-- MODALS --}}
{{-- ========================================= --}}

@include('components.application-modal')

@include('components.logout-modal')

{{-- ========================================= --}}
{{-- FILTER SCRIPT --}}
{{-- ========================================= --}}

<script>

function applyFilters()
{
    const status =
        document.getElementById('statusFilter').value;

    const skill =
        document
            .getElementById('skillFilter')
            .value
            .toLowerCase();


    const rows =
        document.querySelectorAll(
            "tbody tr[data-status]"
        );


    const noResultsRow =
        document.getElementById(
            "noResultsRow"
        );


    let visibleCount = 0;


    rows.forEach(row => {

        const rowStatus =
            row.dataset.status;


        const rowSkills =
            (
                row.dataset.skills || ''
            ).toLowerCase();


        const statusMatch =
            !status ||
            rowStatus === status;


        const skillMatch =
            !skill ||
            rowSkills.includes(skill);


        if (
            statusMatch &&
            skillMatch
        ) {

            row.classList.remove(
                "hidden"
            );

            visibleCount++;

        }
        else {

            row.classList.add(
                "hidden"
            );

        }

    });


    if (noResultsRow) {

        noResultsRow.classList.toggle(
            "hidden",
            visibleCount !== 0
        );

    }


    renumberRows();
}



document.addEventListener(
    "DOMContentLoaded",
    function () {

        applyFilters();

    }
);

</script>

{{-- ========================================= --}}
{{-- NUMBER ROWS --}}
{{-- ========================================= --}}

<script>

function renumberRows()
{

    let count = 1;


    document
        .querySelectorAll(
            "tbody tr[data-status]"
        )
        .forEach(row => {


            if (
                !row.classList.contains(
                    "hidden"
                )
            ) {

                const cell =
                    row.querySelector(
                        ".row-number"
                    );


                if (cell) {

                    cell.innerText =
                        count++;

                }

            }

        });

}

</script>

{{-- ========================================= --}}
{{-- APPLICATION MODAL --}}
{{-- ========================================= --}}

<script>

function openAppModal(button)
{

    const app =
        JSON.parse(
            button.dataset.app
        );


    const setText =
        (id, value) => {

            const el =
                document.getElementById(id);


            if (el) {

                el.innerText =
                    (
                        value !== null &&
                        value !== undefined &&
                        value !== ''
                    )
                    ? value
                    : '---';

            }

        };


    // Personal information

    setText(
        'first_name',
        app.first_name
    );


    setText(
        'last_name',
        app.last_name
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


    // Application information

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


    // Show modal

    const modal =
        document.getElementById(
            'appModal'
        );


    if (modal) {

        modal.classList.remove(
            'hidden'
        );

    }

}

</script>

{{-- ========================================= --}}
{{-- CLOSE MODAL --}}
{{-- ========================================= --}}

<script>

function closeModal()
{

    const modal =
        document.getElementById(
            'appModal'
        );


    const box =
        document.getElementById(
            'modalBox'
        );


    if (box) {

        box.classList.add(
            'scale-95'
        );

    }


    setTimeout(() => {

        if (modal) {

            modal.classList.add(
                'hidden'
            );

        }

    }, 150);

}

</script>

{{-- ========================================= --}}
{{-- UPDATE STATUS --}}
{{-- ========================================= --}}

<script>

function updateStatus(
    id,
    action
)
{

    let message = '';


    if (action === 'approve') {

        message =
            "Are you sure you want to APPROVE this application?";

    }
    else {

        message =
            "Are you sure you want to REJECT this application?";

    }


    if (!confirm(message)) {

        return;

    }


    let url = '';


    if (action === 'approve') {

        url =
            `/applications/approve/${id}`;

    }
    else {

        url =
            `/applications/reject/${id}`;

    }


    fetch(
        url,
        {
            method: 'PATCH',

            headers: {

                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}',

                'Content-Type':
                    'application/json',

                'Accept':
                    'application/json'

            }

        }
    )

    .then(async response => {

        const data =
            await response.json();


        if (!response.ok) {

            throw new Error(
                data.message ||
                'Failed to update application.'
            );

        }


        return data;

    })

    .then(data => {

        if (data.success) {

            alert(
                'Status updated successfully.'
            );

            location.reload();

        }
        else {

            alert(
                'Failed to update application.'
            );

        }

    })

    .catch(error => {

        console.error(
            'Error:',
            error
        );

        alert(
            error.message ||
            'Something went wrong.'
        );

    });

}

</script>

{{-- ========================================= --}}
{{-- ARCHIVE --}}
{{-- ========================================= --}}

<script>

function archiveApplication(id)
{

    if (
        !confirm(
            "Archive this application?"
        )
    ) {

        return;

    }


    fetch(
        `/applications/archive/${id}`,
        {

            method: 'PATCH',

            headers: {

                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}',

                'Content-Type':
                    'application/json',

                'Accept':
                    'application/json'

            }

        }
    )

    .then(async response => {

        const data =
            await response.json();


        if (!response.ok) {

            throw new Error(
                data.message ||
                'Failed to archive application.'
            );

        }


        return data;

    })

    .then(data => {

        if (data.success) {

            alert(
                'Application archived successfully.'
            );

            location.reload();

        }
        else {

            alert(
                'Failed to archive application.'
            );

        }

    })

    .catch(error => {

        console.error(
            'Archive error:',
            error
        );

        alert(
            error.message ||
            'Something went wrong.'
        );

    });

}

</script>

{{-- ========================================= --}}
{{-- RESTORE --}}
{{-- ========================================= --}}

<script>

function restoreApplication(id)
{

    if (
        !confirm(
            "Restore this application back to PENDING?"
        )
    ) {

        return;

    }


    fetch(
        `/applications/restore/${id}`,
        {

            method: 'PATCH',

            headers: {

                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}',

                'Content-Type':
                    'application/json',

                'Accept':
                    'application/json'

            }

        }
    )

    .then(async response => {

        const data =
            await response.json();


        if (!response.ok) {

            throw new Error(
                data.message ||
                'Failed to restore application.'
            );

        }


        return data;

    })

    .then(data => {

        if (data.success) {

            alert(
                'Application restored successfully.'
            );

            location.reload();

        }
        else {

            alert(
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

</script>

</body>
</html>
