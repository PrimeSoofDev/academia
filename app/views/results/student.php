<?php
$pageTitle = 'My Results — ' . ($sessions[0]['name'] ?? '');
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'My Results']
];

// Summarise totals
$totalUnits  = 0;
$earnedPoints = 0.0;
foreach ($results as $r) {
    $totalUnits   += (int)$r['credit_units'];
    $earnedPoints += ($r['grade_point'] ?? 0) * $r['credit_units'];
}
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">My Academic Results</h2>
        <p class="text-slate-500 text-sm mt-1">View your examination results and semester transcript.</p>
    </div>
    
    <!-- Session picker -->
    <form method="GET" action="<?= url('/results') ?>" class="flex items-center gap-2">
        <label class="text-sm font-medium text-slate-600 whitespace-nowrap">Session:</label>
        <select name="session_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 focus:border-brand-500 text-sm bg-white font-medium">
            <?php foreach ($sessions as $sess): ?>
                <option value="<?= $sess['id'] ?>" <?= $sessionId == $sess['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sess['name']) ?> <?= $sess['is_current'] ? '(Current)' : '' ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($sessionId && !empty($results)): ?>
<!-- GPA Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php
    $letterGrade = 'N/A';
    if     ($gpa >= 4.5) $letterGrade = 'FIRST CLASS';
    elseif ($gpa >= 3.5) $letterGrade = '2nd CLASS (UPPER)';
    elseif ($gpa >= 2.5) $letterGrade = '2nd CLASS (LOWER)';
    elseif ($gpa >= 1.5) $letterGrade = 'THIRD CLASS';
    elseif ($gpa >= 1.0) $letterGrade = 'PASS';
    else                 $letterGrade = 'FAIL';
    ?>
    <div class="bg-gradient-to-br from-brand-600 to-indigo-600 rounded-2xl p-5 col-span-2 text-white shadow-lg">
        <p class="text-brand-100 text-xs font-bold uppercase tracking-wider mb-1">Session GPA</p>
        <p class="text-5xl font-black tracking-tight"><?= number_format($gpa, 2) ?></p>
        <p class="text-brand-200 text-sm font-semibold mt-2">— <?= $letterGrade ?></p>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Courses Taken</p>
        <p class="text-3xl font-black text-slate-800"><?= count($results) ?></p>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Credit Units</p>
        <p class="text-3xl font-black text-slate-800"><?= $totalUnits ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Results Table -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 text-base">Result Sheet</h3>
        <?php if (!empty($results)): ?>
        <button onclick="window.print()" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Transcript
        </button>
        <?php endif; ?>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Title</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Units</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">CA (40)</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Exam (60)</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Grade</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Points</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($results)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center">
                            <?php if (!$sessionId): ?>
                                <span class="text-slate-400 text-sm">Please select a session to view your results.</span>
                            <?php else: ?>
                                <span class="text-2xl block mb-3">📋</span>
                                <span class="text-slate-500 text-sm">No published results found for this session.</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($results as $r): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5">
                            <span class="font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-xs font-bold"><?= htmlspecialchars($r['course_code']) ?></span>
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-800"><?= htmlspecialchars($r['course_title']) ?></td>
                        <td class="px-6 py-3.5 text-center font-bold text-slate-600"><?= (int)$r['credit_units'] ?></td>
                        <td class="px-6 py-3.5 text-center text-slate-600"><?= number_format($r['ca_score'], 1) ?></td>
                        <td class="px-6 py-3.5 text-center text-slate-600"><?= number_format($r['exam_score'], 1) ?></td>
                        <td class="px-6 py-3.5 text-center font-bold text-slate-800"><?= number_format($r['total_score'], 1) ?></td>
                        <td class="px-6 py-3.5 text-center">
                            <?php
                            $grade = $r['grade'];
                            $gradeColor = match($grade) {
                                'A' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'B' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'C' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'D' => 'bg-orange-100 text-orange-700 border-orange-200',
                                'E' => 'bg-rose-100 text-rose-700 border-rose-200',
                                default => 'bg-red-200 text-red-800 border-red-200'
                            };
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-xs font-black border <?= $gradeColor ?>">
                                <?= htmlspecialchars($grade) ?>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-center font-bold text-slate-700"><?= number_format($r['grade_point'], 1) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Totals Row -->
                    <tr class="bg-slate-50 border-t-2 border-slate-200">
                        <td colspan="2" class="px-6 py-4 text-right font-bold text-slate-600 uppercase text-xs tracking-wider">Totals / GPA</td>
                        <td class="px-6 py-4 text-center font-black text-slate-800"><?= $totalUnits ?></td>
                        <td colspan="4" class="text-center text-slate-400 text-xs">—</td>
                        <td class="px-6 py-4 text-center font-black text-brand-600 text-base"><?= number_format($gpa, 2) ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
