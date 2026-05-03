<?php
$pageTitle = 'Library Dashboard';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Library']
];
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-3xl">📚</div>
        <div>
            <p class="text-3xl font-extrabold text-blue-600"><?= number_format($stats['total_titles']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Book Titles</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center text-3xl">✅</div>
        <div>
            <p class="text-3xl font-extrabold text-emerald-600"><?= number_format($stats['available_copies']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Available Copies</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center text-3xl">📖</div>
        <div>
            <p class="text-3xl font-extrabold text-amber-600"><?= number_format($stats['active_loans']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Active Loans</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center text-3xl">⚠️</div>
        <div>
            <p class="text-3xl font-extrabold text-red-600"><?= number_format($stats['overdue_loans']) ?></p>
            <p class="text-slate-500 text-sm font-medium">Overdue Returns</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Library Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Library Actions</h3>
        </div>
        <div class="p-4 flex-1 flex flex-col gap-3">
            <a href="<?= url('/library/books') ?>" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-blue-50 transition-colors group">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📚</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-blue-700">Catalog Management</p>
                        <p class="text-xs text-slate-500">Add & manage books</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-amber-50 transition-colors group opacity-60 cursor-not-allowed">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📖</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-amber-700">Issue / Return</p>
                        <p class="text-xs text-slate-500">Manage book loans</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-slate-200 text-[10px] uppercase font-bold rounded text-slate-500">Soon</span>
            </a>
        </div>
    </div>

    <!-- Recent Loans -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Recent Book Loans</h3>
        </div>
        <div class="overflow-x-auto flex-1">
            <?php if (empty($stats['recent_loans'])): ?>
                <div class="p-8 text-center text-slate-500">No recent loans found.</div>
            <?php else: ?>
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Book</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Borrower</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Issued Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($stats['recent_loans'] as $loan): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5">
                                <span class="font-semibold text-slate-800 block truncate max-w-[200px]" title="<?= htmlspecialchars($loan['book_title']) ?>">
                                    <?= htmlspecialchars($loan['book_title']) ?>
                                </span>
                                <span class="text-[10px] text-slate-400">ISBN: <?= htmlspecialchars($loan['isbn'] ?? 'N/A') ?></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="text-slate-800 block"><?= htmlspecialchars($loan['user_name']) ?></span>
                                <span class="text-xs text-slate-500 capitalize"><?= htmlspecialchars($loan['user_role']) ?></span>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">
                                <?= date('d M, Y', strtotime($loan['issued_at'])) ?>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <?php if ($loan['status'] === 'active'): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">Active</span>
                                <?php elseif ($loan['status'] === 'returned'): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">Returned</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700">Overdue</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
