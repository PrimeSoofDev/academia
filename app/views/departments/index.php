<?php
$pageTitle = 'Departments';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Faculties', 'href' => '/faculties'],
    ['label' => 'Departments']
];
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Departments Directory</h2>
        <p class="text-slate-500 text-sm mt-1">View all academic departments across faculties.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="relative max-w-xs">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchDepts" onkeyup="tableSearch('searchDepts', 'deptsTable')" placeholder="Search departments..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white" />
        </div>
        
        <?php if (Auth::hasRole(['superadmin', 'vc', 'dean'])): ?>
        <a href="<?= url('/departments/create') ?>" class="px-5 py-2 whitespace-nowrap bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Department
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table id="deptsTable" class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Faculty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Head of Dept</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Courses</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Students</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($departments)): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">No departments have been added yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($departments as $dept): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 block text-base"><?= htmlspecialchars($dept['name']) ?></span>
                            <span class="font-mono text-slate-500 text-xs font-semibold"><?= htmlspecialchars($dept['code']) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-600 font-medium"><?= htmlspecialchars($dept['faculty_name'] ?? 'Unknown') ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-700"><?= htmlspecialchars($dept['hod_name'] ?? 'Not Assigned') ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-semibold text-slate-700"><?= number_format((int)$dept['course_count']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-semibold text-slate-700"><?= number_format((int)$dept['student_count']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/departments/' . $dept['id']) ?>" class="text-brand-600 hover:text-brand-800 text-sm font-medium inline-flex items-center gap-1 transition-colors">
                                View Details
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
