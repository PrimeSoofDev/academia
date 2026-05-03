<?php
$pageTitle = 'Registry — Academic Sessions';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Registry', 'href' => '/registry'],
    ['label' => 'Academic Sessions']
];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: List of sessions -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">All Sessions</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Session Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($sessions)): ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No sessions configured.</td></tr>
                    <?php else: ?>
                        <?php foreach ($sessions as $session): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 text-base"><?= htmlspecialchars($session['name']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <?= date('M Y', strtotime($session['start_date'])) ?> &mdash; <?= date('M Y', strtotime($session['end_date'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($session['is_current']): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">Current</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">Past/Future</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if (!$session['is_current']): ?>
                                    <form action="<?= url('/registry/sessions/set-current') ?>" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $session['id'] ?>">
                                        <button type="submit" data-confirm="Set this as the current active academic session?" class="text-brand-600 hover:text-brand-800 text-sm font-medium transition-colors">Set Current</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-emerald-500 text-sm font-medium">Active</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Add Session Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-max">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Create New Session</h3>
        </div>
        <div class="p-6">
            <form action="<?= url('/registry/sessions') ?>" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Session Name</label>
                    <input type="text" name="name" id="name" placeholder="e.g. 2025/2026" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all">
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-xl transition-colors shadow-sm">
                        Create Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
