<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Members</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
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
        @include('components.header', ['title' => 'NGO Profile'])

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-10 flex-wrap">

            <a href="/sm-ngos"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>

            <a href="/ngo-members"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Members
            </a>

            <a href="/ngo-accounts"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Accounts
            </a>

        </div>

        <div class="text-center mb-16">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#0e243a]">
                {{ $ngo->name ?? 'NGO NAME' }} BOARD
            </h1>
        </div>

        <div class="flex flex-col items-center gap-16"> 

            <!-- NGO HEAD -->
            <div class="text-center">

                @if($head)

                    @php
                        $headInitials =
                            mb_substr($head->first_name ?? '', 0, 1) .
                            mb_substr($head->last_name ?? '', 0, 1);
                    @endphp

                    <div class="w-36 h-36 bg-[#0e243a] rounded-full mx-auto flex items-center justify-center text-white text-4xl font-bold shadow-lg overflow-hidden">

                        @if(!empty($head->profile_picture))
                            <img
                                src="{{ $head->profile_picture }}"
                                alt="{{ $head->first_name }}'s profile picture"
                                class="w-full h-full object-cover"
                            >
                        @else
                            {{ strtoupper($headInitials) }}
                        @endif

                    </div>

                    <h2 class="mt-4 text-xl font-bold text-[#0e243a]">
                        {{ $head->first_name }} {{ $head->last_name }}
                    </h2>

                    <p class="text-gray-500 font-semibold">
                        NGO Head
                    </p>

                @else

                    <div class="w-36 h-36 bg-[#0e243a] rounded-full mx-auto flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                        --
                    </div>

                    <h2 class="mt-4 text-xl font-bold text-[#0e243a]">
                        No NGO Head Assigned
                    </h2>

                    <p class="text-gray-500 font-semibold">
                        NGO Head
                    </p>

                @endif

            </div>


            <!-- DONATION AND VOLUNTEER MANAGERS -->
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-16 mt-4">

                <!-- DONATION MANAGERS -->
                <div class="text-center">

                    <h3 class="text-xl font-bold text-[#0e243a] mb-10">
                        Donation Managers
                    </h3>

                    <div class="flex flex-col items-center gap-10">

                        @forelse($donationManagers as $manager)

                            @php
                                $initials =
                                    mb_substr($manager->first_name ?? '', 0, 1) .
                                    mb_substr($manager->last_name ?? '', 0, 1);
                            @endphp

                            <div class="text-center group hover:scale-105 transition-transform duration-300">

                                <div class="w-28 h-28 bg-gray-600 rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold shadow overflow-hidden">

                                    @if(!empty($manager->profile_picture))
                                        <img
                                            src="{{ $manager->profile_picture }}"
                                            alt="{{ $manager->first_name }}'s profile picture"
                                            class="w-full h-full object-cover"
                                        >
                                    @else
                                        {{ strtoupper($initials) }}
                                    @endif

                                </div>

                                <h2 class="mt-3 font-bold text-[#0e243a]">
                                    {{ $manager->first_name }}
                                    {{ $manager->last_name }}
                                </h2>

                            </div>

                        @empty

                            <p class="text-gray-500 text-sm">
                                No Donation Managers assigned.
                            </p>

                        @endforelse

                    </div>

                </div>


                <!-- VOLUNTEER MANAGERS -->
                <div class="text-center">

                    <h3 class="text-xl font-bold text-[#0e243a] mb-10">
                        Volunteer Managers
                    </h3>

                    <div class="flex flex-col items-center gap-10">

                        @forelse($volunteerManagers as $manager)

                            @php
                                $initials =
                                    mb_substr($manager->first_name ?? '', 0, 1) .
                                    mb_substr($manager->last_name ?? '', 0, 1);
                            @endphp

                            <div class="text-center group hover:scale-105 transition-transform duration-300">

                                <div class="w-28 h-28 bg-[#1a3554] rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold shadow overflow-hidden">

                                    @if(!empty($manager->profile_picture))
                                        <img
                                            src="{{ $manager->profile_picture }}"
                                            alt="{{ $manager->first_name }}'s profile picture"
                                            class="w-full h-full object-cover"
                                        >
                                    @else
                                        {{ strtoupper($initials) }}
                                    @endif

                                </div>

                                <h2 class="mt-3 font-bold text-[#0e243a]">
                                    {{ $manager->first_name }}
                                    {{ $manager->last_name }}
                                </h2>

                            </div>

                        @empty

                            <p class="text-gray-500 text-sm">
                                No Volunteer Managers assigned.
                            </p>

                        @endforelse

                    </div>

                </div>

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
