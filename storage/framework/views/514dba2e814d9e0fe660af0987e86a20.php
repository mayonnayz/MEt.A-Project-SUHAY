<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact | SUHAY</title>

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/suhayLogo.png')); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        .post-card {
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(14, 36, 58, .18);
        }

        .chip {
            transition: all .2s ease;
        }

        .chip.active {
            background: #0e243a;
            color: #fff;
            border-color: #0e243a;
        }

        .clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .post-gallery img {
            transition: transform .3s ease;
        }

        .post-gallery a:hover img {
            transform: scale(1.03);
        }

        .gallery-trigger {
            cursor: zoom-in;
        }

        /* Lightbox */
        #lightbox {
            transition: opacity .2s ease;
        }

        #lightbox.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        #lbImg {
            transition: opacity .2s ease;
        }
    </style>
</head>

<body class="bg-gray-100 text-[#0e243a]">

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $posts = collect($posts ?? []);
        $ngos = collect($ngos ?? []);

        // Supports arrays and objects.
        $ngoName = fn($p) =>
            data_get($p, 'ngo_name')
            ?? data_get($p, 'ngo.ngo_name')
            ?? data_get($p, 'ngo.name')
            ?? data_get($p, 'ngo_profile.ngo_name')
            ?? data_get($p, 'ngo_profile.name')
            ?? 'SUHAY Partner NGO';

        $ngoLogo = fn($p) =>
            data_get($p, 'ngo_logo')
            ?? data_get($p, 'ngo.logo')
            ?? data_get($p, 'ngo_profile.logo');
    ?>

    <section
        class="relative h-[460px] w-full overflow-hidden"
        data-aos="fade-in"
        data-aos-duration="1000"
    >
        <img
            src="<?php echo e(asset('images/hero.jpg')); ?>"
            class="w-full h-full object-cover"
            alt="SUHAY community impact"
        >

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
                    <a
                        href="#feed"
                        class="px-7 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold shadow-lg hover:-translate-y-0.5 transition"
                    >
                        View Latest Updates
                    </a>

                    <a
                        href="/donate"
                        class="px-7 py-3 rounded-full border-2 border-white/70 text-white font-semibold hover:bg-white hover:text-[#0e243a] transition"
                    >
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
                <p class="text-gray-600 mt-2">
                    Real, unfiltered posts from the organizations on SUHAY
                </p>
            </div>

            

            <div
                class="bg-white rounded-2xl shadow p-4 md:p-5 mb-10"
                data-aos="fade-up"
            >
                <div class="flex flex-col md:flex-row md:items-center gap-4">

                    <div class="relative md:w-72 shrink-0">
                        <svg
                            class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>

                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Search updates..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-full bg-gray-100 border border-transparent focus:border-[#f2c94c] focus:bg-white outline-none text-sm"
                        >
                    </div>

                    <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">

                        <button
                            type="button"
                            class="chip active shrink-0 px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold"
                            data-ngo="all"
                        >
                            All NGOs
                        </button>

                        <?php $__currentLoopData = $posts->map($ngoName)->unique()->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                class="chip shrink-0 px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold"
                                data-ngo="<?php echo e(Str::slug($name)); ?>"
                            >
                                <?php echo e($name); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>
            </div>


            <div class="grid lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6" id="feedList">

                    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <?php
                            $name = $ngoName($post);
                            $logo = $ngoLogo($post);

                            $title = data_get($post, 'title', 'Untitled Update');
                            $desc = data_get($post, 'description', '');

                            // Get every image associated with this post.
                            $rawImages = data_get($post, 'images', []);

                            if (is_string($rawImages)) {
                                $rawImages = [$rawImages];
                            }

                            $images = collect($rawImages ?? [])
                                ->filter(fn($image) => is_string($image) && trim($image) !== '')
                                ->unique()
                                ->values()
                                ->all();

                            // Backward compatibility with older controller data.
                            if (empty($images)) {
                                $fallbackImage =
                                    data_get($post, 'image_url')
                                    ?? data_get($post, 'image');

                                if (is_string($fallbackImage) && trim($fallbackImage) !== '') {
                                    $images = [$fallbackImage];
                                }
                            }

                            // Use the actual Supabase date_created field.
                            $date = data_get($post, 'date_created')
                                ?? data_get($post, 'created_at');

                            $when = null;
                            $full = null;

                            if ($date) {
                                try {
                                    $parsedDate = \Carbon\Carbon::parse($date);

                                    $when = $parsedDate->diffForHumans();
                                    $full = $parsedDate->format('F j, Y \a\t g:i A');
                                } catch (\Throwable $e) {
                                    $when = null;
                                    $full = null;
                                }
                            }

                            $searchText = mb_strtolower(
                                $name . ' ' . $title . ' ' . $desc
                            );
                        ?>

                        <article
                            class="post-card bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden feed-item"
                            data-ngo="<?php echo e(Str::slug($name)); ?>"
                            data-search="<?php echo e($searchText); ?>"
                            data-title="<?php echo e($title); ?>"
                            data-images="<?php echo e(json_encode($images)); ?>"
                            data-aos="fade-up"
                            data-aos-delay="<?php echo e(min($i, 4) * 80); ?>"
                        >

                            <div class="flex items-start justify-between gap-3 p-5 pb-3">

                                <div class="flex items-center gap-3 min-w-0">

                                    <?php if($logo): ?>
                                        <img
                                            src="<?php echo e($logo); ?>"
                                            alt="<?php echo e($name); ?>"
                                            class="w-11 h-11 shrink-0 rounded-full object-cover border border-gray-200"
                                            loading="lazy"
                                            onerror="this.style.display='none';"
                                        >
                                    <?php else: ?>
                                        <div class="w-11 h-11 shrink-0 rounded-full bg-[#0e243a] text-[#f2c94c] flex items-center justify-center font-bold">
                                            <?php echo e(strtoupper(mb_substr($name, 0, 1))); ?>

                                        </div>
                                    <?php endif; ?>

                                    <div class="min-w-0">

                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h3 class="font-bold leading-tight">
                                                <?php echo e($name); ?>

                                            </h3>

                                            <svg
                                                class="w-4 h-4 text-[#f2c94c] shrink-0"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                title="Verified partner"
                                            >
                                                <path d="M12 2l2.4 2.1 3.2-.3 1 3 2.8 1.6-.9 3.1.9 3.1-2.8 1.6-1 3-3.2-.3L12 22l-2.4-2.1-3.2.3-1-3L2.6 15.6l.9-3.1-.9-3.1 2.8-1.6 1-3 3.2.3L12 2zm-1.2 13.4l5-5-1.4-1.4-3.6 3.6-1.6-1.6-1.4 1.4 3 3z"/>
                                            </svg>
                                        </div>

                                        <?php if($when): ?>
                                            <p
                                                class="text-xs text-gray-500 mt-1"
                                                title="<?php echo e($full); ?>"
                                            >
                                                <?php echo e($when); ?>

                                            </p>
                                        <?php else: ?>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Date unavailable
                                            </p>
                                        <?php endif; ?>

                                    </div>
                                </div>

                                <span class="shrink-0 text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Verified Update
                                </span>

                            </div>

                            <div class="px-5 pb-4">

                                <h4 class="text-xl font-bold mb-1.5">
                                    <?php echo e($title); ?>

                                </h4>

                                <p class="text-gray-600 leading-relaxed clamp-4 post-desc">
                                    <?php echo e($desc); ?>

                                </p>

                                <?php if(mb_strlen((string) $desc) > 220): ?>
                                    <button
                                        type="button"
                                        class="toggle-more mt-1 text-sm font-semibold text-[#c9a227] hover:underline"
                                    >
                                        Read more
                                    </button>
                                <?php endif; ?>

                            </div>

                            <?php if(count($images) === 1): ?>

                                <div class="w-full overflow-hidden bg-gray-100">
                                    <button
                                        type="button"
                                        class="gallery-trigger block w-full"
                                        data-index="0"
                                        aria-label="View image for <?php echo e($title); ?>"
                                    >
                                        <img
                                            src="<?php echo e($images[0]); ?>"
                                            alt="<?php echo e($title); ?>"
                                            loading="lazy"
                                            class="block w-full max-h-[480px] object-cover"
                                            onerror="console.error('Post image failed:', this.src); this.closest('div').classList.add('hidden');"
                                        >
                                    </button>
                                </div>

                            <?php elseif(count($images) > 1): ?>

                                <div class="post-gallery grid grid-cols-2 gap-0.5">

                                    <?php $__currentLoopData = array_slice($images, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <a
                                            href="<?php echo e($img); ?>"
                                            data-index="<?php echo e($k); ?>"
                                            aria-label="View image <?php echo e($k + 1); ?> for <?php echo e($title); ?>"
                                            class="gallery-trigger relative block aspect-[4/3] overflow-hidden bg-gray-100 <?php echo e(count($images) === 3 && $k === 0 ? 'col-span-2' : ''); ?>"
                                        >

                                            <img
                                                src="<?php echo e($img); ?>"
                                                alt="<?php echo e($title); ?> - Image <?php echo e($k + 1); ?>"
                                                loading="lazy"
                                                class="w-full h-full object-cover"
                                                onerror="console.error('Post image failed:', this.src); this.closest('a').classList.add('hidden');"
                                            >

                                            <?php if($k === 3 && count($images) > 4): ?>
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-2xl font-bold pointer-events-none">
                                                    +<?php echo e(count($images) - 4); ?>

                                                </div>
                                            <?php endif; ?>

                                        </a>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>

                            <?php endif; ?>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-5 py-3 border-t border-gray-100 text-sm text-gray-500">

                                <span>
                                    Posted by <?php echo e($name); ?>

                                </span>

                                <a
                                    href="/donate"
                                    class="font-semibold text-[#0e243a] hover:text-[#c9a227] transition"
                                >
                                    Support this NGO →
                                </a>

                            </div>

                        </article>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div class="bg-white rounded-2xl shadow p-12 text-center">

                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#f2c94c]/20 flex items-center justify-center text-3xl">
                                📰
                            </div>

                            <h3 class="text-xl font-bold mb-1">
                                No updates yet
                            </h3>

                            <p class="text-gray-500">
                                Partner NGOs haven't posted anything yet. Check back soon!
                            </p>

                        </div>

                    <?php endif; ?>

                    <div
                        id="noResults"
                        class="hidden bg-white rounded-2xl shadow p-10 text-center"
                    >
                        <h3 class="text-lg font-bold mb-1">
                            No matching updates
                        </h3>

                        <p class="text-gray-500 text-sm">
                            Try a different keyword or select another NGO.
                        </p>
                    </div>

                </div>


                <aside class="space-y-6 lg:sticky lg:top-6 self-start">
                    <div
                        class="bg-white rounded-2xl shadow-md p-6"
                        data-aos="fade-left"
                    >
                        <h3 class="font-bold text-lg mb-4">
                            Participating NGOs
                        </h3>

                        <ul class="space-y-3">

                            <?php $__empty_1 = true; $__currentLoopData = $ngos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ngo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                <?php
                                    $n = data_get($ngo, 'ngo_name')
                                        ?? data_get($ngo, 'name')
                                        ?? 'NGO';

                                    $l = data_get($ngo, 'logo');
                                ?>

                                <li>
                                    <button
                                        type="button"
                                        class="side-ngo w-full flex items-center gap-3 text-left hover:bg-gray-50 rounded-xl p-2 transition"
                                        data-ngo="<?php echo e(Str::slug($n)); ?>"
                                    >

                                        <?php if($l): ?>

                                            <img
                                                src="<?php echo e($l); ?>"
                                                class="w-9 h-9 rounded-full object-cover shrink-0"
                                                alt="<?php echo e($n); ?>"
                                                loading="lazy"
                                                onerror="this.style.display='none';"
                                            >

                                        <?php else: ?>

                                            <span class="w-9 h-9 shrink-0 rounded-full bg-[#0e243a] text-[#f2c94c] flex items-center justify-center text-sm font-bold">
                                                <?php echo e(strtoupper(mb_substr($n, 0, 1))); ?>

                                            </span>

                                        <?php endif; ?>

                                        <span class="font-medium text-sm">
                                            <?php echo e($n); ?>

                                        </span>

                                    </button>
                                </li>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                <li class="text-sm text-gray-500">
                                    NGOs will appear here once registered.
                                </li>

                            <?php endif; ?>

                        </ul>
                    </div>

                    <div
                        class="rounded-2xl p-6 bg-gradient-to-br from-[#0e243a] to-[#16385a] text-white shadow-md"
                        data-aos="fade-left"
                        data-aos-delay="100"
                    >

                        <h3 class="font-bold text-lg mb-2">
                            Why transparency?
                        </h3>

                        <p class="text-sm text-gray-200 leading-relaxed mb-4">
                            Every post here comes directly from verified NGOs on SUHAY, so donors and volunteers can see how help reaches communities.
                        </p>

                        <ul class="text-sm space-y-2 text-gray-100">
                            <li class="flex gap-2">
                                <span class="text-[#f2c94c]">✔</span>
                                Posted by verified NGOs
                            </li>

                            <li class="flex gap-2">
                                <span class="text-[#f2c94c]">✔</span>
                                Updated in real time
                            </li>

                            <li class="flex gap-2">
                                <span class="text-[#f2c94c]">✔</span>
                                Open to everyone
                            </li>
                        </ul>

                    </div>

                </aside>

            </div>
        </div>
    </section>


    <section
        class="py-20 bg-[#0e243a] text-center text-white"
        data-aos="fade-up"
        data-aos-duration="1000"
    >
        <div class="max-w-4xl mx-auto px-6">

            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                Be Part of the Impact
            </h2>

            <p class="mb-8 text-lg md:text-xl text-gray-200 max-w-2xl mx-auto leading-relaxed">
                Join us in making a difference in the lives of others.
            </p>

            <a
                href="/donate"
                class="inline-flex items-center px-12 py-4 rounded-full font-bold text-lg bg-[#f2c94c] hover:bg-[#f2c94c]/90 text-[#0e243a] shadow-xl hover:shadow-2xl hover:-translate-y-1 transform transition-all duration-300"
            >
                Donate Now
            </a>

        </div>
    </section>

    <div
        id="lightbox"
        class="fixed inset-0 z-[100] bg-black/90 opacity-0 pointer-events-none flex items-center justify-center"
        role="dialog"
        aria-modal="true"
        aria-label="Image viewer"
        aria-hidden="true"
    >
        <button
            type="button"
            id="lbClose"
            aria-label="Close"
            class="absolute top-4 right-4 w-11 h-11 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition z-10"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
            </svg>
        </button>

        <div
            id="lbCounter"
            class="absolute top-5 left-5 text-white/90 text-sm font-semibold bg-white/10 px-3 py-1 rounded-full z-10"
        ></div>

        <button
            type="button"
            id="lbPrev"
            aria-label="Previous image"
            class="absolute left-3 md:left-6 top-1/2 -translate-y-1/2 w-11 h-11 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition z-10"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <button
            type="button"
            id="lbNext"
            aria-label="Next image"
            class="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 w-11 h-11 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition z-10"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <figure id="lbStage" class="max-w-[92vw] max-h-[88vh] flex flex-col items-center">
            <img
                id="lbImg"
                src=""
                alt=""
                class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl select-none"
                draggable="false"
            >
            <figcaption id="lbCaption" class="mt-3 text-white/90 text-sm text-center px-4"></figcaption>
        </figure>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 100,
                    easing: 'ease-out-cubic'
                });
            }

            const items = document.querySelectorAll('.feed-item');
            const chips = document.querySelectorAll('.chip');
            const search = document.getElementById('searchInput');
            const noRes = document.getElementById('noResults');

            let activeNgo = 'all';


            function applyFilters() {
                const q = (search?.value || '').trim().toLowerCase();
                let visible = 0;

                items.forEach(el => {
                    const okNgo =
                        activeNgo === 'all' ||
                        el.dataset.ngo === activeNgo;

                    const okQ =
                        !q ||
                        (el.dataset.search || '').includes(q);

                    const show = okNgo && okQ;

                    el.classList.toggle('hidden', !show);

                    if (show) {
                        visible++;
                    }
                });

                if (noRes) {
                    noRes.classList.toggle(
                        'hidden',
                        visible > 0 || items.length === 0
                    );
                }

                if (typeof AOS !== 'undefined') {
                    AOS.refreshHard();
                }
            }

            function setNgo(slug) {
                activeNgo = slug;

                chips.forEach(chip => {
                    chip.classList.toggle(
                        'active',
                        chip.dataset.ngo === slug
                    );
                });

                applyFilters();
            }

            chips.forEach(chip => {
                chip.addEventListener('click', function () {
                    setNgo(this.dataset.ngo);
                });
            });

            document.querySelectorAll('.side-ngo').forEach(button => {
                button.addEventListener('click', function () {
                    setNgo(this.dataset.ngo);

                    document.getElementById('feed')?.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            search?.addEventListener('input', applyFilters);


            document.querySelectorAll('.toggle-more').forEach(button => {
                button.addEventListener('click', function () {
                    const desc = this.previousElementSibling;

                    if (!desc) return;

                    const expanded = desc.classList.toggle('clamp-4') === false;

                    this.textContent = expanded
                        ? 'Show less'
                        : 'Read more';
                });
            });

            const lb = document.getElementById('lightbox');
            const lbImg = document.getElementById('lbImg');
            const lbCounter = document.getElementById('lbCounter');
            const lbCaption = document.getElementById('lbCaption');
            const lbPrev = document.getElementById('lbPrev');
            const lbNext = document.getElementById('lbNext');
            const lbClose = document.getElementById('lbClose');
            const lbStage = document.getElementById('lbStage');

            let lbImages = [];
            let lbIndex = 0;
            let lbTitle = '';
            let lbLastFocus = null;

            function isLightboxOpen() {
                return lb.classList.contains('is-open');
            }

            function preload(i) {
                if (!lbImages.length) return;
                const idx = (i + lbImages.length) % lbImages.length;
                const img = new Image();
                img.src = lbImages[idx];
            }

            function renderLightbox() {
                const total = lbImages.length;

                lbImg.style.opacity = '0';
                lbImg.onload = () => { lbImg.style.opacity = '1'; };
                lbImg.onerror = () => { lbImg.style.opacity = '1'; };
                lbImg.src = lbImages[lbIndex];
                lbImg.alt = lbTitle + ' - Image ' + (lbIndex + 1);

                lbCaption.textContent = lbTitle;
                lbCounter.textContent = (lbIndex + 1) + ' / ' + total;

                const multiple = total > 1;
                lbPrev.classList.toggle('hidden', !multiple);
                lbNext.classList.toggle('hidden', !multiple);
                lbCounter.classList.toggle('hidden', !multiple);

                if (multiple) {
                    preload(lbIndex + 1);
                    preload(lbIndex - 1);
                }
            }

            function openLightbox(images, index, title) {
                if (!images.length) return;

                lbImages = images;
                lbIndex = Math.min(Math.max(index, 0), images.length - 1);
                lbTitle = title || '';
                lbLastFocus = document.activeElement;

                renderLightbox();

                lb.classList.add('is-open');
                lb.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                lbClose.focus();
            }

            function closeLightbox() {
                lb.classList.remove('is-open');
                lb.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                lbImg.src = '';

                if (lbLastFocus && typeof lbLastFocus.focus === 'function') {
                    lbLastFocus.focus();
                }
            }

            function showNext() {
                if (lbImages.length < 2) return;
                lbIndex = (lbIndex + 1) % lbImages.length;
                renderLightbox();
            }

            function showPrev() {
                if (lbImages.length < 2) return;
                lbIndex = (lbIndex - 1 + lbImages.length) % lbImages.length;
                renderLightbox();
            }

            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('.gallery-trigger');
                if (!trigger) return;

                const article = trigger.closest('.feed-item');
                if (!article) return;

                e.preventDefault();

                let images = [];
                try {
                    images = JSON.parse(article.dataset.images || '[]');
                } catch (err) {
                    images = [];
                }

                openLightbox(
                    images,
                    parseInt(trigger.dataset.index || '0', 10),
                    article.dataset.title || ''
                );
            });

            lbClose.addEventListener('click', closeLightbox);
            lbNext.addEventListener('click', showNext);
            lbPrev.addEventListener('click', showPrev);
            lb.addEventListener('click', function (e) {
                if (e.target === lb || e.target === lbStage) {
                    closeLightbox();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (!isLightboxOpen()) return;

                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowRight') {
                    showNext();
                } else if (e.key === 'ArrowLeft') {
                    showPrev();
                }
            });

            let touchStartX = 0;
            let touchStartY = 0;

            lb.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].clientX;
                touchStartY = e.changedTouches[0].clientY;
            }, { passive: true });

            lb.addEventListener('touchend', function (e) {
                const dx = e.changedTouches[0].clientX - touchStartX;
                const dy = e.changedTouches[0].clientY - touchStartY;

                if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) {
                    dx < 0 ? showNext() : showPrev();
                }
            }, { passive: true });

        });
    </script>

</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY\resources\views/impact.blade.php ENDPATH**/ ?>