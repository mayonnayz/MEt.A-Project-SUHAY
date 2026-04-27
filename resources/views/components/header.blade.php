<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[#0e243a]">
        {{ $title ?? 'Dashboard' }}
    </h1>

    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gray-300 rounded-full"></div>

        <p class="text-[#0e243a]">
            {{ session('user_name', 'Guest') }}
        </p>
    </div>
</div>