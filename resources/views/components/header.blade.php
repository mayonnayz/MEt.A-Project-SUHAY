<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[#0e243a]">
        {{ $title ?? 'Dashboard' }}
    </h1>

    <div class="flex items-center gap-3">
        @if(session('ngo_logo'))
            <img
                src="{{ session('ngo_logo') }}"
                alt="{{ session('user_name', 'Guest') }}"
                class="w-10 h-10 rounded-full object-cover bg-gray-100"
                onerror="this.src='{{ asset('images/suhayLogo.png') }}'"
            >
        @else
            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
        @endif

        <p class="text-[#0e243a]">
            {{ session('user_name', 'Guest') }}
        </p>
    </div>
</div>