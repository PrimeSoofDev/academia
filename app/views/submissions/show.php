<?php
$pageTitle = 'Submission Details';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions', 'href' => '/submissions'],
    ['label' => 'Details']
];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Content & Form -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight"><?= str_replace('_', ' ', ucwords($submission['type'], '_')) ?></h3>
                    <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($submission['course_code']) ?> — <?= htmlspecialchars($submission['course_title']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                        <?= $submission['status'] === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' ?>">
                        <?= htmlspecialchars($submission['status']) ?>
                    </span>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Content View -->
                <?php if ($submission['file_path']): ?>
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-2xl shadow-sm">
                                📄
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">Submitted Document</p>
                                <p class="text-xs text-slate-500">File-based Academic Material</p>
                            </div>
                        </div>
                        <a href="<?= url($submission['file_path']) ?>" target="_blank" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download / View
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($submission['content']): ?>
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Electronic Content / Entry</h4>
                    <div class="p-6 rounded-2xl bg-slate-50 text-slate-700 text-sm border border-slate-100 font-mono whitespace-pre-wrap leading-relaxed shadow-inner">
                        <?= htmlspecialchars($submission['content']) ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!$submission['file_path'] && !$submission['content']): ?>
                    <div class="p-8 text-center text-slate-400 italic">No content or file provided.</div>
                <?php endif; ?>

                <?php if ($submission['remarks']): ?>
                <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100">
                    <h4 class="text-xs font-bold text-amber-800 uppercase tracking-widest mb-2">Reviewer Feedback</h4>
                    <p class="text-sm text-amber-700 font-medium"><?= htmlspecialchars($submission['remarks']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Approval Form (HOD/Dean) -->
        <?php if (in_array(Auth::role(), ['hod', 'dean', 'superadmin', 'vc']) && $submission['status'] !== 'approved'): ?>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-lg overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Review & Approval</h3>
            </div>
            <form action="<?= url("/submissions/{$submission['id']}/approve") ?>" method="POST" class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Review Remarks</label>
                    <textarea name="remarks" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700" placeholder="Enter approval comments or rejection reason..."></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" name="action" value="reject" class="flex-1 px-6 py-3.5 border border-red-200 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50 transition-colors">
                        Reject Submission
                    </button>
                    <button type="submit" name="action" value="approve" class="flex-[2] px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Approve & Sign Electronically
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Signature Tracking -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6 tracking-tight">Signature Ledger</h3>
            
            <div class="space-y-8 relative">
                <!-- Vertical Line -->
                <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-slate-100"></div>

                <!-- Lecturer -->
                <div class="relative flex gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center z-10 shrink-0 shadow-sm border border-brand-100">
                        <span class="text-xl">👨‍🏫</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lecturer</p>
                        <p class="font-bold text-slate-800 truncate"><?= htmlspecialchars($submission['lecturer_name']) ?></p>
                        <div class="mt-2 p-2 bg-slate-50 rounded-xl border border-slate-100">
                            <?php if ($submission['lecturer_sig']): ?>
                                <img src="<?= url($submission['lecturer_sig']) ?>" class="h-10 object-contain mix-blend-multiply">
                                <p class="text-[9px] text-slate-400 mt-1 text-center font-mono"><?= date('Y-m-d H:i:s', strtotime($submission['lecturer_signed_at'])) ?></p>
                            <?php else: ?>
                                <p class="text-[10px] text-red-400 italic py-2 text-center">No signature on file</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- HOD -->
                <div class="relative flex gap-6">
                    <div class="w-12 h-12 rounded-2xl <?= $submission['hod_signed_at'] ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-100' ?> flex items-center justify-center z-10 shrink-0 shadow-sm border">
                        <span class="text-xl"><?= $submission['hod_signed_at'] ? '✅' : '⏳' ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Head of Department</p>
                        <p class="font-bold text-slate-800 truncate"><?= $submission['hod_name'] ?? 'Pending Review' ?></p>
                        <?php if ($submission['hod_signed_at']): ?>
                        <div class="mt-2 p-2 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <?php if ($submission['hod_sig']): ?>
                                <img src="<?= url($submission['hod_sig']) ?>" class="h-10 object-contain mix-blend-multiply">
                                <p class="text-[9px] text-emerald-600 mt-1 text-center font-mono"><?= date('Y-m-d H:i:s', strtotime($submission['hod_signed_at'])) ?></p>
                            <?php else: ?>
                                <p class="text-[10px] text-emerald-600 font-bold py-2 text-center uppercase tracking-widest">Digitally Verified</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Dean -->
                <div class="relative flex gap-6">
                    <div class="w-12 h-12 rounded-2xl <?= $submission['dean_signed_at'] ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-100' ?> flex items-center justify-center z-10 shrink-0 shadow-sm border">
                        <span class="text-xl"><?= $submission['dean_signed_at'] ? '🏛️' : '⏳' ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Dean of Faculty</p>
                        <p class="font-bold text-slate-800 truncate"><?= $submission['dean_name'] ?? 'Pending Approval' ?></p>
                        <?php if ($submission['dean_signed_at']): ?>
                        <div class="mt-2 p-2 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <?php if ($submission['dean_sig']): ?>
                                <img src="<?= url($submission['dean_sig']) ?>" class="h-10 object-contain mix-blend-multiply">
                                <p class="text-[9px] text-emerald-600 mt-1 text-center font-mono"><?= date('Y-m-d H:i:s', strtotime($submission['dean_signed_at'])) ?></p>
                            <?php else: ?>
                                <p class="text-[10px] text-emerald-600 font-bold py-2 text-center uppercase tracking-widest">Digitally Verified</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-brand-900 rounded-3xl p-6 text-white shadow-xl shadow-brand-900/30">
            <h4 class="font-bold mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Security Audit
            </h4>
            <p class="text-xs text-brand-100 leading-relaxed">This document is electronically tracked and signed. All approvals are logged with timestamps and associated with verified user accounts.</p>
        </div>
    </div>
</div>
