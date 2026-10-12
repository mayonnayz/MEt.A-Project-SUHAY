
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
          rel="stylesheet">

    <title>Donations</title>

    <style>
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }

        .active-nav {
            background-color: #d97706;
            color: white;
            border-radius: 9999px;
        }

        .donation-row {
            cursor: pointer;
        }

        .donation-row:focus-visible {
            outline: 2px solid #d97706;
            outline-offset: -2px;
        }
    </style>
</head>

<body class="bg-slate-100" style="font-family: 'Poppins', sans-serif;">

<div class="flex min-h-screen">

    @include('components.nav')

    <main class="flex-1 p-8">

        @include('components.header', ['title' => 'Donation Management'])

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">

            <!-- FILTERS -->
            <div class="p-6 bg-slate-50 border-b border-slate-200">

                <div class="flex flex-wrap gap-4 items-center">

                    <div class="relative flex-1 min-w-[300px]">
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search Donors..."
                            class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none"
                        >

                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400"></i>
                    </div>

                    <select
                        id="statusFilter"
                        class="px-4 py-2 rounded-full border border-slate-300 bg-white text-slate-600 outline-none"
                    >
                        <option value="all">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Rejected">Rejected</option>
                    </select>

                    <select
                        id="typeFilter"
                        class="px-4 py-2 rounded-full border border-slate-300 bg-white text-slate-600 outline-none"
                    >
                        <option value="all">All Types</option>
                        <option value="MONETARY">Monetary</option>
                        <option value="ONLINE_MONETARY">Online Monetary</option>
                        <option value="CONSUMABLE">Consumable</option>
                        <option value="REUSABLE">Reusable</option>
                    </select>

                    <input
                        type="date"
                        id="dateFilter"
                        class="px-4 py-2 rounded-full border border-slate-300 text-slate-600 outline-none"
                    >

                </div>

                <div class="mt-4 text-slate-500 text-sm font-semibold">
                    Total Number of Donations:
                    <span id="totalDonations" class="text-slate-800 text-lg font-bold">
                        {{ $total_donations }}
                    </span>
                </div>

                <p class="text-xs text-slate-400 mt-2">
                    Click any donation row to view its details.
                </p>

            </div>

            <!-- DONATION TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-white text-slate-800 uppercase text-sm border-b">
                        <tr>
                            <th class="px-6 py-4 font-bold">#</th>
                            <th class="px-6 py-4 font-bold">Name</th>
                            <th class="px-6 py-4 font-bold">Donation Type</th>
                            <th class="px-6 py-4 font-bold">Description</th>
                            <th class="px-6 py-4 font-bold">Amount</th>
                            <th class="px-6 py-4 font-bold">Unit</th>
                            <th class="px-6 py-4 font-bold">Date</th>
                            <th class="px-6 py-4 font-bold">Status</th>
                        </tr>
                    </thead>

                    <tbody id="donationTableBody" class="divide-y divide-slate-100">

                        @forelse ($donations as $index => $donation)

                            <tr
                                class="donation-row hover:bg-amber-50 transition-colors focus-visible:bg-amber-50"
                                tabindex="0"
                                role="button"
                                aria-label="View donation from {{ $donation->donor_name ?? 'Unknown' }}"

                                data-id="{{ $donation->id }}"
                                data-name="{{ $donation->donor_name ?? 'Unknown' }}"
                                data-type="{{ strtoupper($donation->type ?? '') }}"
                                data-status="{{ $donation->status ?? 'Pending' }}"
                                data-date="{{ $donation->date ? \Carbon\Carbon::parse($donation->date)->format('Y-m-d') : '' }}"
                                data-description="{{ $donation->description ?? 'N/A' }}"
                                data-amount="{{ $donation->amount ?? '' }}"
                                data-unit="{{ $donation->unit ?? 'N/A' }}"
                                data-source="{{ $donation->source ?? 'N/A' }}"
                            >

                                <td class="px-6 py-4 text-slate-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $donation->donor_name ?? 'Unknown' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ str_replace('_', ' ', $donation->type ?? 'N/A') }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $donation->description ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    @if(in_array(strtoupper($donation->type ?? ''), ['MONETARY', 'ONLINE_MONETARY']))
                                        @if(!is_null($donation->amount))
                                            ₱{{ number_format((float) $donation->amount, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    @else
                                        {{ !is_null($donation->amount) ? number_format((float) $donation->amount, 2) : 'N/A' }}
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $donation->unit ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $donation->date ? \Carbon\Carbon::parse($donation->date)->format('Y-m-d') : 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $status = $donation->status ?? 'Unknown';

                                        $statusClasses = match ($status) {
                                            'Pending' => 'bg-yellow-100 text-yellow-700',
                                            'Confirmed' => 'bg-green-100 text-green-700',
                                            'Rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <span class="donation-status px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses }}">
                                        {{ $status }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-slate-500">
                                    No donations found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

@include('components.donation-view-modal')
@include('components.logout-modal')

<script>
    let currentDonationId = null;
    let currentDonationRow = null;

    // =========================================================
    // OPEN MODAL
    // =========================================================

    function openModal(row) {
        const modal = document.getElementById('donationModal');

        if (!modal || !row) {
            return;
        }

        currentDonationId = row.dataset.id;
        currentDonationRow = row;

        document.getElementById('modalName').textContent =
            'Donor: ' + (row.dataset.name || 'Unknown');

        document.getElementById('modalDate').textContent =
            'Date: ' + (row.dataset.date || 'N/A');

        document.getElementById('modalType').textContent =
            (row.dataset.type || 'N/A').replaceAll('_', ' ');

        document.getElementById('modalDescription').textContent =
            row.dataset.description || 'N/A';

        const amount = row.dataset.amount;
        const type = (row.dataset.type || '').toUpperCase();

        if (amount !== '') {
            const formattedAmount = Number(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            document.getElementById('modalAmount').textContent =
                ['MONETARY', 'ONLINE_MONETARY'].includes(type)
                    ? '₱' + formattedAmount
                    : formattedAmount;
        } else {
            document.getElementById('modalAmount').textContent = 'N/A';
        }

        document.getElementById('modalUnit').textContent =
            row.dataset.unit || 'N/A';

        document.getElementById('modalSource').textContent =
            row.dataset.source || 'N/A';

        document.getElementById('modalStatus').textContent =
            row.dataset.status || 'Unknown';

        const message = document.getElementById('donationActionMessage');

        if (message) {
            message.textContent = '';
            message.className = 'hidden mt-3 text-center text-sm';
        }

        const confirmButton = document.getElementById('confirmDonationButton');

        if (confirmButton) {
            const alreadyProcessed = ['Confirmed', 'Rejected'].includes(
                row.dataset.status
            );

            confirmButton.disabled = alreadyProcessed;
            confirmButton.textContent =
                row.dataset.status === 'Confirmed'
                    ? 'Confirmed'
                    : row.dataset.status === 'Rejected'
                        ? 'Rejected'
                        : 'Confirm';

            confirmButton.classList.toggle('opacity-50', alreadyProcessed);
            confirmButton.classList.toggle('cursor-not-allowed', alreadyProcessed);
        }

        modal.classList.remove('hidden');
    }

    // =========================================================
    // MAKE EACH ROW CLICKABLE
    // =========================================================

    document.querySelectorAll('.donation-row').forEach(row => {
        row.addEventListener('click', () => openModal(row));

        row.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openModal(row);
            }
        });
    });

    // =========================================================
    // CLOSE MODAL
    // =========================================================

    function closeModal() {
        const modal = document.getElementById('donationModal');

        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // =========================================================
    // CONFIRM DONATION
    // =========================================================

    async function confirmDonation() {
        if (!currentDonationId || !currentDonationRow) {
            alert('Please select a donation first.');
            return;
        }

        const button = document.getElementById('confirmDonationButton');
        const message = document.getElementById('donationActionMessage');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            message.textContent = 'CSRF token is missing. Please refresh the page.';
            message.className = 'mt-3 text-center text-sm text-red-600';
            return;
        }

        if (['Confirmed', 'Rejected'].includes(currentDonationRow.dataset.status)) {
            message.textContent = 'This donation has already been processed.';
            message.className = 'mt-3 text-center text-sm text-amber-600';
            return;
        }

        if (!window.confirm('Are you sure you want to confirm this donation?')) {
            return;
        }

        button.disabled = true;
        button.textContent = 'Confirming...';

        message.textContent = '';
        message.className = 'hidden mt-3 text-center text-sm';

        try {
            const response = await fetch(
                `/donations/${encodeURIComponent(currentDonationId)}/status`,
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        status: 'Confirmed'
                    })
                }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Failed to confirm donation.');
            }

            // Update the selected row immediately.
            currentDonationRow.dataset.status = 'Confirmed';

            const statusBadge = currentDonationRow.querySelector('.donation-status');

            if (statusBadge) {
                statusBadge.textContent = 'Confirmed';
                statusBadge.className =
                    'donation-status px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
            }

            // Update the modal.
            document.getElementById('modalStatus').textContent = 'Confirmed';

            button.textContent = 'Confirmed';

            message.textContent = result.message || 'Donation confirmed successfully.';
            message.className = 'mt-3 text-center text-sm text-green-600';

            // Reapply filters in case the row no longer matches.
            filterDonations();

        } catch (error) {
            console.error('Confirm donation failed:', error);

            message.textContent = error.message || 'An unexpected error occurred.';
            message.className = 'mt-3 text-center text-sm text-red-600';

            button.disabled = false;
            button.textContent = 'Confirm';
        }
    }

    // =========================================================
    // FILTER DONATIONS
    // =========================================================

    function filterDonations() {
        const search = document.getElementById('searchInput').value.toLowerCase().trim();
        const status = document.getElementById('statusFilter').value;
        const type = document.getElementById('typeFilter').value;
        const date = document.getElementById('dateFilter').value;

        const rows = document.querySelectorAll('.donation-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowName = (row.dataset.name || '').toLowerCase();
            const rowStatus = row.dataset.status || '';
            const rowType = (row.dataset.type || '').toUpperCase();
            const rowDate = row.dataset.date || '';

            const matchesSearch = rowName.includes(search);
            const matchesStatus = status === 'all' || rowStatus === status;
            const matchesType = type === 'all' || rowType === type;
            const matchesDate = date === '' || rowDate === date;

            const visible =
                matchesSearch && matchesStatus && matchesType && matchesDate;

            row.style.display = visible ? '' : 'none';

            if (visible) {
                visibleCount++;
            }
        });

        document.getElementById('totalDonations').textContent = visibleCount;
    }

    document.getElementById('searchInput')
        .addEventListener('input', filterDonations);

    document.getElementById('statusFilter')
        .addEventListener('change', filterDonations);

    document.getElementById('typeFilter')
        .addEventListener('change', filterDonations);

    document.getElementById('dateFilter')
        .addEventListener('change', filterDonations);

    // =========================================================
    // LOGOUT MODAL
    // =========================================================

    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>

</body>
</html>
