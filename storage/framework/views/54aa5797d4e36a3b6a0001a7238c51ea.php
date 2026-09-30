<?php
    $toneScreen = [
        'red'    => 'text-red-600 font-semibold',
        'green'  => 'text-green-600 font-semibold',
        'orange' => 'text-orange-500 font-semibold',
        'gray'   => 'text-gray-500 font-semibold',
    ];

    $tonePdf = [
        'red'    => '#dc2626',
        'green'  => '#16a34a',
        'orange' => '#ea580c',
        'gray'   => '#6b7280',
    ];

    $dateQuery = array_filter(['from' => $from, 'to' => $to]);

    // Logo embedded as base64. Skipped if GD is missing or the controller says no logo.
    $logoPath = public_path('images/suhayLogo.png');
    $logoData = (($useLogo ?? true) && extension_loaded('gd') && file_exists($logoPath))
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
?>

<?php if($isPdf): ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SUHAY - <?php echo e($report['sections'][0]['title']); ?></title>

    <style>
        @page { margin: 40px 36px 55px 36px; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        table { width: 100%; border-collapse: collapse; }

        /* ---------- BRAND HEADER ---------- */
        .brand { margin-bottom: 10px; }
        .brand td { vertical-align: middle; }
        .brand .logo-cell { width: 120px; padding-right: 16px; }
        .brand .logo-cell img { width: 110px; height: auto; }
        .brand .text-cell {
            border-left: 1px solid #9ca3af;
            padding-left: 18px;
        }
        .brand .reports {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #0e243a;
        }
        .brand .tagline {
            font-size: 8.5px;
            color: #6b7280;
            margin-top: 3px;
        }
        .brand-rule {
            border-top: 2px solid #d39a11;
            margin: 6px 0 12px 0;
        }

        /* ---------- REPORT INFO ---------- */
        .info-title { font-size: 16px; font-weight: bold; color: #0e243a; }
        .info-ngo   { font-size: 11px; font-weight: bold; color: #d39a11; margin-top: 2px; }
        .info-meta  { font-size: 8.5px; color: #6b7280; margin-top: 5px; }

        .chips { margin-top: 12px; }
        .chips td {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 7px 10px;
        }
        .chips .label { font-size: 8.5px; color: #6b7280; }
        .chips .value { font-size: 13px; font-weight: bold; color: #0e243a; margin-top: 2px; }

        .sub { font-size: 8.5px; color: #9ca3af; margin: 8px 0 0 0; }

        .data { margin-top: 10px; }
        .data th {
            background: #0e243a;
            color: #ffffff;
            font-size: 9px;
            text-align: left;
            padding: 5px 6px;
        }
        .data td {
            padding: 5px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .data tr:nth-child(even) td { background: #f9fafb; }
        .data thead { display: table-header-group; }
        .data tr    { page-break-inside: avoid; }

        .right { text-align: right; }
        .empty { text-align: center; color: #9ca3af; padding: 10px; }

        .footer {
            position: fixed;
            bottom: -35px;
            left: 0;
            right: 0;
            font-size: 8px;
            color: #9ca3af;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <?php $section = $report['sections'][0]; ?>

    <div class="footer">
        SUHAY · <?php echo e($ngoName); ?> · <?php echo e($section['title']); ?> · Generated <?php echo e($generatedAt); ?>

    </div>

    
    <table class="brand">
        <tr>
            <td class="logo-cell">
                <?php if($logoData): ?>
                    <img src="<?php echo e($logoData); ?>" alt="SUHAY">
                <?php else: ?>
                    <div style="font-size:20px; font-weight:bold; color:#0e243a;">SUHAY</div>
                <?php endif; ?>
            </td>
            <td class="text-cell">
                <div class="reports">REPORTS</div>
                <div class="tagline"><?php echo e($roleLabel); ?></div>
            </td>
        </tr>
    </table>

    <div class="brand-rule"></div>

    
    <div class="info-title"><?php echo e($section['title']); ?></div>
    <div class="info-ngo"><?php echo e($ngoName); ?></div>
    <div class="info-meta">
        Period: <?php echo e($periodLabel); ?> &nbsp;|&nbsp;
        Prepared by: <?php echo e($userName); ?> &nbsp;|&nbsp;
        Generated: <?php echo e($generatedAt); ?>

    </div>

    <?php if(!empty($section['stats'])): ?>
        <table class="chips">
            <tr>
                <?php $__currentLoopData = $section['stats']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <div class="label"><?php echo e($stat[0]); ?></div>
                        <div class="value"><?php echo e($stat[1]); ?></div>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </table>
    <?php endif; ?>

    <?php if(!empty($section['subtitle'])): ?>
        <div class="sub"><?php echo e($section['subtitle']); ?></div>
    <?php endif; ?>

    <table class="data">
        <thead>
            <tr>
                <?php $__currentLoopData = $section['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="<?php echo e(in_array($i, $section['right']) ? 'right' : ''); ?>"><?php echo e($col); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $section['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $text = is_array($cell) ? $cell['text'] : $cell;
                            $tone = is_array($cell) ? ($cell['tone'] ?? null) : null;
                        ?>
                        <td class="<?php echo e(in_array($i, $section['right']) ? 'right' : ''); ?>"
                            <?php if($tone): ?> style="color: <?php echo e($tonePdf[$tone]); ?>; font-weight: bold;" <?php endif; ?>>
                            <?php echo e($text); ?>

                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td class="empty" colspan="<?php echo e(count($section['columns'])); ?>">
                        No data for this period.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

<?php else: ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SUHAY - Reports</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .report-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
        .report-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 9999px; }

        /* Indeterminate progress bar (downloading modal) */
        @keyframes slideBar {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(350%); }
        }
        .progress-bar { animation: slideBar 1.2s ease-in-out infinite; }

        /* Arrow dropping into the tray (downloading modal) */
        @keyframes dropArrow {
            0%   { transform: translateY(-10px); opacity: 0; }
            40%  { opacity: 1; }
            100% { transform: translateY(10px);  opacity: 0; }
        }
        .drop-arrow { animation: dropArrow 1.1s ease-in infinite; }

        /* ============================================= */
        /* SUHAY DATE PICKER (flatpickr theme)           */
        /* ============================================= */
        .flatpickr-calendar {
            font-family: 'Poppins', sans-serif;
            width: 340px;
            padding: 12px 10px 14px;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 32px rgba(14, 36, 58, 0.18);
            background: #ffffff;
        }
        .flatpickr-calendar:before,
        .flatpickr-calendar:after { display: none; }

        /* Header: arrows + month dropdown + year */
        .flatpickr-months { align-items: center; margin-bottom: 6px; }
        .flatpickr-months .flatpickr-month {
            height: 44px;
            color: #0e243a;
            fill: #0e243a;
        }
        .flatpickr-current-month {
            font-size: 22px;
            font-weight: 500;
            padding-top: 6px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 22px;
            color: #0e243a;
            background: transparent;
            border-radius: 8px;
            padding: 2px 4px;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months:hover { background: #f3f4f6; }
        .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background: #ffffff;
            color: #0e243a;
            font-size: 14px;
        }
        .flatpickr-current-month input.cur-year {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 22px;
            color: #0e243a;
        }
        .numInputWrapper:hover { background: transparent; }
        .numInputWrapper span { display: none; } /* hide the tiny year up/down arrows */

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            top: 8px;
            padding: 8px 12px;
            color: #0e243a;
            fill: #0e243a;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg { fill: #d39a11; }

        /* Weekday labels */
        .flatpickr-weekdays { height: 34px; margin-bottom: 2px; }
        span.flatpickr-weekday {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: #6b7280;
            background: transparent;
        }

        /* Grid sizing */
        .flatpickr-innerContainer,
        .flatpickr-rContainer,
        .flatpickr-days,
        .dayContainer {
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }
        .flatpickr-day {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            font-size: 15px;
            color: #1f2937;
            max-width: none;
            width: 14.2857%;
            flex-basis: 14.2857%;
            height: 44px;
            line-height: 42px;
            margin: 1px 0;
            border-radius: 9999px;
            border: 1px solid transparent;
        }
        .flatpickr-day:hover,
        .flatpickr-day:focus {
            background: #fdf3cf;
            border-color: #fdf3cf;
        }

        /* Days from other months */
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #d1d5db;
        }
        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover {
            background: #f9fafb;
            border-color: #f9fafb;
        }

        /* Today (outlined circle) */
        .flatpickr-day.today {
            border-color: #6b7280;
            background: transparent;
        }
        .flatpickr-day.today:hover {
            background: #fdf3cf;
            border-color: #6b7280;
            color: #1f2937;
        }

        /* Selected (yellow filled circle) */
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay {
            background: #f2c94c;
            border-color: #f2c94c;
            color: #0e243a;
            font-weight: 700;
        }

        /* Disabled (outside allowed From/To range) */
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #e5e7eb;
            background: transparent;
            border-color: transparent;
            cursor: not-allowed;
        }

        /* Date input look */
        .date-input {
            width: 11rem;
            background: #ffffff;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-200">

<div class="flex">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8 min-w-0">

        <?php echo $__env->make('components.header', ['title' => 'Reports'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        
        
        <div class="bg-[#0e243a] text-white rounded-2xl p-6 mb-6">
            <p class="text-sm text-gray-300"><?php echo e($roleLabel); ?> Report</p>
            <h2 class="text-2xl font-bold mt-1"><?php echo e($report['title']); ?></h2>
            <p class="text-sm text-gray-300 mt-2"><?php echo e($report['subtitle']); ?></p>
            <p class="text-xs text-[#f2c94c] mt-3">
                <?php echo e($ngoName); ?> · Period: <span id="periodLabel"><?php echo e($periodLabel); ?></span>
            </p>
        </div>

        
        
        
        <?php if($showFilter): ?>
            <form
                id="reportFilter"
                method="GET"
                action="<?php echo e(url('/sm-reports')); ?>"
                class="bg-white rounded-2xl shadow-sm p-5 mb-6 flex flex-wrap items-end gap-4"
            >
                <div>
                    <label class="block text-xs text-gray-500 mb-1">From</label>
                    <input
                        type="text" id="fromDate" name="from" value="<?php echo e($from); ?>"
                        placeholder="mm/dd/yyyy" autocomplete="off"
                    >
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">To</label>
                    <input
                        type="text" id="toDate" name="to" value="<?php echo e($to); ?>"
                        placeholder="mm/dd/yyyy" autocomplete="off"
                    >
                </div>

                <button
                    type="submit"
                    class="px-6 py-2 bg-[#0e243a] text-yellow-400
                           rounded-full text-sm font-semibold hover:opacity-90"
                >
                    Apply
                </button>

                <button
                    type="button"
                    id="resetBtn"
                    class="px-6 py-2 bg-gray-300 rounded-full
                           text-sm font-semibold hover:bg-gray-400"
                >
                    Reset
                </button>
            </form>
        <?php endif; ?>

        
        
        
        <div id="reportCards" class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <?php $__currentLoopData = $report['sections']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col
                            <?php echo e($loop->last && $loop->count % 2 === 1 ? 'xl:col-span-2' : ''); ?>">

                    
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-[#0e243a]"><?php echo e($section['title']); ?></h2>

                            <?php if(!empty($section['subtitle'])): ?>
                                <p class="text-sm text-gray-400"><?php echo e($section['subtitle']); ?></p>
                            <?php endif; ?>
                        </div>

                        <a
                            href="<?php echo e(url('/sm-reports/download') . '?' . http_build_query($dateQuery + ['section' => $section['key']])); ?>"
                            class="download-btn flex-shrink-0 px-5 py-2 bg-[#d39a11] text-white
                                   rounded-full text-sm font-semibold hover:opacity-90"
                        >
                            Download PDF
                        </a>
                    </div>

                    
                    <?php if(!empty($section['stats'])): ?>
                        <div class="flex flex-wrap gap-3 mt-4">
                            <?php $__currentLoopData = $section['stats']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gray-100 rounded-xl px-4 py-2">
                                    <p class="text-[11px] text-gray-500"><?php echo e($stat[0]); ?></p>
                                    <p class="text-base font-bold text-[#0e243a]"><?php echo e($stat[1]); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    
                    <div class="report-scroll overflow-auto max-h-80 mt-4 border border-gray-100 rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="sticky top-0 bg-[#0e243a] text-white">
                                <tr>
                                    <?php $__currentLoopData = $section['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="py-2 px-3 font-semibold whitespace-nowrap
                                            <?php echo e(in_array($i, $section['right']) ? 'text-right' : 'text-left'); ?>">
                                            <?php echo e($col); ?>

                                        </th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $section['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $text = is_array($cell) ? $cell['text'] : $cell;
                                                $tone = is_array($cell) ? ($cell['tone'] ?? null) : null;
                                            ?>
                                            <td class="py-2 px-3 text-[#0e243a]
                                                <?php echo e(in_array($i, $section['right']) ? 'text-right whitespace-nowrap' : ''); ?>

                                                <?php echo e($tone ? $toneScreen[$tone] : ''); ?>">
                                                <?php echo e($text); ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td
                                            colspan="<?php echo e(count($section['columns'])); ?>"
                                            class="py-8 text-center text-gray-400"
                                        >
                                            No data for this period.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <p class="text-xs text-gray-400 mt-3">
                        <?php echo e(count($section['rows'])); ?> record(s)
                    </p>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>

</div>




<div id="loadingModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-12 w-[480px] max-w-[92vw] text-center shadow-lg">
        <div class="mx-auto mb-6 w-24 h-24 rounded-full border-[6px] border-gray-200
                    border-t-[#d39a11] animate-spin"></div>
        <h2 class="text-2xl font-bold text-[#0e243a]">Loading report...</h2>
        <p class="text-base text-gray-500 mt-3">Applying your date range, please wait.</p>
    </div>
</div>




<div id="downloadModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-12 w-[480px] max-w-[92vw] text-center shadow-lg">

        <div class="mx-auto mb-6 w-24 h-24 rounded-full bg-[#0e243a]
                    flex items-center justify-center">
            <svg class="w-12 h-12 text-[#f2c94c]" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
                <g class="drop-arrow">
                    <path d="M12 3v11"/>
                    <path d="M7 10l5 5 5-5"/>
                </g>
                <path d="M4 19h16"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-[#0e243a]">Downloading PDF...</h2>
        <p class="text-base text-gray-500 mt-3">Preparing your report, please wait.</p>

        <div class="relative w-full h-2 bg-gray-200 rounded-full overflow-hidden mt-6">
            <div class="progress-bar absolute top-0 left-0 h-2 w-1/3 bg-[#d39a11] rounded-full"></div>
        </div>
    </div>
</div>

<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<script>
    (function () {
        const baseUrl       = "<?php echo e(url('/sm-reports')); ?>";
        const loadingModal  = document.getElementById('loadingModal');
        const downloadModal = document.getElementById('downloadModal');

        function show(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hide(modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

        /* ---------------------------------------------- */
        /* DATE PICKERS (From / To)                       */
        /* ---------------------------------------------- */
        const form = document.getElementById('reportFilter');
        let fromPicker = null;
        let toPicker   = null;

        if (form && window.flatpickr) {
            const common = {
                dateFormat: 'Y-m-d',      // value sent to the server
                altInput: true,           // what the user sees
                altFormat: 'm/d/Y',
                altInputClass: 'date-input border border-gray-300 rounded-lg px-3 py-2 text-sm',
                disableMobile: true,
                allowInput: false,
                monthSelectorType: 'dropdown'
            };

            fromPicker = flatpickr('#fromDate', {
                ...common,
                onChange: function (selectedDates) {
                    // "To" can't be earlier than "From"
                    toPicker.set('minDate', selectedDates[0] || null);
                }
            });

            toPicker = flatpickr('#toDate', {
                ...common,
                onChange: function (selectedDates) {
                    // "From" can't be later than "To"
                    fromPicker.set('maxDate', selectedDates[0] || null);
                }
            });

            // Apply limits for values already filled in (after page load)
            const fromVal = document.getElementById('fromDate').value;
            const toVal   = document.getElementById('toDate').value;
            if (fromVal) toPicker.set('minDate', fromVal);
            if (toVal)   fromPicker.set('maxDate', toVal);
        }

        /* ---------------------------------------------- */
        /* APPLY / RESET DATE FILTER                      */
        /* ---------------------------------------------- */
        async function loadReport(url) {
            show(loadingModal);

            try {
                const [res] = await Promise.all([
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }),
                    sleep(900) // keep the modal visible so it doesn't just flash
                ]);

                if (!res.ok) throw new Error('Request failed');

                const html = await res.text();
                const doc  = new DOMParser().parseFromString(html, 'text/html');

                document.getElementById('reportCards').innerHTML =
                    doc.getElementById('reportCards').innerHTML;

                document.getElementById('periodLabel').textContent =
                    doc.getElementById('periodLabel').textContent;

                history.replaceState(null, '', url);
            } catch (e) {
                alert('Something went wrong while loading the report. Please try again.');
            } finally {
                hide(loadingModal);
            }
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const from = document.getElementById('fromDate').value; // Y-m-d
                const to   = document.getElementById('toDate').value;   // Y-m-d

                if (from && to && from > to) {
                    alert('"From" date must not be later than "To" date.');
                    return;
                }

                const params = new URLSearchParams();
                if (from) params.set('from', from);
                if (to)   params.set('to', to);

                const qs = params.toString();
                loadReport(qs ? baseUrl + '?' + qs : baseUrl);
            });

            document.getElementById('resetBtn').addEventListener('click', function () {
                if (fromPicker) {
                    fromPicker.clear();
                    fromPicker.set('maxDate', null);
                }
                if (toPicker) {
                    toPicker.clear();
                    toPicker.set('minDate', null);
                }
                loadReport(baseUrl);
            });
        }

        /* ---------------------------------------------- */
        /* DOWNLOAD PDF (with downloading modal)          */
        /* Works for every card, even after Apply/Reset   */
        /* ---------------------------------------------- */
        let downloading = false;

        document.addEventListener('click', async function (e) {
            const btn = e.target.closest('.download-btn');
            if (!btn) return;

            e.preventDefault();
            if (downloading) return;
            downloading = true;

            show(downloadModal);

            try {
                const [res] = await Promise.all([
                    fetch(btn.href, { credentials: 'same-origin' }),
                    sleep(900)
                ]);

                const type = res.headers.get('Content-Type') || '';

                // Server error or not a PDF (e.g. redirected to login)
                if (!res.ok || !type.includes('pdf')) {
                    const body = await res.text();
                    console.error('PDF download failed:', res.status, body.slice(0, 1500));
                    throw new Error('Server responded with status ' + res.status + ' (' + type + ')');
                }

                const blob = await res.blob();

                let fileName = 'SUHAY-report.pdf';
                const disposition = res.headers.get('Content-Disposition') || '';
                const match = disposition.match(/filename="?([^";]+)"?/i);
                if (match) {
                    try { fileName = decodeURIComponent(match[1]); } catch (_) { fileName = match[1]; }
                }

                const blobUrl = URL.createObjectURL(blob);
                const link    = document.createElement('a');
                link.href     = blobUrl;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                link.remove();
                setTimeout(() => URL.revokeObjectURL(blobUrl), 1000);
            } catch (err) {
                alert('Could not download the PDF.\n\n' + err.message);
            } finally {
                hide(downloadModal);
                downloading = false;
            }
        });
    })();
</script>

</body>
</html>
<?php endif; ?><?php /**PATH C:\Sysands\MEt.A-Project-SUHAY\resources\views/reports.blade.php ENDPATH**/ ?>