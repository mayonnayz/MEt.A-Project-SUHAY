<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SUHAY - Dashboard</title>


    {{-- GOOGLE FONT --}}

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- TAILWIND --}}


    <script src="https://cdn.tailwindcss.com"></script>


    <style>

        body {
            font-family: 'Poppins', sans-serif;
        }

    </style>

</head>


<body class="bg-gray-200">


<div class="flex">


    {{-- ================================================= --}}
    {{-- SIDEBAR                                           --}}
    {{-- ================================================= --}}

    @include('components.nav')


    {{-- ================================================= --}}
    {{-- MAIN CONTENT                                      --}}
    {{-- ================================================= --}}

    <div class="flex-1 p-8">


        {{-- ================================================= --}}
        {{-- HEADER                                            --}}
        {{-- ================================================= --}}

        @include('components.header', [
    'title' => 'Dashboard'
])


        {{-- ================================================= --}}
        {{-- NGO WELCOME SECTION                                --}}
        {{-- ================================================= --}}

      <div class="bg-[#0e243a] text-white rounded-2xl p-6 mb-6">

    <p class="text-sm text-gray-300">
        Welcome back,
        {{ $userName }}!
    </p>

    <h2 class="text-2xl font-bold mt-1">
        {{ $ngoName }}
    </h2>

    <p class="text-sm text-gray-300 mt-2">
        Here's an overview of your organization's current activities.
    </p>

