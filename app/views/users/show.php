<?php
$pageTitle = htmlspecialchars($user['name']);
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Users', 'href' => '/users'],
    ['label' => htmlspecialchars($user['name'])]
];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Profile Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-8 flex flex-col items-center text-center border-b border-slate-100 relative">
            
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-brand-100 to-indigo-100 flex items-center justify-center text-brand-700 font-bold text-3xl shadow-sm mb-4">
                <?= substr($user['name'], 0, 1) ?>
            </div>
            
            <h2 class="text-xl font-extrabold text-slate-800"><?= htmlspecialchars($user['name']) ?></h2>
            <p class="text-slate-500 text-sm mt-1"><?= htmlspecialchars($user['email']) ?></p>
            
            <div class="mt-4 inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded text-xs font-bold uppercase tracking-wider">
                <?= htmlspecialchars($user['role']) ?>
            </div>
        </div>
        
        <div class="p-6 bg-slate-50/50 flex-1">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Account Status</h3>
            
            <form action="<?= url('/users/' . $user['id'] . '/status') ?>" method="POST" class="flex gap-2">
                <select name="status" class="flex-1 px-3 py-2 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm bg-white font-medium <?= $user['status'] === 'active' ? 'text-emerald-600' : ($user['status'] === 'suspended' ? 'text-red-600' : 'text-amber-600') ?>">
                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    Update
                </button>
            </form>

            <div class="mt-8">
                <a href="<?= url('/users/' . $user['id'] . '/edit') ?>" class="w-full px-4 py-2 bg-brand-50 text-brand-700 hover:bg-brand-100 text-sm font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Full Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Academic Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Identification Number</p>
                    <p class="font-mono font-medium text-slate-800 text-lg">
                        <?= htmlspecialchars($user['matric_number'] ?? $user['staff_id'] ?? 'Not Assigned') ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Faculty</p>
                    <p class="font-medium text-slate-800">
                        <?= htmlspecialchars($user['faculty_name'] ?? 'N/A') ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Department</p>
                    <p class="font-medium text-slate-800">
                        <?= htmlspecialchars($user['department_name'] ?? 'N/A') ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Administrative Unit</p>
                    <p class="font-medium text-slate-800">
                        <?= htmlspecialchars($user['unit_name'] ?? 'N/A') ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Personal Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Phone Number</p>
                    <p class="font-medium text-slate-800">
                        <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Gender</p>
                    <p class="font-medium text-slate-800 capitalize">
                        <?= htmlspecialchars($user['gender'] ?? 'Not specified') ?>
                    </p>
                </div>
                
                <div class="md:col-span-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Date Joined</p>
                    <p class="font-medium text-slate-800">
                        <?= date('F j, Y, g:i a', strtotime($user['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</div>
