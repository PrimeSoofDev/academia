<?php
$pageTitle = htmlspecialchars($course['code']);
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Courses', 'href' => '/courses'],
    ['label' => $course['code']]
];
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-blue-600 font-black text-xl shadow-sm border border-blue-200 shrink-0 mt-1">
            <?= htmlspecialchars($course['code']) ?>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight"><?= htmlspecialchars($course['title']) ?></h2>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5 text-sm">
                <span class="text-brand-600 font-semibold"><a href="<?= url('/departments/' . $course['department_id']) ?>"><?= htmlspecialchars($course['department_name']) ?></a></span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 font-medium">Lvl <?= htmlspecialchars($course['level']) ?></span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 font-medium"><?= (int)$course['credit_units'] ?> Units</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 capitalize"><?= htmlspecialchars($course['semester']) ?> Semester</span>
            </div>
        </div>
    </div>
    
    <?php if (Auth::hasRole(['superadmin', 'vc', 'dean', 'hod'])): ?>
    <div class="flex gap-2 shrink-0">
        <a href="<?= url('/courses/' . $course['id'] . '/edit') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">
            Edit Course
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Course Info Card -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-bold text-slate-800 text-base mb-4">Course Details</h3>
        
        <div class="prose prose-sm text-slate-600 max-w-none mb-6">
            <?php if (!empty($course['description'])): ?>
                <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
            <?php else: ?>
                <p class="italic text-slate-400">No description provided for this course.</p>
            <?php endif; ?>
        </div>

        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Lecturer in Charge</h4>
            <?php if ($course['lecturer_name']): ?>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                        <?= substr($course['lecturer_name'], 0, 1) ?>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800"><?= htmlspecialchars($course['lecturer_name']) ?></p>
                        <p class="text-xs text-slate-500"><?= htmlspecialchars($course['lecturer_email']) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-sm font-medium text-amber-600">No lecturer currently assigned.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Stats Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-100 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 text-2xl mb-3">
                👨‍🎓
            </div>
            <h3 class="text-3xl font-black text-slate-800"><?= number_format(count($students)) ?></h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Enrolled Students</p>
        </div>
        <div class="p-4 bg-slate-50 flex-1 flex flex-col justify-center">
            <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-slate-500">Status</span>
                <?php if ($course['status'] === 'active'): ?>
                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-emerald-100 text-emerald-700">Active</span>
                <?php else: ?>
                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-slate-200 text-slate-600">Inactive</span>
                <?php endif; ?>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500">Faculty</span>
                <span class="font-medium text-slate-800 line-clamp-1 text-right max-w-[150px] title="<?= htmlspecialchars($course['faculty_name']) ?>"><?= htmlspecialchars($course['faculty_name']) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Enrolled Students Table -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 text-base">Class Roster</h3>
        
        <div class="relative max-w-xs w-full ml-4">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchRoster" onkeyup="tableSearch('searchRoster', 'rosterTable')" placeholder="Search student..." class="w-full pl-9 pr-4 py-1.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-xs transition-all bg-slate-50" />
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table id="rosterTable" class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Matric Number</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Enrollment Date</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Grade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($students)): ?>
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No students are currently enrolled in this course.</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5">
                            <span class="font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded text-xs font-bold tracking-wider">
                                <?= htmlspecialchars($student['matric_number'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="font-semibold text-slate-800 block"><?= htmlspecialchars($student['name']) ?></span>
                            <span class="text-xs text-slate-500"><?= htmlspecialchars($student['email']) ?></span>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600 text-xs">
                            <?= date('d M Y', strtotime($student['enrolled_at'])) ?>
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            <?php if ($student['grade']): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-800 text-white">
                                    <?= htmlspecialchars($student['grade']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-slate-400 italic text-xs">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