</div>



        {{-- ================================================= --}}
        {{-- SUMMARY CARDS                                     --}}
        {{-- ================================================= --}}

        <div
            class="grid grid-cols-1
                   sm:grid-cols-2
                   lg:grid-cols-4
                   gap-5 mb-6"
        >


            {{-- ================================================= --}}
            {{-- DONATIONS                                         --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Donations
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    ₱{{ number_format($monthlyDonations ?? 0, 2) }}

                </h2>


                <p class="text-xs text-gray-400 mt-1">
                    This Month
                </p>

            </div>



            {{-- ================================================= --}}
            {{-- INVENTORY                                         --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Inventory Items
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    {{ $inventoryItems ?? 0 }}

                </h2>


                <p class="text-xs text-red-500 mt-1">

                    {{ $lowStockItems ?? 0 }}

                    items low in stock

                </p>

            </div>



            {{-- ================================================= --}}
            {{-- VOLUNTEERS                                        --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Active Volunteers
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    {{ $activeVolunteers ?? 0 }}

                </h2>


                <p class="text-xs text-gray-400 mt-1">
                    Current volunteers
                </p>

            </div>



            {{-- ================================================= --}}
            {{-- APPLICATIONS                                      --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       p-5 shadow-sm"
            >

                <p class="text-sm text-gray-500">
                    Pending Applications
                </p>


                <h2
                    class="text-2xl font-bold
                           text-[#0e243a] mt-2"
                >

                    {{ $pendingApplications ?? 0 }}

                </h2>


                <p class="text-xs text-orange-500 mt-1">
                    Requires review
                </p>

            </div>


        </div>


     

        
        {{-- ================================================= --}}
        {{-- NGO UPDATES                                       --}}
        {{-- ================================================= --}}

    
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <div>
            <h2 class="text-lg font-bold text-[#0e243a]">
                NGO Updates
            </h2>
            <p class="text-sm text-gray-400">
                Share announcements and stories with the SUHAY community.
            </p>
        </div>

        <button
            type="button"
            onclick="openUpdatePostModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0e243a] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#183b5d] transition"
        >
            <span class="text-lg leading-none">+</span>
            Create Post
        </button>
    </div>

{{-- POST STATUS FILTERS --}}
<div class="flex flex-wrap items-center gap-2 mb-4">
    <button
        type="button"
        onclick="filterUpdatePosts('all', this)"
        data-filter="all"
        class="update-filter-btn rounded-lg bg-[#0e243a] px-3 py-2 text-xs font-semibold text-white transition"
    >
        All
    </button>

    <button
        type="button"
        onclick="filterUpdatePosts('1', this)"
        data-filter="1"
        class="update-filter-btn rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition"
    >
        Published
    </button>

    <button
        type="button"
        onclick="filterUpdatePosts('0', this)"
        data-filter="0"
        class="update-filter-btn rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition"
    >
        Drafts
    </button>

    <button
        type="button"
        onclick="filterUpdatePosts('2', this)"
        data-filter="2"
        class="update-filter-btn rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition"
    >
        Archived
    </button>
</div>

    {{-- NGO POSTS --}}
    @forelse(($updatePosts ?? []) as $index => $post)
    
<div
    class="update-post-item border border-gray-100 rounded-xl p-4 mb-3"
    data-status="{{ (int) $post->status }}"
>


     

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                {{-- POST CONTENT --}}
                <div class="min-w-0 flex-1">

                    {{-- TITLE AND STATUS --}}
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="font-semibold text-[#0e243a]">
                            {{ $post->title }}
                        </h3>

                        @if((int) $post->status === 1)
                            <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                Published
                            </span>
                        @elseif((int) $post->status === 2)
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                Archived
                            </span>
                        @else
                            <span class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                Draft
                            </span>
                        @endif
                    </div>

                    {{-- POST DATE --}}
                    @if(!empty($post->date_created))
                        <p class="text-xs text-gray-400 mb-3 flex items-center gap-1.5">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>

                            {{ \Carbon\Carbon::parse($post->date_created)
                                ->timezone('Asia/Manila')
                                ->format('M d, Y • h:i A') }}
                        </p>
                    @endif

                   
{{-- POST IMAGES --}}
@if(!empty($post->images))
    <div class="w-full max-w-2xl mb-4">
        <div class="grid grid-cols-2 gap-2">
            @foreach($post->images as $index => $image)
                <div class="overflow-hidden rounded-xl bg-gray-100
                    {{ count($post->images) === 3 && $index === 0 ? 'col-span-2' : '' }}">
                    <img
                        src="{{ $image }}"
                        alt="{{ $post->title }} - Image {{ $index + 1 }}"
                        loading="lazy"
                        class="block w-full h-40 sm:h-48 object-cover rounded-xl"
                        onerror="console.error('Post image failed:', this.src); this.closest('div').classList.add('hidden');"
                    >
                </div>
            @endforeach
        </div>
    </div>
@endif

                    {{-- POST DESCRIPTION --}}
                    <p class="text-sm text-gray-600 whitespace-pre-line">
                        {{ \Illuminate\Support\Str::limit($post->description, 220) }}
                    </p>

                </div>

                {{-- POST ACTIONS --}}
                <div class="flex flex-wrap items-center gap-2 sm:justify-end">

                    {{-- PUBLISH / UNPUBLISH --}}
                    @if((int) $post->status === 1)

                        <form
                            method="POST"
                            action="{{ url('/update-posts/' . $post->id . '/status') }}"
                            onsubmit="return false;"
                            data-confirm-title="Unpublish Post"
                            data-confirm-message="This post will no longer be published. Do you want to move it back to drafts?"
                            data-confirm-text="Unpublish"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="0">

                            <button
                                type="button"
                                onclick="confirmUpdatePostAction(this)"
                                class="rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2 text-xs font-semibold text-yellow-700 hover:bg-yellow-100 transition"
                            >
                                Unpublish
                            </button>
                        </form>

                    @elseif((int) $post->status === 0)

                        <form
                            method="POST"
                            action="{{ url('/update-posts/' . $post->id . '/status') }}"
                            onsubmit="return false;"
                            data-confirm-title="Publish Post"
                            data-confirm-message="This post will be published and made available to the SUHAY community. Do you want to continue?"
                            data-confirm-text="Publish"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="1">

                            <button
                                type="button"
                                onclick="confirmUpdatePostAction(this)"
                                class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 hover:bg-green-100 transition"
                            >
                                Publish
                            </button>
                        </form>

                    @endif
                        
                    {{-- EDIT POST --}}
                    <button
                        type="button"
                        onclick="openEditUpdatePostModal(
                            {{ $post->id }},
                            {{ Js::from($post->title) }},
                            {{ Js::from($post->description) }}
                        )"
                        class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition"
                    >
                        Edit
                    </button>


                    {{-- ARCHIVE / RESTORE --}}
                    @if((int) $post->status === 2)

                        <form
                            method="POST"
                            action="{{ url('/update-posts/' . $post->id . '/status') }}"
                            onsubmit="return false;"
                            data-confirm-title="Restore Post"
                            data-confirm-message="This archived post will be restored as a draft. Do you want to continue?"
                            data-confirm-text="Restore"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="0">

                            <button
                                type="button"
                                onclick="confirmUpdatePostAction(this)"
                                class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition"
                            >
                                Restore to Draft
                            </button>
                        </form>

                    @else

                        <form
                            method="POST"
                            action="{{ url('/update-posts/' . $post->id . '/status') }}"
                            onsubmit="return false;"
                            data-confirm-title="Archive Post"
                            data-confirm-message="This post will be archived and no longer treated as an active update. Do you want to continue?"
                            data-confirm-text="Archive"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="2">

                            <button
                                type="button"
                                onclick="confirmUpdatePostAction(this)"
                                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition"
                            >
                                Archive
                            </button>
                        </form>

                    @endif

                    {{-- DELETE --}}
                    <form
                        method="POST"
                        action="{{ url('/update-posts/' . $post->id) }}"
                        onsubmit="return false;"
                        data-confirm-title="Delete Post"
                        data-confirm-message="This post will be permanently deleted. This action cannot be undone. Are you sure?"
                        data-confirm-text="Delete Permanently"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            onclick="confirmUpdatePostAction(this)"
                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100 transition"
                        >
                            Delete
                        </button>
                    </form>

                </div>

            </div>
        </div>

    @empty

        <div class="rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 text-[#b38720]">
                <span class="text-2xl">✎</span>
            </div>

            <p class="font-semibold text-[#0e243a]">
                No updates yet
            </p>

            <p class="mt-1 text-sm text-gray-400">
                Create your first post to share your organization's news.
            </p>
        </div>

    @endforelse

   

{{-- SEE MORE / SEE LESS --}}
<div id="updatePostsToggleContainer" class="hidden flex justify-center mt-4">
    <button
        type="button"
        id="toggleUpdatePostsBtn"
        onclick="toggleUpdatePosts()"
        aria-expanded="false"
        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-[#0e243a] hover:bg-gray-50 transition"
    >
        <span id="toggleUpdatePostsText">See More Updates</span>

        <svg
            id="toggleUpdatePostsIcon"
            xmlns="http://www.w3.org/2000/svg"
            class="w-4 h-4 transition-transform duration-200"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M19 9l-7 7-7-7"
            />
        </svg>
    </button>
</div>


</div>



        {{-- ================================================= --}}
        {{-- SECOND ROW: APPLICATIONS AND EVENTS               --}}
        {{-- ================================================= --}}

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- VOLUNTEER APPLICATIONS --}}

            <div class="bg-white rounded-2xl shadow-sm p-6">

                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-[#0e243a]">
                            Volunteer Applications
                        </h2>

                        <p class="text-sm text-gray-400">
                            Application status
                        </p>
                    </div>

                    <a href="/applications"
                       class="text-sm text-[#d39a11] hover:underline">
                        View
                    </a>
                </div>

                @php
                    $totalApplications =
                        ($pendingApplications ?? 0) +
                        ($approvedApplications ?? 0) +
                        ($rejectedApplications ?? 0);

                    $pendingPercentage = $totalApplications > 0
                        ? (($pendingApplications ?? 0) / $totalApplications) * 100
                        : 0;

                    $approvedPercentage = $totalApplications > 0
                        ? (($approvedApplications ?? 0) / $totalApplications) * 100
                        : 0;

                    $rejectedPercentage = $totalApplications > 0
                        ? (($rejectedApplications ?? 0) / $totalApplications) * 100
                        : 0;
                @endphp

                {{-- PENDING --}}
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Pending</span>
                        <span class="font-semibold text-orange-500">
                            {{ $pendingApplications ?? 0 }}
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-400 h-2 rounded-full"
                             style="width: {{ $pendingPercentage }}%">
                        </div>
                    </div>
                </div>

                {{-- APPROVED --}}
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Approved</span>
                        <span class="font-semibold text-green-600">
                            {{ $approvedApplications ?? 0 }}
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full"
                             style="width: {{ $approvedPercentage }}%">
                        </div>
                    </div>
                </div>

                {{-- REJECTED --}}
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Rejected</span>
                        <span class="font-semibold text-red-500">
                            {{ $rejectedApplications ?? 0 }}
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-400 h-2 rounded-full"
                             style="width: {{ $rejectedPercentage }}%">
                        </div>
                    </div>
                </div>

            </div>


            {{-- UPCOMING EVENTS --}}

            <div class="bg-white rounded-2xl shadow-sm p-6">

                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-[#0e243a]">
                            Upcoming Events
                        </h2>

                        <p class="text-sm text-gray-400">
                            Volunteer events
                        </p>
                    </div>

                    <a href="/events"
                       class="text-sm text-[#d39a11] hover:underline">
                        View
                    </a>
                </div>

                @forelse($upcomingEvents ?? [] as $event)

                    <div class="flex gap-4 mb-5">

                        <div class="w-12 h-12 rounded-xl bg-[#f2c94c] flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-xs">
                                {{ \Carbon\Carbon::parse($event->date)->format('M') }}
                            </span>

                            <span class="text-lg font-bold">
                                {{ \Carbon\Carbon::parse($event->date)->format('d') }}
                            </span>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-semibold text-[#0e243a]">
                                {{ $event->name }}
                            </h3>

                            <p class="text-xs text-gray-500">
                                {{ $event->volunteer_count ?? 0 }}
                                volunteer(s) assigned
                            </p>
                        </div>

                    </div>

                @empty

                    <div class="py-5 text-center">
                        <p class="text-sm text-gray-400">
                            No upcoming events.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- THIRD ROW                                         --}}
        {{-- ================================================= --}}

        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-6 mb-6"
        >


            {{-- ================================================= --}}
            {{-- INVENTORY ALERTS                                  --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       shadow-sm p-6"
            >

                <div
                    class="flex justify-between
                           items-center mb-5"
                >

                    <div>

                        <h2
                            class="text-lg font-bold
                                   text-[#0e243a]"
                        >
                            Inventory Alerts
                        </h2>


                        <p class="text-sm text-gray-400">
                            Items requiring attention
                        </p>

                    </div>


                    <a
                        href="/inventory-master-list"
                        class="text-sm text-[#d39a11]
                               hover:underline"
                    >
                        View
                    </a>

                </div>



                {{-- REAL INVENTORY ALERTS --}}

                @forelse($inventoryAlerts ?? [] as $item)

                    <div
                        class="flex justify-between
                               items-center
                               bg-red-50
                               rounded-xl
                               p-4 mb-3"
                    >

                        <div>

                            <p
                                class="font-semibold
                                       text-[#0e243a]"
                            >

                                {{ $item->name }}

                            </p>


                            <p
                                class="text-xs
                                       text-gray-500"
                            >

                                {{ $item->current_quantity }}

                                {{ $item->unit }}

                                remaining

                            </p>


                            <p
                                class="text-[11px]
                                       text-gray-400 mt-1"
                            >

                                Minimum:

                                {{ $item->minimum_threshold }}

                            </p>

                        </div>


                        <span
                            class="text-xs
                                   font-semibold
                                   text-red-600"
                        >
                            LOW STOCK
                        </span>

                    </div>


                @empty

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No inventory alerts.

                        </p>

                    </div>

                @endforelse


            </div>



            {{-- ================================================= --}}
            {{-- RECENT ACTIVITY                                   --}}
            {{-- ================================================= --}}

            <div
                class="bg-white rounded-2xl
                       shadow-sm p-6"
            >

                <h2
                    class="text-lg font-bold
                           text-[#0e243a] mb-5"
                >
                    Recent Activity
                </h2>



                {{-- REAL ACTIVITIES --}}

                @forelse($recentActivities ?? [] as $activity)

                    <div class="flex gap-4 mb-5">

                        {{-- ICON --}}

                        <div
                            class="
                                w-9 h-9
                                rounded-full
                                flex items-center
                                justify-center
                                flex-shrink-0

                                @if($activity->title === 'volunteer')
                                    bg-blue-100
                                @elseif($activity->title === 'donation')
                                    bg-green-100
                                @else
                                    bg-yellow-100
                                @endif
                            "
                        >

                            @if($activity->title === 'volunteer')

                                <span
                                    class="text-blue-600"
                                >
                                    V
                                </span>

                            @elseif($activity->title === 'donation')

                                <span
                                    class="text-green-600"
                                >
                                    ₱
                                </span>

                            @else

                                <span
                                    class="text-yellow-600"
                                >
                                    I
                                </span>

                            @endif

                        </div>



                        {{-- ACTIVITY DETAILS --}}

                        <div>

                            <p class="text-sm text-[#0e243a]">
                                <strong>
                                    {{ $activity->title }}
                                </strong>

                                {{ $activity->description }}
                            </p>

                         <p class="text-xs text-gray-400">
                            {{ $activity->date
                                ? \Carbon\Carbon::parse($activity->date, 'UTC')
                                    ->setTimezone('Asia/Manila')
                                    ->diffForHumans()
                                : 'No date'
                            }}
                        </p>

                        </div>

                    </div>


                @empty

                    <div class="py-5 text-center">

                        <p class="text-sm text-gray-400">

                            No recent activity.

                        </p>

                    </div>

                @endforelse


            </div>


        </div>


    </div>


</div>

@include('components.update-post-modal')
@include('components.edit-post-modal')


@include('components.confirm-modal')
@include('components.logout-modal')

@include('components.confirm-modal-script')

<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
