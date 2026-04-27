<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Donations</title>
    <style>
        .sidebar-gradient { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .active-nav { background-color: #d97706; color: white; border-radius: 9999px; }
    </style>
</head>
<body class="bg-slate-100 font-sans">

    <div class="flex min-h-screen">
        @include('components.nav')

        <main class="flex-1 p-8">
              @include('components.header', ['title' => 'Donation Management'])

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
                <div class="p-6 bg-slate-50 border-b border-slate-200">
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="relative flex-1 min-w-[300px]">
                            <input type="text" placeholder="Search Donors..." class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-300 focus:ring-2 focus:ring-amber-500 outline-none">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400"></i>
                        </div>
                        <select class="px-4 py-2 rounded-full border border-slate-300 bg-white text-slate-600 outline-none">
                            <option>Unconfirmed</option>
                            <option>Confirmed</option>
                        </select>
                        <select class="px-4 py-2 rounded-full border border-slate-300 bg-white text-slate-600 outline-none">
                            <option>All Types</option>
                            <option>Monetary</option>
                            <option>Non-Monetary</option>
                        </select>
                        <input type="date" class="px-4 py-2 rounded-full border border-slate-300 text-slate-600 outline-none">
                    </div>
                    <div class="mt-4 text-slate-500 text-sm font-semibold">
                        Total Number of Donations: <span class="text-slate-800">148</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-slate-800 uppercase text-sm border-b">
                            <tr>
                                <th class="px-6 py-4 font-bold">#</th>
                                <th class="px-6 py-4 font-bold">Name</th>
                                <th class="px-6 py-4 font-bold">Donation Type</th>
                                <th class="px-6 py-4 font-bold">Date</th>
                                <th class="px-6 py-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-500">1</td>
                                <td class="px-6 py-4 font-medium text-slate-800">Jose Example</td>
                                <td class="px-6 py-4 text-slate-600">Non-Monetary</td>
                                <td class="px-6 py-4 text-slate-600">2026-03-18</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-1 rounded-lg font-bold transition-transform active:scale-95">View</button>
                                </td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>