<?php
$pageTitle = 'Bursary Dashboard';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Bursary']
];
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-3xl">💰</div>
        <div>
            <p class="text-3xl font-extrabold text-emerald-600">₦<?= number_format($stats['total_revenue']) ?></p>
            <p class="text-slate-500 text-sm font-medium mt-1">Total Realized Revenue</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-3xl">⏳</div>
        <div>
            <p class="text-3xl font-extrabold text-amber-600">₦<?= number_format($stats['pending_revenue']) ?></p>
            <p class="text-slate-500 text-sm font-medium mt-1">Pending Fees (Unpaid)</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-3xl">🧾</div>
        <div>
            <p class="text-3xl font-extrabold text-blue-600"><?= number_format($stats['paid_count']) ?></p>
            <p class="text-slate-500 text-sm font-medium mt-1">Successful Transactions</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Bursary Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Financial Actions</h3>
        </div>
        <div class="p-4 flex-1 flex flex-col gap-3">
            <a href="<?= url('/bursary/payments') ?>" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-emerald-50 transition-colors group">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💸</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-emerald-700">Fee Payments</p>
                        <p class="text-xs text-slate-500">Record and verify payments</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-blue-50 transition-colors group opacity-60 cursor-not-allowed" title="Coming soon">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📜</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-blue-700">Generate Invoices</p>
                        <p class="text-xs text-slate-500">Bulk create student fee invoices</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-slate-200 text-[10px] uppercase font-bold rounded text-slate-500">Soon</span>
            </a>
            
            <a href="#" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-violet-50 transition-colors group opacity-60 cursor-not-allowed" title="Coming soon">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📈</span>
                    <div>
                        <p class="font-semibold text-slate-800 group-hover:text-violet-700">Financial Reports</p>
                        <p class="text-xs text-slate-500">Export revenue data & analytics</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-slate-200 text-[10px] uppercase font-bold rounded text-slate-500">Soon</span>
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Recent Transactions</h3>
            <a href="<?= url('/bursary/payments') ?>" class="text-brand-500 hover:text-brand-700 text-sm font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto flex-1">
            <?php if (empty($stats['recent_payments'])): ?>
                <div class="p-8 text-center text-slate-500">No recent transactions found.</div>
            <?php else: ?>
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($stats['recent_payments'] as $payment): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5">
                                <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded text-xs">
                                    <?= htmlspecialchars($payment['reference']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="font-semibold text-slate-800 block"><?= htmlspecialchars($payment['student_name']) ?></span>
                                <span class="text-xs text-slate-500"><?= htmlspecialchars($payment['fee_type']) ?></span>
                            </td>
                            <td class="px-6 py-3.5 text-right font-bold text-slate-800">
                                ₦<?= number_format($payment['amount'], 2) ?>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <?php if ($payment['status'] === 'paid'): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">Paid</span>
                                <?php elseif ($payment['status'] === 'pending'): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">Pending</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700"><?= htmlspecialchars($payment['status']) ?></span>
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
