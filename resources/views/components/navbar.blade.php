<nav class="bg-white shadow-md">
    <div class="w-full px-8 py-6 flex justify-between items-center">

        <!-- LOGO -->
        <div class="flex items-center ml-2">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/suhayLogo.png') }}" class="h-16 w-auto">
            </a>
        </div>

        <!-- MENU -->
        <div class="hidden md:flex items-center gap-10 font-bold text-xl mr-2">

            <a href="{{ route('home') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('/') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               Home
            </a>

            <a href="{{ route('about') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('about') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               About
            </a>

            <a href="{{ route('ngos') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('ngos') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               NGOs
            </a>

            <a href="{{ route('impact') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('impact') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               Impact
            </a>

            <a href="{{ route('volunteer.page') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('volunteer-page') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               Get Involved
            </a>

            <a href="{{ route('donate') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('donate') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               Donate
            </a>

            <a href="{{ route('login.page') }}"
               class="px-4 py-2 rounded-lg transition
               {{ request()->is('login-page') ? 'bg-[#f2c94c] text-white' : 'text-[#0e243a] hover:bg-[#f2c94c] hover:text-white' }}">
               Log in
            </a>

        </div>
    </div>
</nav>