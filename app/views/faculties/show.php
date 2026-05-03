<?php
$pageTitle = htmlspecialchars($faculty['name']);
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Faculties', 'href' => '/faculties'],
    ['label' => $faculty['code']]
];
?>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-100 to-indigo-100 flex items-center justify-center text-brand-600 font-black text-2xl shadow-sm">
            <?= htmlspecialchars($faculty['code']) ?>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight"><?= htmlspecialchars($faculty['name']) ?></h2>
            <p class="text-slate-500 text-sm mt-1">Dean: <span class="font-medium text-slate-700"><?= htmlspecialchars($faculty['dean_name'] ?? 'Not Assigned') ?></span></p>
        </div>
    </div>
    
    <?php if (Auth::hasRole(['superadmin', 'vc'])): ?>
    <div class="flex gap-2">
        <a href="<?= url('/faculties/' . $faculty['id'] . '/edit') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">
            Edit Faculty
        </a>
        <a href="<?= url('/departments/create?faculty_id=' . $faculty['id']) ?>" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            Add Department
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Departments List -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 text-base">Departments in <?= htmlspecialchars($faculty['code']) ?></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Department Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Head of Department</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Students</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($faculty['departments'])): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No departments found in this faculty.</td></tr>
                <?php else: ?>
                    <?php foreach ($faculty['departments'] as $dept): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($dept['name']) ?></td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded text-xs font-bold"><?= htmlspecialchars($dept['code']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($dept['hod_name'] ?? 'Not Assigned') ?></td>
                        <td class="px-6 py-4 text-right">
                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <?= number_format((int)$dept['student_count']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/departments/' . $dept['id']) ?>" class="text-brand-600 hover:text-brand-800 text-sm font-medium transition-colors">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
