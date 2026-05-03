<?php
$pageTitle = 'Registry — Staff Records';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Registry', 'href' => '/registry'],
    ['label' => 'Staff Records']
];
?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 text-base">Academic & Non-Academic Staff</h3>
        <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            Add Staff Member
        </button>
    </div>
    
    <div class="p-4 border-b border-slate-100 bg-slate-50">
        <div class="relative max-w-md">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchStaff" onkeyup="tableSearch('searchStaff', 'staffTable')" placeholder="Search staff by name, email, or department..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white" />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="staffTable" class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Staff Member</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Placement</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Staff ID</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($staff)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No staff records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($staff as $s): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                    <?= strtoupper(substr($s['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-800 block"><?= htmlspecialchars($s['name']) ?></span>
                                    <span class="text-xs text-slate-500"><?= htmlspecialchars($s['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <?php if ($s['role'] === 'lecturer'): ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700">Lecturer</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-200 text-slate-700">Admin Staff</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3.5">
                            <?php if ($s['role'] === 'lecturer'): ?>
                                <span class="text-slate-600 font-medium"><?= htmlspecialchars($s['department_name'] ?? 'Unassigned Dept') ?></span>
                            <?php else: ?>
                                <span class="text-slate-600 font-medium"><?= htmlspecialchars($s['unit_name'] ?? 'Unassigned Unit') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded text-xs">
                                <?= htmlspecialchars($s['staff_id'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <button class="text-brand-500 hover:text-brand-700 text-sm font-medium transition-colors">Edit</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
