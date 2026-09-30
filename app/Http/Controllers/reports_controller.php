<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class reports_controller extends Controller
{
    private const ROLE_LABELS = [
        0 => 'NGO Head',
        1 => 'Volunteer Manager',
        2 => 'Donation Manager',
    ];

    private const APPLICATION_STATUS = [
        0 => 'Pending',
        1 => 'Approved',
        2 => 'Rejected',
        3 => 'Archived',
    ];

    private string $supabaseUrl;
    private array $headers;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $key = env('SUPABASE_SERVICE_KEY');

        $this->headers = [
            'apikey'        => $key,
            'Authorization' => 'Bearer ' . $key,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROUTE ACTIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if (!session('ngo_id')) {
            return redirect('/login')->with('error', 'NGO session not found.');
        }

        return view('reports', $this->buildReport($request) + ['isPdf' => false]);
    }

    // Downloads ONE card (?section=key) as PDF, respecting the date range
    public function download(Request $request)
    {
        if (!session('ngo_id')) {
            return redirect('/login')->with('error', 'NGO session not found.');
        }

        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $sectionKey = (string) $request->query('section', '');
        $data = $this->buildReport($request, $sectionKey) + ['isPdf' => true];

        abort_if(empty($data['report']['sections']), 404, 'Report section not found.');

        $fileName = 'SUHAY-' . Str::slug($data['roleLabel']) . '-'
            . Str::slug($sectionKey) . '-'
            . now('Asia/Manila')->format('Ymd-His') . '.pdf';

        try {
            // First try: with the SUHAY logo
            return Pdf::loadView('reports', $data + ['useLogo' => true])
                ->setPaper('a4', 'portrait')
                ->download($fileName);
        } catch (\Throwable $e) {
            report($e); // logged in storage/logs/laravel.log

            // Fallback: same PDF without the logo
            return Pdf::loadView('reports', $data + ['useLogo' => false])
                ->setPaper('a4', 'portrait')
                ->download($fileName);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD REPORT (PICKS CARDS BY ROLE)
    |--------------------------------------------------------------------------
    */

    private function buildReport(Request $request, ?string $onlySection = null): array
    {
        $ngoId = session('ngo_id');
        $role  = $this->resolveRole();

        $from = $this->parseDate($request->query('from'), true);
        $to   = $this->parseDate($request->query('to'), false);

        $ctx = ['ngoId' => $ngoId, 'from' => $from, 'to' => $to];

        $report = match ($role) {
            0 => $this->ngoHeadReport($ctx),
            1 => $this->volunteerManagerReport($ctx),
            2 => $this->donationManagerReport($ctx),
        };

        if ($onlySection) {
            $report['sections'] = array_values(array_filter(
                $report['sections'],
                fn ($s) => $s['key'] === $onlySection
            ));
        }

        $ngo = $this->fetch('ngo_profile', ['id' => 'eq.' . $ngoId])->first();

        if ($role === 0) {
            $period = 'Current snapshot';
        } elseif ($from && $to) {
            $period = $from->format('M d, Y') . ' – ' . $to->format('M d, Y');
        } elseif ($from) {
            $period = 'From ' . $from->format('M d, Y');
        } elseif ($to) {
            $period = 'Until ' . $to->format('M d, Y');
        } else {
            $period = 'All time';
        }

        return [
            'report'      => $report,
            'role'        => $role,
            'roleLabel'   => self::ROLE_LABELS[$role],
            'showFilter'  => $role !== 0,
            'ngoName'     => $ngo['name'] ?? $ngo['ngo_name'] ?? 'Your NGO',
            'userName'    => $this->userName(),
            'from'        => $from?->format('Y-m-d'),
            'to'          => $to?->format('Y-m-d'),
            'periodLabel' => $period,
            'generatedAt' => now('Asia/Manila')->format('M d, Y h:i A'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE 0: NGO HEAD  ->  Current Inventory
    |--------------------------------------------------------------------------
    */

    private function ngoHeadReport(array $c): array
    {
        $inventory = $this->inventory($c);
        $low = $inventory->filter(fn ($i) => $this->isLow($i));

        $rows = $inventory
            ->sortBy(fn ($i) => ($this->isLow($i) ? '0' : '1') . strtolower($i['name'] ?? ''))
            ->values()
            ->map(fn ($i) => [
                $i['name'] ?? 'Unnamed Item',
                (string) ($i['current_quantity'] ?? 0),
                (string) ($i['unit'] ?? ''),
                (string) ($i['minimum_threshold'] ?? 0),
                $this->isLow($i) ? $this->tone('LOW STOCK', 'red') : $this->tone('In stock', 'green'),
            ])
            ->all();

        return [
            'title'    => 'Inventory Report',
            'subtitle' => 'Your organization\'s current inventory.',
            'sections' => [
                $this->section(
                    'inventory',
                    'Current Inventory',
                    ['Item', 'Quantity', 'Unit', 'Minimum', 'Status'],
                    $rows,
                    [1, 3],
                    'Live snapshot, low stock items are listed first',
                    [
                        ['Total Items', (string) $inventory->count()],
                        ['Low Stock', (string) $low->count()],
                        ['Sufficient', (string) ($inventory->count() - $low->count())],
                    ]
                ),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE 1: VOLUNTEER MANAGER  ->  Events, Volunteers, Applications
    |--------------------------------------------------------------------------
    */

    private function volunteerManagerReport(array $c): array
    {
        $events  = $this->events($c);
        $allApps = $this->applications($events);
        $apps    = $allApps->filter(fn ($a) => $this->inRange($a['application_date'] ?? null, $c['from'], $c['to']))->values();

        $accounts = $this->accountMap($allApps);
        $eventMap = $events->keyBy(fn ($e) => (string) ($e['id'] ?? ''));

        /* ---------- EVENTS ---------- */

        $eventsInRange = $events
            ->filter(fn ($e) => $this->inRange($e['date'] ?? null, $c['from'], $c['to']))
            ->sortByDesc(fn ($e) => $e['date'] ?? '')
            ->values();

        $byEvent = $allApps->groupBy(fn ($a) => (string) ($a['volunteer_event_id'] ?? ''));

        $eventRows = $eventsInRange->map(function ($e) use ($byEvent) {
            $g = $byEvent->get((string) ($e['id'] ?? ''), collect());

            return [
                $e['name'] ?? 'Unnamed Event',
                $this->fmtDate($e['date'] ?? null),
                ($e['status'] ?? null) == 1 ? $this->tone('Active', 'green') : $this->tone('Inactive', 'gray'),
                (string) $g->count(),
                (string) $g->where('status', 1)->count(),
            ];
        })->all();

        $activeEvents = $eventsInRange->filter(fn ($e) => ($e['status'] ?? null) == 1)->count();

        /* ---------- VOLUNTEERS ---------- */

        $volRows = $apps
            ->filter(fn ($a) => !empty($a['account_id']))
            ->groupBy(fn ($a) => (string) $a['account_id'])
            ->map(fn ($g, $id) => [
                'name'     => $this->fullName($accounts, $id),
                'total'    => $g->count(),
                'approved' => $g->where('status', 1)->count(),
                'last'     => $g->max('application_date'),
            ])
            ->sortBy([['approved', 'desc'], ['total', 'desc']])
            ->values();

        $volTable = $volRows->map(fn ($v) => [
            $v['name'],
            (string) $v['total'],
            (string) $v['approved'],
            $this->fmtDate($v['last']),
        ])->all();

        /* ---------- APPLICATIONS ---------- */

        $appRows = $apps
            ->sortByDesc(fn ($a) => $a['application_date'] ?? '')
            ->values()
            ->map(fn ($a) => [
                $this->fullName($accounts, $a['account_id'] ?? null),
                $eventMap->get((string) ($a['volunteer_event_id'] ?? ''))['name'] ?? 'Unknown Event',
                $this->fmtDate($a['application_date'] ?? null),
                $this->statusCell($a['status'] ?? 0),
            ])
            ->all();

        return [
            'title'    => 'Volunteer Management Report',
            'subtitle' => 'Events, volunteers and applications for the selected period.',
            'sections' => [
                $this->section(
                    'events',
                    'All Events',
                    ['Event', 'Date', 'Status', 'Applications', 'Approved'],
                    $eventRows,
                    [3, 4],
                    'Events held within the selected period',
                    [
                        ['Events', (string) $eventsInRange->count()],
                        ['Active', (string) $activeEvents],
                    ]
                ),
                $this->section(
                    'volunteers',
                    'All Volunteers',
                    ['Volunteer', 'Applications', 'Approved', 'Latest Application'],
                    $volTable,
                    [1, 2],
                    'Volunteers who applied within the selected period',
                    [
                        ['Volunteers', (string) $volRows->count()],
                        ['With Approved', (string) $volRows->where('approved', '>', 0)->count()],
                    ]
                ),
                $this->section(
                    'applications',
                    'All Applications',
                    ['Volunteer', 'Event', 'Applied On', 'Status'],
                    $appRows,
                    [],
                    'Applications submitted within the selected period',
                    [
                        ['Total', (string) $apps->count()],
                        ['Pending', (string) $apps->where('status', 0)->count()],
                        ['Approved', (string) $apps->where('status', 1)->count()],
                        ['Rejected', (string) $apps->where('status', 2)->count()],
                    ]
                ),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE 2: DONATION MANAGER  ->  By Type, Top Donors, Donation Log
    |--------------------------------------------------------------------------
    */

    private function donationManagerReport(array $c): array
    {
        $donations    = $this->donations($c);
        $monetary     = $donations->filter(fn ($d) => $this->isMonetary($d));
        $totalRecords = $donations->count();
        $totalMoney   = $monetary->sum(fn ($d) => $this->amount($d));

        /* ---------- BY TYPE ---------- */

        $typeRows = $donations
            ->groupBy(fn ($d) => $this->pretty($d['type'] ?? null))
            ->map(fn ($g, $type) => [
                'type'     => $type,
                'count'    => $g->count(),
                'total'    => $g->sum(fn ($d) => $this->amount($d)),
                'monetary' => $this->isMonetary($g->first()),
            ])
            ->sortByDesc('count')
            ->values();

        $typeTable = $typeRows->map(fn ($t) => [
            $t['type'],
            (string) $t['count'],
            $t['monetary'] ? $this->peso($t['total']) : '—',
            $totalRecords > 0 ? round($t['count'] / $totalRecords * 100, 1) . '%' : '0%',
        ])->all();

        /* ---------- TOP DONORS ---------- */

        $donorRows = $monetary
            ->groupBy(fn ($d) => trim((string) ($d['source'] ?? '')) ?: 'Anonymous')
            ->map(fn ($g, $src) => [
                'source' => $src,
                'count'  => $g->count(),
                'total'  => $g->sum(fn ($d) => $this->amount($d)),
                'last'   => $g->max('date'),
            ])
            ->sortByDesc('total')
            ->values();

        $donorTable = $donorRows
            ->take(10)
            ->map(fn ($r, $i) => [
                (string) ($i + 1),
                $r['source'],
                (string) $r['count'],
                $this->peso($r['total']),
                $this->fmtDate($r['last']),
            ])
            ->all();

        /* ---------- DONATION LOG ---------- */

        $logTable = $donations
            ->sortByDesc(fn ($d) => $d['date'] ?? '')
            ->values()
            ->map(fn ($d) => [
                $this->fmtDate($d['date'] ?? null),
                trim((string) ($d['source'] ?? '')) ?: 'Anonymous',
                $this->pretty($d['type'] ?? null),
                (string) ($d['description'] ?? '—'),
                $this->isMonetary($d) ? $this->peso($this->amount($d)) : '—',
            ])
            ->all();

        return [
            'title'    => 'Donation Report',
            'subtitle' => 'Donations by type, top donors and the full donation log.',
            'sections' => [
                $this->section(
                    'donations-by-type',
                    'Donations by Type',
                    ['Type', 'Donations', 'Total Amount', 'Share'],
                    $typeTable,
                    [1, 2, 3],
                    'Grouped by donation type',
                    [
                        ['Records', (string) $totalRecords],
                        ['Monetary Total', $this->peso($totalMoney)],
                    ]
                ),
                $this->section(
                    'top-donors',
                    'Top Donors',
                    ['#', 'Donor / Source', 'Donations', 'Total Amount', 'Last Donation'],
                    $donorTable,
                    [2, 3],
                    'Monetary donations only, top 10 by total amount',
                    [
                        ['Donors', (string) $donorRows->count()],
                        ['Top Donor', $donorRows->first() ? $this->peso($donorRows->first()['total']) : $this->peso(0)],
                    ]
                ),
                $this->section(
                    'donation-log',
                    'Donation Log',
                    ['Date', 'Source', 'Type', 'Description', 'Amount'],
                    $logTable,
                    [4],
                    'Every donation in the selected period, newest first',
                    [
                        ['Records', (string) $totalRecords],
                        ['In-Kind', (string) ($totalRecords - $monetary->count())],
                    ]
                ),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DATA LOADERS
    |--------------------------------------------------------------------------
    */

    private function fetch(string $table, array $query = []): Collection
    {
        $res = Http::withHeaders($this->headers)
            ->get($this->supabaseUrl . '/rest/v1/' . $table, array_merge(['select' => '*'], $query));

        $json = $res->json();

        return collect(is_array($json) ? $json : [])
            ->filter(fn ($row) => is_array($row))
            ->values();
    }

    private function donations(array $c): Collection
    {
        return $this->fetch('donations_table', ['ngo_id' => 'eq.' . $c['ngoId']])
            ->filter(fn ($d) => $this->inRange($d['date'] ?? null, $c['from'], $c['to']))
            ->values();
    }

    private function inventory(array $c): Collection
    {
        return $this->fetch('inventory', ['ngo_id' => 'eq.' . $c['ngoId']]);
    }

    private function events(array $c): Collection
    {
        return $this->fetch('volunteer_events', ['ngo_id' => 'eq.' . $c['ngoId']]);
    }

    private function applications(Collection $events): Collection
    {
        $ids = $events->pluck('id')->filter()->map(fn ($id) => (string) $id)->implode(',');

        if ($ids === '') {
            return collect();
        }

        return $this->fetch('volunteer_applications', ['volunteer_event_id' => 'in.(' . $ids . ')']);
    }

    private function accountMap(Collection $apps): Collection
    {
        $ids = $apps->pluck('account_id')->filter()->unique()->implode(',');

        if ($ids === '') {
            return collect();
        }

        return $this->fetch('accounts', [
            'select' => 'id,first_name,last_name',
            'id'     => 'in.(' . $ids . ')',
        ])->keyBy(fn ($a) => (string) ($a['id'] ?? ''));
    }

    private function resolveRole(): int
    {
        $role = session('role');

        if ($role === null && session('user_id')) {
            $account = $this->fetch('accounts', [
                'select' => 'role',
                'id'     => 'eq.' . session('user_id'),
            ])->first();

            $role = $account['role'] ?? null;
        }

        abort_unless(
            is_numeric($role) && array_key_exists((int) $role, self::ROLE_LABELS),
            403,
            'You are not allowed to view reports.'
        );

        return (int) $role;
    }

    private function userName(): string
    {
        $user = null;

        if (session('user_id')) {
            $user = $this->fetch('accounts', [
                'select' => 'first_name,last_name',
                'id'     => 'eq.' . session('user_id'),
            ])->first();
        }

        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

        return $name !== '' ? $name : session('user_name', 'NGO User');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function section(
        string $key,
        string $title,
        array $columns,
        array $rows,
        array $right = [],
        ?string $subtitle = null,
        array $stats = []
    ): array {
        return compact('key', 'title', 'columns', 'rows', 'right', 'subtitle', 'stats');
    }

    private function tone(string $text, string $tone): array
    {
        return ['text' => $text, 'tone' => $tone];
    }

    private function statusCell($status): array|string
    {
        $status = (int) $status;
        $label  = self::APPLICATION_STATUS[$status] ?? 'Unknown';
        $tone   = [0 => 'orange', 1 => 'green', 2 => 'red'][$status] ?? null;

        return $tone ? $this->tone($label, $tone) : $label;
    }

    private function fullName(Collection $accounts, $id): string
    {
        $a = $accounts->get((string) $id);
        $name = trim(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));

        return $name !== '' ? $name : 'Unknown';
    }

    private function peso($n): string
    {
        return '₱' . number_format((float) $n, 2);
    }

    private function amount(array $d): float
    {
        return (float) ($d['amount'] ?? 0);
    }

    private function fmtDate($v): string
    {
        if (empty($v)) {
            return '—';
        }

        try {
            return Carbon::parse($v)->format('M d, Y');
        } catch (\Throwable $e) {
            return '—';
        }
    }

    private function pretty($v): string
    {
        $v = trim((string) $v);

        return $v === '' ? 'Unspecified' : Str::title(strtolower(str_replace('_', ' ', $v)));
    }

    private function isMonetary(array $d): bool
    {
        return in_array(strtoupper($d['type'] ?? ''), ['MONETARY', 'ONLINE_MONETARY'], true);
    }

    private function isLow(array $i): bool
    {
        return (float) ($i['current_quantity'] ?? 0) <= (float) ($i['minimum_threshold'] ?? 0);
    }

    private function parseDate($value, bool $startOfDay): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            $d = Carbon::parse($value);

            return $startOfDay ? $d->startOfDay() : $d->endOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function inRange($value, ?Carbon $from, ?Carbon $to): bool
    {
        if (!$from && !$to) {
            return true;
        }

        if (empty($value)) {
            return false;
        }

        try {
            $d = Carbon::parse($value);
        } catch (\Throwable $e) {
            return false;
        }

        return (!$from || $d->gte($from)) && (!$to || $d->lte($to));
    }
}