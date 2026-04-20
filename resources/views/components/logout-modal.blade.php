<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 w-[350px] text-center shadow-lg">
        <h2 class="text-xl font-bold mb-4">Are you sure you want to logout?</h2>

        <div class="flex justify-center gap-4 mt-6">
            <button onclick="closeLogoutModal()" 
                class="px-6 py-2 bg-gray-300 rounded-full font-semibold hover:bg-gray-400">
                    No
            </button>

            <a href="/sm-logout" 
                class="px-6 py-2 bg-[#0e243a] text-yellow-400 rounded-full font-semibold hover:opacity-90">
                    Yes
            </a>
        </div>
    </div>
</div>