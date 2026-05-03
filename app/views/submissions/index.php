<?php
$pageTitle = 'Academic Submissions';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions']
];
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Academic Materials</h2>
        <p class="text-slate-500 text-sm mt-1">Review and approve exam questions, CA, and final results.</p>
    </div>
    
    <?php if (Auth::role() === 'lecturer'): ?>
    <a href="<?= url('/submissions/create') ?>" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-brand-500/20 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Submission
    </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Course & Type</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Submitted By</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Reviewers</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($submissions)): ?>
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">No submissions found.</td></tr>
                <?php else: ?>
                    <?php foreach ($submissions as $sub): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-mono text-xs font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded w-max mb-1"><?= htmlspecialchars($sub['course_code']) ?></span>
                                <span class="font-bold text-slate-800"><?= str_replace('_', ' ', ucwords($sub['type'], '_')) ?></span>
                                <span class="text-[10px] text-slate-400 mt-0.5"><?= date('M j, Y, g:i a', strtotime($sub['created_at'])) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    <?= substr($sub['lecturer_name'], 0, 1) ?>
                                </div>
                                <span class="text-slate-700 font-medium"><?= htmlspecialchars($sub['lecturer_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php 
                            $statusClass = match($sub['status']) {
                                'submitted' => 'bg-blue-100 text-blue-700',
                                'reviewed'  => 'bg-amber-100 text-amber-700',
                                'approved'  => 'bg-emerald-100 text-emerald-700',
                                'rejected'  => 'bg-red-100 text-red-700',
                                default     => 'bg-slate-100 text-slate-700'
                            };
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $statusClass ?>">
                                <?= htmlspecialchars($sub['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <?php if ($sub['hod_name']): ?>
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-600">
                                        <span class="text-emerald-500">✓</span> HOD: <?= htmlspecialchars($sub['hod_name']) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($sub['dean_name']): ?>
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-600">
                                        <span class="text-emerald-500">✓</span> Dean: <?= htmlspecialchars($sub['dean_name']) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$sub['hod_name'] && !$sub['dean_name']): ?>
                                    <span class="text-slate-400 text-xs italic">Awaiting review</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= url('/submissions/' . $sub['id']) ?>" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors">
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
