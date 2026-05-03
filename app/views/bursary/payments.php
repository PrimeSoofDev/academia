<?php
$pageTitle = 'Bursary — Payments';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Bursary', 'href' => '/bursary'],
    ['label' => 'Fee Payments']
];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Payment List -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Transaction History</h3>
        </div>
        
        <div class="p-4 border-b border-slate-100 bg-slate-50">
            <div class="relative w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchPayments" onkeyup="tableSearch('searchPayments', 'paymentsTable')" placeholder="Search reference, student name, or fee type..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-sm transition-all bg-white" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="paymentsTable" class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Reference / Session</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fee Type</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No payment records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $payment): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5">
                                <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded text-xs block mb-1 w-max">
                                    <?= htmlspecialchars($payment['reference']) ?>
                                </span>
                                <span class="text-[10px] uppercase font-bold text-slate-400"><?= htmlspecialchars($payment['session_name'] ?? 'N/A') ?></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="font-semibold text-slate-800 block"><?= htmlspecialchars($payment['student_name']) ?></span>
                                <span class="text-xs text-slate-500"><?= htmlspecialchars($payment['matric_number'] ?? 'No Matric') ?></span>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600 font-medium">
                                <?= htmlspecialchars(ucfirst($payment['fee_type'])) ?>
                            </td>
                            <td class="px-6 py-3.5 text-right font-bold text-slate-800">
                                ₦<?= number_format($payment['amount'], 2) ?>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <?php if ($payment['status'] === 'paid'): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">Paid</span>
                                    <div class="text-[10px] text-slate-400 mt-1"><?= date('d M, y', strtotime($payment['paid_at'])) ?></div>
                                <?php elseif ($payment['status'] === 'pending'): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">Pending</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700"><?= htmlspecialchars($payment['status']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Record Payment Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-max">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Record Offline Payment</h3>
            <p class="text-xs text-slate-500 mt-1">Manually log a bank teller or cash receipt.</p>
        </div>
        <div class="p-6 bg-slate-50/50">
            <form action="<?= url('/bursary/record') ?>" method="POST" class="space-y-4">
                
                <!-- Session -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Academic Session</label>
                    <input type="text" value="<?= htmlspecialchars($currentSession['name'] ?? 'Not Set') ?>" readonly
                           class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm font-medium cursor-not-allowed">
                    <input type="hidden" name="session_id" value="<?= $currentSession['id'] ?? '' ?>">
                </div>

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-slate-700 mb-1">Select Student</label>
                    <select name="student_id" id="student_id" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-sm transition-all bg-white">
                        <option value="">-- Choose a student --</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>">
                                <?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['matric_number'] ?? $student['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Fee Type -->
                <div>
                    <label for="fee_type" class="block text-sm font-medium text-slate-700 mb-1">Fee Category</label>
                    <select name="fee_type" id="fee_type" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-sm transition-all bg-white">
                        <option value="tuition">Tuition Fee</option>
                        <option value="accommodation">Accommodation / Hostel</option>
                        <option value="acceptance">Acceptance Fee</option>
                        <option value="library">Library Fine</option>
                        <option value="other">Other Adjustments</option>
                    </select>
                </div>
                
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-1">Amount (₦)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">₦</span>
                        <input type="number" name="amount" id="amount" step="0.01" min="1" required placeholder="0.00"
                               class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-sm transition-all bg-white font-mono">
                    </div>
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Payment Status</label>
                    <select name="status" id="status" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-sm transition-all bg-white">
                        <option value="paid">Paid & Verified</option>
                        <option value="pending">Pending (Invoice)</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Record Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
