<?php
$pageTitle = 'User Management';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Users']
];
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">University Directory</h2>
        <p class="text-slate-500 text-sm mt-1">Manage staff, students, and administration accounts.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-64">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchUsers" onkeyup="tableSearch('searchUsers', 'usersTable')" placeholder="Search names, emails..." class="w-full pl-9 pr-4 py-2.5 sm:py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white" />
        </div>
        
        <a href="<?= url('/users/create') ?>" class="w-full sm:w-auto px-5 py-2.5 sm:py-2 whitespace-nowrap bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add New User
        </a>
    </div>
</div>

<!-- Role Filters -->
<div class="flex flex-wrap items-center gap-2 mb-6">
    <a href="<?= url('/users') ?>" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors <?= empty($currentRole) ? 'bg-slate-800 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
        All Users
    </a>
    
    <?php
    $filters = [
        'student' => 'Students',
        'lecturer'=> 'Lecturers',
        'hod'     => 'HODs',
        'dean'    => 'Deans',
        'staff'   => 'Admin Staff',
        'vc'      => 'Vice Chancellor',
        'superadmin'=> 'System Admins'
    ];
    foreach ($filters as $key => $label): 
        $count = $roleCounts[$key] ?? 0;
    ?>
    <a href="<?= url("/users?role={$key}") ?>" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors flex items-center gap-2 <?= ($currentRole === $key) ? 'bg-brand-600 text-white shadow-sm border border-transparent' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
        <?= $label ?>
        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold <?= ($currentRole === $key) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' ?>"><?= $count ?></span>
    </a>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table id="usersTable" class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User Profile</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role & ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Placement</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($users)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No users found for the selected criteria.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-slate-50 transition-colors <?= $user['status'] !== 'active' ? 'opacity-70' : '' ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-100 to-indigo-100 flex items-center justify-center text-brand-700 font-bold uppercase shrink-0">
                                    <?= substr($user['name'], 0, 1) ?>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 block"><?= htmlspecialchars($user['name']) ?></span>
                                    <span class="text-xs text-slate-500"><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 mb-1 inline-block">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                            <div class="text-xs font-mono font-medium text-slate-500">
                                <?= htmlspecialchars($user['matric_number'] ?? $user['staff_id'] ?? 'No ID Set') ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-sm">
                            <?php if ($user['department_name']): ?>
                                <span class="font-medium"><?= htmlspecialchars($user['department_name']) ?></span>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars($user['faculty_name']) ?></div>
                            <?php elseif ($user['unit_name']): ?>
                                <span class="font-medium"><?= htmlspecialchars($user['unit_name']) ?> Unit</span>
                            <?php else: ?>
                                <span class="italic text-slate-400 text-xs">Unassigned</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($user['status'] === 'active'): ?>
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Active</span>
                            <?php elseif ($user['status'] === 'suspended'): ?>
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Suspended</span>
                            <?php else: ?>
                                <span class="bg-slate-200 text-slate-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"><?= htmlspecialchars($user['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/users/' . $user['id']) ?>" class="text-brand-600 hover:text-brand-800 text-sm font-medium inline-flex items-center gap-1 transition-colors">
                                Manage
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
