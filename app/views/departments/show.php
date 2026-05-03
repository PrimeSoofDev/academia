<?php
$pageTitle = htmlspecialchars($department['name']);
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Faculties', 'href' => '/faculties'],
    ['label' => $faculty['code'], 'href' => '/faculties/' . $faculty['id']],
    ['label' => $department['code']]
];
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-black text-2xl shadow-sm">
            <?= htmlspecialchars($department['code']) ?>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight"><?= htmlspecialchars($department['name']) ?></h2>
            <div class="flex items-center gap-3 mt-1 text-sm">
                <span class="text-brand-600 font-semibold"><a href="<?= url('/faculties/' . $faculty['id']) ?>"><?= htmlspecialchars($faculty['name']) ?></a></span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500">HOD: <span class="font-medium text-slate-700"><?= htmlspecialchars($hod['name'] ?? 'Not Assigned') ?></span></span>
            </div>
        </div>
    </div>
    
    <?php if (Auth::hasRole(['superadmin', 'vc', 'dean'])): ?>
    <div class="flex gap-2">
        <a href="<?= url('/departments/' . $department['id'] . '/edit') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">
            Edit Department
        </a>
        <a href="<?= url('/courses/create?department_id=' . $department['id']) ?>" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            Add Course
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Courses List -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 text-base">Courses offered in <?= htmlspecialchars($department['code']) ?></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Title</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Units</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lecturer</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($courses)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No courses are currently assigned to this department.</td></tr>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-sm font-bold"><?= htmlspecialchars($course['code']) ?></span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($course['title']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                <?= htmlspecialchars($course['level']) ?> Lvl
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-600"><?= (int)$course['credit_units'] ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($course['lecturer_name'] ?? 'Unassigned') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
