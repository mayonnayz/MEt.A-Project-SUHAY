<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact | SUHAY</title>

    <link rel="icon" type="image/png" href="{{ asset('images/suhayLogo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        html { scroll-behavior: smooth; }

        .post-card { transition: all .3s cubic-bezier(.4,0,.2,1); }
        .post-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(14,36,58,.18); }

        .chip { transition: all .2s ease; }
        .chip.active { background:#0e243a; color:#fff; border-color:#0e243a; }

        .clamp-4 {
            display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-100 text-[#0e243a]">

    @include('components.navbar')

    @php
        // ---- Safe data helpers (works with arrays or objects/models) ----
        $posts = collect($posts ?? []);
        $ngos  = collect($ngos ?? []);

        $ngoName = fn($p) => data_get($p, 'ngo_name')
            ?? data_get($p, 'ngo.ngo_name')
            ?? data_get($p, 'ngo.name')
            ?? data_get($p, 'ngo_profile.ngo_name')
            ?? data_get($p, 'ngo_profile.name')
            ?? 'SUHAY Partner NGO';

        $ngoLogo = fn($p) => data_get($p, 'ngo_logo')
            ?? data_get($p, 'ngo.logo')
            ?? data_get($p, 'ngo_profile.logo');

    @endphp

    <section class="relative h-[460px] w-full overflow-hidden" data-aos="fade-in" data-aos-duration="1000">
        <img src="{{ asset('images/hero.jpg') }}" class="w-full h-full object-cover" alt="">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0e243a] via-[#0e243a]/85 to-[#0e243a]/20"></div>

        <div class="absolute inset-0 flex items-center">
            <div class="px-8 md:px-16 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#f2c94c]/15 border border-[#f2c94c]/40 text-[#f2c94c] text-sm font-semibold mb-5">
                    <span class="w-2 h-2 rounded-full bg-[#f2c94c] animate-pulse"></span>
                    Transparency Board
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
                    Our <span class="text-[#f2c94c]">Impact</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-200 leading-relaxed">
                    Every update, donation drive, and outreach — posted directly by our partner NGOs.
                    See exactly where your support goes.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#feed" class="px-7 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold shadow-lg hover:-translate-y-0.5 transition">
                        View Latest Updates
                    </a>
                    <a href="/donate" class="px-7 py-3 rounded-full border-2 border-white/70 text-white font-semibold hover:bg-white hover:text-[#0e243a] transition">
                        Donate Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="feed" class="py-16 px-6">
        <div class="max-w-6xl mx-auto">

            <div class="text-center mb-10" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold">NGO Updates</h2>
                <p class="text-gray-600 mt-2">Real, unfiltered posts from the organizations on SUHAY</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-4 md:p-5 mb-10" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="relative md:w-72 shrink-0">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input id="searchInput" type="text" placeholder="Search updates..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-full bg-gray-100 border border-transparent focus:border-[#f2c94c] focus:bg-white outline-none text-sm">
                    </div>

                    <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                        <button class="chip active shrink-0 px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold" data-ngo="all">
                            All NGOs
                        </button>
                        @foreach ($posts->map($ngoName)->unique()->values() as $name)
                            <button class="chip shrink-0 px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold"
                                    data-ngo="{{ Str::slug($name) }}">
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6" id="feedList">

                    @forelse ($posts as $i => $post)
                        @php
                            $name   = $ngoName($post);
                            $logo   = $ngoLogo($post);
                            $title  = data_get($post, 'title');
                            $desc   = data_get($post, 'description');
                            $images = data_get($post, 'images') ?: (data_get($post, 'image_url') ? [data_get($post, 'image_url')] : []);
                            $date   = data_get($post, 'created_at');
                            $when   = $date ? \Carbon\Carbon::parse($date)->diffForHumans() : null;
                            $full   = $date ? \Carbon\Carbon::parse($date)->format('F j, Y') : null;
                        @endphp

                        <article class="post-card bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden feed-item"
                                 data-ngo="{{ Str::slug($name) }}"
                                 data-search="{{ strtolower($name . ' ' . $title . ' ' . $desc) }}"
                                 data-aos="fade-up" data-aos-delay="{{ min($i, 4) * 80 }}">
                            <div class="flex items-center justify-between p-5 pb-3">
                                <div class="flex items-center gap-3">
                                    @if ($logo)
                                        <img src="{{ $logo }}" alt="{{ $name }}" class="w-11 h-11 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-[#0e243a] text-[#f2c94c] flex items-center justify-center font-bold">
                                            {{ strtoupper(mb_substr($name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <h3 class="font-bold leading-tight">{{ $name }}</h3>
                                            <svg class="w-4 h-4 text-[#f2c94c]" viewBox="0 0 24 24" fill="currentColor" title="Verified partner"><path d="M12 2l2.4 2.1 3.2-.3 1 3 2.8 1.6-.9 3.1.9 3.1-2.8 1.6-1 3-3.2-.3L12 22l-2.4-2.1-3.2.3-1-3L2.6 15.6l.9-3.1-.9-3.1 2.8-1.6 1-3 3.2.3L12 2zm-1.2 13.4l5-5-1.4-1.4-3.6 3.6-1.6-1.6-1.4 1.4 3 3z"/></svg>
                                        </div>
                                        @if ($when)
                                            <p class="text-xs text-gray-500" title="{{ $full }}">{{ $when }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">Verified Update</span>
                            </div>

                            <div class="px-5 pb-4">
                                <h4 class="text-xl font-bold mb-1.5">{{ $title }}</h4>
                                <p class="text-gray-600 leading-relaxed clamp-4 post-desc">{{ $desc }}</p>
                                @if (strlen((string) $desc) > 220)
                                    <button class="toggle-more mt-1 text-sm font-semibold text-[#c9a227] hover:underline">Read more</button>
                                @endif
                            </div>

                            @if (count($images) === 1)
                                <img src="{{ $images[0] }}" alt="{{ $title }}" loading="lazy"
                                     class="w-full max-h-[480px] object-cover">
                            @elseif (count($images) > 1)
                                <div class="grid grid-cols-2 gap-0.5">
                                    @foreach (array_slice($images, 0, 4) as $k => $img)
                                        <div class="relative aspect-[4/3] overflow-hidden {{ count($images) === 3 && $k === 0 ? 'col-span-2' : '' }}">
                                            <img src="{{ $img }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover">
                                            @if ($k === 3 && count($images) > 4)
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-2xl font-bold">
                                                    +{{ count($images) - 4 }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 text-sm text-gray-500">
                                <span>Posted by {{ $name }}</span>
                                <a href="/donate" class="font-semibold text-[#0e243a] hover:text-[#c9a227] transition">Support this NGO →</a>
                            </div>
                        </article>
                    @empty
                        <div class="bg-white rounded-2xl shadow p-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#f2c94c]/20 flex items-center justify-center text-3xl">📰</div>
                            <h3 class="text-xl font-bold mb-1">No updates yet</h3>
                            <p class="text-gray-500">Partner NGOs haven't posted anything yet. Check back soon!</p>
                        </div>
                    @endforelse

                    <div id="noResults" class="hidden bg-white rounded-2xl shadow p-10 text-center">
                        <h3 class="text-lg font-bold mb-1">No matching updates</h3>
                        <p class="text-gray-500 text-sm">Try a different keyword or select another NGO.</p>
                    </div>
                </div>

                <aside class="space-y-6 lg:sticky lg:top-6 self-start">

                    <div class="bg-white rounded-2xl shadow-md p-6" data-aos="fade-left">
                        <h3 class="font-bold text-lg mb-4">Participating NGOs</h3>
                        <ul class="space-y-3">
                            @forelse ($ngos as $ngo)
                                @php
                                    $n = data_get($ngo, 'ngo_name') ?? data_get($ngo, 'name') ?? 'NGO';
                                    $l = data_get($ngo, 'logo');
                                @endphp
                                <li>
                                    <button class="side-ngo w-full flex items-center gap-3 text-left hover:bg-gray-50 rounded-xl p-2 transition"
                                            data-ngo="{{ Str::slug($n) }}">
                                        @if ($l)
                                            <img src="{{ $l }}" class="w-9 h-9 rounded-full object-cover" alt="">
                                        @else
                                            <span class="w-9 h-9 rounded-full bg-[#0e243a] text-[#f2c94c] flex items-center justify-center text-sm font-bold">{{ strtoupper(mb_substr($n, 0, 1)) }}</span>
                                        @endif
                                        <span class="font-medium text-sm">{{ $n }}</span>
                                    </button>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">NGOs will appear here once registered.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="rounded-2xl p-6 bg-gradient-to-br from-[#0e243a] to-[#16385a] text-white shadow-md" data-aos="fade-left" data-aos-delay="100">
                        <h3 class="font-bold text-lg mb-2">Why transparency?</h3>
                        <p class="text-sm text-gray-200 leading-relaxed mb-4">
                            Every post here comes directly from verified NGOs on SUHAY, so donors and volunteers
                            can see how help reaches communities.
                        </p>
                        <ul class="text-sm space-y-2 text-gray-100">
                            <li class="flex gap-2"><span class="text-[#f2c94c]">✔</span> Posted by verified NGOs</li>
                            <li class="flex gap-2"><span class="text-[#f2c94c]">✔</span> Updated in real time</li>
                            <li class="flex gap-2"><span class="text-[#f2c94c]">✔</span> Open to everyone</li>
                        </ul>
                    </div>

                </aside>
            </div>
        </div>
    </section>

    <section class="py-20 bg-[#0e243a] text-center text-white" data-aos="fade-up" data-aos-duration="1000">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Be Part of the Impact</h2>
            <p class="mb-8 text-lg md:text-xl text-gray-200 max-w-2xl mx-auto leading-relaxed">
                Join us in making a difference in the lives of others.
            </p>
            <a href="/donate"
               class="inline-flex items-center px-12 py-4 rounded-full font-bold text-lg bg-[#f2c94c] hover:bg-[#f2c94c]/90 text-[#0e243a] shadow-xl hover:shadow-2xl hover:-translate-y-1 transform transition-all duration-300">
                Donate Now
            </a>
        </div>
    </section>

    <script>
        AOS.init({ duration: 800, once: true, offset: 100, easing: 'ease-out-cubic' });

        const items   = document.querySelectorAll('.feed-item');
        const chips   = document.querySelectorAll('.chip');
        const search  = document.getElementById('searchInput');
        const noRes   = document.getElementById('noResults');
        let activeNgo = 'all';

        function applyFilters() {
            const q = (search.value || '').trim().toLowerCase();
            let visible = 0;

            items.forEach(el => {
                const okNgo = activeNgo === 'all' || el.dataset.ngo === activeNgo;
                const okQ   = !q || el.dataset.search.includes(q);
                const show  = okNgo && okQ;
                el.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            noRes.classList.toggle('hidden', visible > 0 || items.length === 0);
        }

        function setNgo(slug) {
            activeNgo = slug;
            chips.forEach(c => c.classList.toggle('active', c.dataset.ngo === slug));
            applyFilters();
        }

        chips.forEach(c => c.addEventListener('click', () => setNgo(c.dataset.ngo)));
        document.querySelectorAll('.side-ngo').forEach(b => b.addEventListener('click', () => {
            setNgo(b.dataset.ngo);
            document.getElementById('feed').scrollIntoView({ behavior: 'smooth' });
        }));
        search.addEventListener('input', applyFilters);

        document.querySelectorAll('.toggle-more').forEach(btn => {
            btn.addEventListener('click', () => {
                const desc = btn.previousElementSibling;
                const open = desc.classList.toggle('clamp-4') === false;
                btn.textContent = open ? 'Show less' : 'Read more';
            });
        });
    </script>

</body>
</html>