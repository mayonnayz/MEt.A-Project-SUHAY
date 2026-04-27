<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">
<div class="flex">

    <?php echo $__env->make('components.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 p-8">
       <?php echo $__env->make('components.header', ['title' => 'Events'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <div class="bg-white rounded-3xl border-[10px] border-[#0e243a] p-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            
            <div class="bg-white border border-gray-300 rounded-xl px-6 py-5 flex justify-between items-center">
                <p class="text-gray-600 text-sm">
                    Total<br>Applications
                </p>
                <h2 class="text-3xl font-bold text-[#0e243a]">
                    <?php echo e($totalApplications); ?>

                </h2>
            </div>

            
            <div class="bg-white border border-gray-300 rounded-xl px-6 py-5 flex justify-between items-center">
                <p class="text-gray-600 text-sm">
                    Approved<br>Applications
                </p>
                <h2 class="text-3xl font-bold text-green-500">
                    <?php echo e($approved); ?>

                </h2>
            </div>

            
            <div class="bg-white border border-gray-300 rounded-xl px-6 py-5 flex justify-between items-center">
                <p class="text-gray-600 text-sm">
                    Pending<br>Applications
                </p>
                <h2 class="text-3xl font-bold text-yellow-500">
                    <?php echo e($pending); ?>

                </h2>
            </div>

        </div>

            
            <div class="border-t border-gray-400 w-2/3 mx-auto mb-6"></div>

            
            <div class="bg-white border border-gray-300 rounded-2xl p-8 max-w-2xl mx-auto">

                <?php if(session('error')): ?>
                    <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                
                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 rounded-full border-4 border-[#0e243a] flex items-center justify-center mb-2">
                        <span class="text-xl">👤</span>
                    </div>
                    <h2 class="font-bold text-[#0e243a]">ACCOUNT DETAILS</h2>
                </div>

                
                <form method="POST" action="/volunteer/update-account">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-2 text-sm">

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            First Name
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <?php echo e(data_get($user, 'first_name')); ?>

                        </div>

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            Last Name
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <?php echo e(data_get($user, 'last_name')); ?>

                        </div>

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            Email
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <input type="email"
                                name="email"
                                value="<?php echo e(data_get($user, 'email')); ?>"
                                class="w-full bg-transparent outline-none">
                        </div>

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            Password
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <input type="password"
                                name="password"
                                placeholder="*****"
                                class="w-full bg-transparent outline-none">
                        </div>

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            Contact Number
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <input type="text"
                                name="contact_number"
                                value="<?php echo e(data_get($user, 'contact_number')); ?>"
                                maxlength="11"
                                pattern="[0-9]{11}"
                                class="w-full bg-transparent outline-none">
                        </div>

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            Address
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <input type="text"
                                name="address"
                                value="<?php echo e(data_get($user, 'address')); ?>"
                                class="w-full bg-transparent outline-none">
                        </div>

                        
                        <div class="bg-[#0e243a] text-white px-3 py-2 border">
                            Birth Date
                        </div>
                        <div class="px-3 py-2 border bg-gray-50">
                            <?php echo e(data_get($user, 'birth_date')); ?>

                        </div>

                    </div>

                            
                        <div class="flex justify-center gap-4 mt-6">
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded-full hover:bg-yellow-600">
                                Switch Account
                            </button>

                            <button type="submit"
                                class="bg-green-400 text-white px-4 py-2 rounded-full hover:bg-green-500">
                                Save Changes
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>


<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>

</body>
</html><?php /**PATH C:\sysands\MEt.A-Project-SUHAY-elisha\resources\views/Volunteers/dashboard.blade.php ENDPATH**/ ?>