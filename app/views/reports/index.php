<?php
$pageTitle = 'Campus Live Reporting';
$breadcrumb = [['label' => 'Dashboard', 'href' => '/dashboard'], ['label' => 'Campus Reports']];
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Campus Live Reporting</h1>
            <p class="text-slate-500 text-sm">Real-time incident reporting for a safer and better campus environment.</p>
        </div>
        <?php if ($role === 'student'): ?>
            <button onclick="document.getElementById('reportModal').classList.remove('hidden')" 
                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Report an Incident
            </button>
        <?php endif; ?>
    </div>

    <?php if ($role !== 'student'): ?>
    <!-- Stats for Leaders -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <?php
        $pendingCount = count(array_filter($reports, fn($r) => $r['status'] === 'pending'));
        $highUrgency = count(array_filter($reports, fn($r) => in_array($r['urgency'], ['high', 'critical'])));
        ?>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Reports</p>
            <p class="text-2xl font-black text-slate-800"><?= count($reports) ?></p>
        </div>
        <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 shadow-sm">
            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Pending Review</p>
            <p class="text-2xl font-black text-amber-700"><?= $pendingCount ?></p>
        </div>
        <div class="bg-red-50 p-5 rounded-2xl border border-red-100 shadow-sm">
            <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">High/Critical</p>
            <p class="text-2xl font-black text-red-700"><?= $highUrgency ?></p>
        </div>
        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1">Resolved Today</p>
            <p class="text-2xl font-black text-emerald-700">0</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Reports List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 tracking-tight">Active Reports</h3>
            <div class="flex items-center gap-2">
                <select class="text-xs font-bold text-slate-500 bg-slate-50 border-none rounded-lg px-3 py-1.5 outline-none">
                    <option>All Categories</option>
                    <option>Security</option>
                    <option>Facilities</option>
                    <option>Academic</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Report Info</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category & Urgency</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-slate-400 text-sm font-medium">No reports found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="shrink-0">
                                            <?php if ($role !== 'student'): ?>
                                                <img src="<?= $r['student_image'] ? url($r['student_image']) : 'https://ui-avatars.com/api/?name='.urlencode($r['student_name']) ?>" 
                                                     class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                                            <?php else: ?>
                                                <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-800 truncate"><?= htmlspecialchars($r['title']) ?></p>
                                            <p class="text-[10px] text-slate-400 truncate mt-0.5"><?= $role !== 'student' ? 'By: '.htmlspecialchars($r['student_name']) : 'Location: '.htmlspecialchars($r['location'] ?: 'Not specified') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase"><?= htmlspecialchars($r['category']) ?></span>
                                        <?php 
                                            $urgencyClass = match($r['urgency']) {
                                                'critical' => 'text-red-600 bg-red-100',
                                                'high'     => 'text-orange-600 bg-orange-100',
                                                'medium'   => 'text-brand-600 bg-brand-100',
                                                default    => 'text-slate-600 bg-slate-100'
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black uppercase w-fit <?= $urgencyClass ?>">
                                            <?= $r['urgency'] ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $statusClass = match($r['status']) {
                                            'resolved'      => 'text-emerald-600 bg-emerald-100',
                                            'investigating' => 'text-blue-600 bg-blue-100',
                                            'dismissed'     => 'text-slate-400 bg-slate-100',
                                            default         => 'text-amber-600 bg-amber-100'
                                        };
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $statusClass ?>">
                                        <?= str_replace('_', ' ', $r['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-slate-600"><?= date('M j, Y', strtotime($r['created_at'])) ?></p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><?= date('H:i', strtotime($r['created_at'])) ?></p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="viewReport(<?= htmlspecialchars(json_encode($r)) ?>)" 
                                                class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ── Student: Report Modal ── -->
<div id="reportModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-8 pt-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Report Incident</h3>
                    <button onclick="document.getElementById('reportModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg>
                    </button>
                </div>
                <form action="<?= url('/reports/create') ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Title *</label>
                        <input type="text" name="title" required placeholder="Brief summary of the issue"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category *</label>
                            <select name="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                                <option value="security">Security</option>
                                <option value="facility">Facility/Infrastructure</option>
                                <option value="academic">Academic Issue</option>
                                <option value="medical">Medical Emergency</option>
                                <option value="harassment">Harassment/Bullying</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Urgency *</label>
                            <select name="urgency" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Location</label>
                        <input type="text" name="location" placeholder="e.g. Block B Room 402 or Main Gate"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Details *</label>
                        <textarea name="description" rows="4" required placeholder="Describe what is happening in detail..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 resize-none"></textarea>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')"
                                class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="flex-[2] px-4 py-3 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-500/20">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ── View/Update Modal (Common) ── -->
<div id="viewModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-8 pt-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight" id="vTitle">Report Details</h3>
                    <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <img id="vAvatar" src="" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                        <div>
                            <p class="text-sm font-bold text-slate-800" id="vStudent">Student Name</p>
                            <p class="text-xs text-slate-400" id="vMeta">Category | Date</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Description</h4>
                        <p class="text-sm text-slate-700 leading-relaxed" id="vDesc"></p>
                    </div>

                    <div id="adminActionSection" class="<?= $role === 'student' ? 'hidden' : '' ?>">
                        <form action="<?= url('/reports/update-status') ?>" method="POST" class="space-y-4 pt-4 border-t border-slate-100">
                            <input type="hidden" name="id" id="vId">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Update Status</label>
                                <select name="status" id="vStatus" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 transition-all outline-none text-sm text-slate-700">
                                    <option value="pending">Pending Review</option>
                                    <option value="investigating">Investigating</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="dismissed">Dismissed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Resolution Note (Shared with Student)</label>
                                <textarea name="resolution_note" id="vNote" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 transition-all outline-none text-sm text-slate-700 resize-none" placeholder="Explain what has been done..."></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-3 bg-brand-600 text-white text-sm font-bold rounded-xl hover:bg-brand-700 transition-colors shadow-lg">Update Report Status</button>
                        </form>
                    </div>

                    <div id="studentStatusSection" class="<?= $role !== 'student' ? 'hidden' : '' ?>">
                        <div class="p-4 rounded-2xl bg-brand-50 border border-brand-100">
                            <h4 class="text-[10px] font-bold text-brand-600 uppercase tracking-widest mb-2">Admin Response</h4>
                            <p class="text-sm text-slate-700" id="vNoteStudent">Waiting for response...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewReport(r) {
    document.getElementById('vId').value = r.id;
    document.getElementById('vTitle').textContent = r.title;
    document.getElementById('vDesc').textContent = r.description;
    document.getElementById('vStudent').textContent = r.student_name;
    document.getElementById('vMeta').textContent = `${r.category.toUpperCase()} | ${r.location || 'No location'}`;
    document.getElementById('vAvatar').src = r.student_image ? `<?= url('/') ?>/${r.student_image}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.student_name)}`;
    
    if (document.getElementById('vStatus')) document.getElementById('vStatus').value = r.status;
    if (document.getElementById('vNote')) document.getElementById('vNote').value = r.resolution_note || '';
    if (document.getElementById('vNoteStudent')) document.getElementById('vNoteStudent').textContent = r.resolution_note || 'This report is currently being reviewed by university authorities.';

    document.getElementById('viewModal').classList.remove('hidden');
}
</script>
