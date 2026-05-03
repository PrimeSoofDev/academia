<?php
$pageTitle = 'Results Management';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Results']
];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Results Management</h2>
        <p class="text-slate-500 text-sm mt-1">Select a course to enter and publish student results.</p>
    </div>
    <?php if ($currentSess): ?>
    <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold">
        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
        Current: <?= htmlspecialchars($currentSess['name']) ?>
    </div>
    <?php endif; ?>
</div>

<!-- Filter Bar -->
<div class="relative mb-6 max-w-sm">
    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <input type="text" id="searchResults" onkeyup="tableSearch('searchResults', 'resultsTable')" placeholder="Search courses..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white" />
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table id="resultsTable" class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Enrolled</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($courses)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No courses assigned to you yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-xs font-bold mr-2"><?= htmlspecialchars($course['code']) ?></span>
                            <span class="font-semibold text-slate-800"><?= htmlspecialchars($course['title']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($course['department_name'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-100 rounded text-[10px] font-bold uppercase tracking-wider">
                                Lvl <?= htmlspecialchars($course['level']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-600"><?= number_format((int)$course['enrolled_count']) ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/results/courses/' . $course['id']) ?>" class="px-4 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-lg transition-colors inline-block">
                                Enter Results
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
