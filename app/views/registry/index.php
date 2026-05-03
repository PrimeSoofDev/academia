<?php
$pageTitle = 'Registry Dashboard';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Registry']
];
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-3xl">🎓</div>
        <div>
            <p class="text-3xl font-extrabold text-blue-600"><?= number_format($stats['total_students']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Total Students</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-violet-50 flex items-center justify-center text-3xl">👨‍🏫</div>
        <div>
            <p class="text-3xl font-extrabold text-violet-600"><?= number_format($stats['total_lecturers']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Total Lecturers</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center text-3xl">🏢</div>
        <div>
            <p class="text-3xl font-extrabold text-emerald-600"><?= number_format($stats['total_staff']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Total Staff</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center text-3xl">📅</div>
        <div>
            <p class="text-xl font-extrabold text-amber-600 truncate max-w-[120px]" title="<?= htmlspecialchars($stats['current_session']) ?>">
                <?= htmlspecialchars($stats['current_session']) ?>
            </p>
            <p class="text-slate-500 text-sm font-medium">Current Session</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Registry Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Registry Actions</h3>
        </div>
        <div class="p-4 flex-1 flex flex-col gap-3">
            <a href="<?= url('/registry/students') ?>" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-blue-50 transition-colors group">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎓</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-blue-700">Manage Students</p>
                        <p class="text-xs text-slate-500">Admissions, records, profiles</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="<?= url('/registry/staff') ?>" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-violet-50 transition-colors group">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">👨‍💼</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-violet-700">Manage Staff</p>
                        <p class="text-xs text-slate-500">Academic & Non-academic staff</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="<?= url('/registry/sessions') ?>" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-amber-50 transition-colors group">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📅</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-amber-700">Academic Sessions</p>
                        <p class="text-xs text-slate-500">Create & manage semesters</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- Recent Admissions/Users -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Recently Added Users</h3>
        </div>
        <div class="overflow-x-auto flex-1">
            <?php if (empty($stats['recent_users'])): ?>
                <div class="p-8 text-center text-slate-500">No users found.</div>
            <?php else: ?>
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($stats['recent_users'] as $user): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <span class="font-semibold text-slate-700"><?= htmlspecialchars($user['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right text-slate-500 text-xs">
                                <?= date('M j, Y', strtotime($user['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
