<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Account</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">
    @include('components.nav')

    <div class="flex-1 p-8">
        @include('components.header', ['title' => 'NGO Profile'])

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-10 flex-wrap">
            <a href="/sm-ngos"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <a href="/ngo-members"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Members
            </a>

            <a href="/ngo-accounts"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Accounts
            </a>
        </div>

        <div class="bg-white rounded-2xl p-8">

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-[#0e243a]">Account Details</h2>
                <a href="{{ route('ngo-accounts') }}"
                   class="bg-gray-200 text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm">
                    Back to Accounts
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">First Name</div>
                    <div class="text-[#0e243a] text-base">{{ $account->first_name }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Last Name</div>
                    <div class="text-[#0e243a] text-base">{{ $account->last_name }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Email</div>
                    <div class="text-[#0e243a] text-base">{{ $account->email }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Role</div>
                    <div class="text-[#0e243a] text-base">{{ $account->role_label }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Contact Number</div>
                    <div class="text-[#0e243a] text-base">{{ $account->contact_number ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Birth Date</div>
                    <div class="text-[#0e243a] text-base">
                        {{ $account->birth_date ? \Carbon\Carbon::parse($account->birth_date)->format('M d, Y') : '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status</div>
                    <div>
                        @if($account->status == 1)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">Archived</span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="mt-10 flex gap-3">
                @if($account->status == 1)
                    <form action="{{ route('ngo-accounts.archive', $account->id) }}" method="POST"
                          onsubmit="return confirm('Archive this account?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-[#f2c94c] text-[#0e243a] px-6 py-2.5 rounded-lg font-semibold text-sm">
                            Archive Account
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>

@include('components.logout-modal')

<script>
function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}
</script>

</body>
</html>