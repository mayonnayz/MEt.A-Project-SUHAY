<div class="p-6 bg-gray-100 rounded-xl">

    <!-- 🔷 Program Header -->
    <div class="bg-white p-5 rounded-xl shadow mb-6">
        <h2 class="text-2xl font-bold text-gray-800">KALINGA PROGRAM</h2>
        <p class="text-gray-600">March 22, 2026 | 9:00 AM – 4:00 PM</p>
        <p class="text-gray-600">Tayuman, Sta. Cruz, Manila</p>

        <div class="mt-3 flex items-center justify-between">
            <span class="text-lg font-semibold">
                Assigned: <span class="text-yellow-500">12 / 20</span>
            </span>
            <span class="bg-yellow-400 text-white px-4 py-1 rounded-full">
                Open
            </span>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-300 rounded-full mt-3">
            <div class="bg-yellow-500 h-3 rounded-full" style="width: 60%"></div>
        </div>
    </div>

    <!-- 🔍 Search + Filters -->
    <div class="flex justify-between mb-4">
        <input type="text" placeholder="Search Volunteers..."
            class="border p-2 rounded w-1/2">

        <select class="border p-2 rounded">
            <option>All Skills</option>
            <option>Medic</option>
            <option>Logistics</option>
            <option>General</option>
        </select>
    </div>

    <div class="grid grid-cols-2 gap-6">

        <!-- 👥 Available Volunteers -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="font-bold text-lg mb-3">Available Volunteers</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center border p-2 rounded">
                    <div>
                        <p class="font-semibold">Juan Dela Cruz</p>
                        <p class="text-sm text-gray-500">Medic</p>
                    </div>
                    <button class="bg-blue-500 text-white px-3 py-1 rounded">
                        Assign
                    </button>
                </div>

                <div class="flex justify-between items-center border p-2 rounded">
                    <div>
                        <p class="font-semibold">Maria Santos</p>
                        <p class="text-sm text-gray-500">Logistics</p>
                    </div>
                    <button class="bg-blue-500 text-white px-3 py-1 rounded">
                        Assign
                    </button>
                </div>
            </div>
        </div>

        <!-- 📋 Assigned Volunteers -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="font-bold text-lg mb-3">Assigned Volunteers</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center border p-2 rounded">
                    <div>
                        <p class="font-semibold">Ana Reyes</p>
                        <p class="text-sm text-gray-500">Team Leader</p>
                    </div>

                    <div class="flex gap-2">
                        <select class="border p-1 rounded">
                            <option>Leader</option>
                            <option>Medic</option>
                            <option>Member</option>
                        </select>

                        <button class="bg-red-500 text-white px-2 py-1 rounded">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 💾 Actions -->
    <div class="flex justify-end mt-6 gap-3">
        <button class="bg-gray-400 text-white px-4 py-2 rounded">
            Cancel
        </button>
        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Save Assignments
        </button>
    </div>

</div>