<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Archived NGO Accounts</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
          rel="stylesheet">

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


        <?php echo $__env->make('components.header', ['title' => 'NGO Profile'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        

        <div class="bg-[#0e243a] p-4 rounded-2xl flex gap-4 mb-10 flex-wrap">

            <a href="/sm-ngos"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Details
            </a>


            <a href="/ngo-members"
               class="bg-[#f2c94c] text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Members
            </a>


            <a href="/ngo-accounts"
               class="bg-gray-200 text-[#0e243a] px-6 py-2 rounded-full font-semibold">
                Accounts
            </a>

        </div>


        

        <div class="bg-white rounded-2xl overflow-hidden">


            

            <div class="bg-gray-100 p-4 flex items-center gap-3 flex-wrap">


                

                <div class="mr-auto">

                    <h1 class="text-xl font-bold text-[#0e243a]">
                        Archived Accounts
                    </h1>

                </div>


                

                <form action="<?php echo e(route('ngo-accounts.archived')); ?>"
                      method="GET"
                      class="flex items-center gap-2 text-sm font-semibold text-gray-600">

                    <label for="role">
                        Filter:
                    </label>


                    <select name="role"
                            id="role"
                            onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-[#0e243a] bg-white">

                        <option value="">
                            Roles
                        </option>


                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($value); ?>"
                                <?php if(request('role') == $value): echo 'selected'; endif; ?>>

                                <?php echo e($label); ?>


                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </form>


                

                <form action="<?php echo e(route('ngo-accounts.archived')); ?>"
                      method="GET"
                      id="archivedSearchForm">

                    <?php if(request('role')): ?>

                        <input type="hidden"
                               name="role"
                               value="<?php echo e(request('role')); ?>">

                    <?php endif; ?>


                    <input type="text"
                           name="search"
                           id="archivedSearchInput"
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Search..."
                           autocomplete="off"
                           class="w-56 border-[1.5px] border-indigo-300 rounded-lg px-3.5 py-2 text-sm outline-none focus:border-indigo-400">

                </form>


                

                <a href="<?php echo e(route('ngo-accounts')); ?>"
                   class="bg-[#f2c94c] text-[#0e243a] px-5 py-2.5 rounded-lg font-semibold text-sm whitespace-nowrap">

                    Back to Accounts

                </a>


            </div>


            

            <?php if(session('success')): ?>

                <div class="mx-5 mt-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">

                    <?php echo e(session('success')); ?>


                </div>

            <?php endif; ?>


            

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">


                    <thead>

                        <tr class="border-b border-gray-200">

                            <th class="w-14 text-left px-5 py-4 text-sm text-[#0e243a]">
                                #
                            </th>


                            <th class="text-left px-5 py-4 text-sm text-[#0e243a]">
                                Name
                            </th>


                            <th class="text-left px-5 py-4 text-sm text-[#0e243a]">
                                Role
                            </th>


                            <th class="text-left px-5 py-4 text-sm text-[#0e243a]">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr class="border-b border-gray-200 last:border-0">


                                

                                <td class="px-5 py-3.5 text-sm text-gray-500">

                                    <?php echo e($index + 1); ?>


                                </td>


                                

                                <td class="px-5 py-3.5 text-sm text-[#0e243a]">

                                    <?php echo e($account->first_name); ?>

                                    <?php echo e($account->last_name); ?>


                                </td>


                                

                                <td class="px-5 py-3.5 text-sm text-[#0e243a]">

                                    <?php echo e($account->role_label); ?>


                                </td>


                                

                                <td class="px-5 py-3.5">

                                    <form action="<?php echo e(route('ngo-accounts.restore', $account->id)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('Restore this account?')">

                                        <?php echo csrf_field(); ?>

                                        <?php echo method_field('PATCH'); ?>


                                        <button type="submit"
                                                class="bg-[#f2c94c] text-[#0e243a] px-4 py-2 rounded-lg font-semibold text-sm">

                                            Restore

                                        </button>

                                    </form>

                                </td>

                            </tr>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>

                                <td colspan="4"
                                    class="text-center px-5 py-10 text-sm text-gray-500">

                                    No archived accounts found.

                                </td>

                            </tr>

                        <?php endif; ?>


                    </tbody>

                </table>

            </div>


        </div>

    </div>

</div>


<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<script>

function openLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.remove('hidden');

    document
        .getElementById('logoutModal')
        .classList.add('flex');

}


function closeLogoutModal() {

    document
        .getElementById('logoutModal')
        .classList.add('hidden');

}

(function () {

    const input =
        document.getElementById('archivedSearchInput');

    const form =
        document.getElementById('archivedSearchForm');

    let timer = null;


    if (!input || !form) {
        return;
    }


    input.addEventListener('input', function () {

        clearTimeout(timer);


        timer = setTimeout(function () {

            form.submit();

        }, 300);

    });


    if (input.value.length > 0) {

        input.focus();

        input.setSelectionRange(
            input.value.length,
            input.value.length
        );

    }

})();

</script>


</body>

</html>

<?php /**PATH C:\ProjectSuhay\MEt.A-Project-SUHAY\resources\views/ngo_accounts_archived.blade.php ENDPATH**/ ?>