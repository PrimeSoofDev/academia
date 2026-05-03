<?php
$pageTitle = 'Enter Results — ' . htmlspecialchars($course['code']);
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Results', 'href' => '/results'],
    ['label' => htmlspecialchars($course['code'])]
];
$isPublished = false;
foreach ($resultMap as $r) {
    if ($r['published']) { $isPublished = true; break; }
}
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-blue-600 font-black text-xl shadow-sm border border-blue-200">
            <?= htmlspecialchars($course['code']) ?>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight"><?= htmlspecialchars($course['title']) ?></h2>
            <p class="text-slate-500 text-sm mt-1">
                <?= count($students) ?> student(s) enrolled
                <?php if ($isPublished): ?>
                    • <span class="text-emerald-600 font-semibold">✓ Results Published</span>
                <?php else: ?>
                    • <span class="text-amber-500 font-semibold">⚠ Results Unpublished</span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    
    <!-- Session Picker -->
    <form method="GET" action="<?= url('/results/courses/' . $course['id']) ?>" class="flex items-center gap-2">
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

<?php if (empty($students)): ?>
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
    <span class="text-3xl block mb-3">👥</span>
    <p class="text-slate-500">No students are currently enrolled in this course.</p>
    <p class="text-slate-400 text-sm mt-1">Students can enroll via their portal.</p>
</div>
<?php else: ?>

<form action="<?= url('/results/courses/' . $course['id'] . '/save') ?>" method="POST" id="resultForm">
    <input type="hidden" name="session_id" value="<?= (int)$sessionId ?>">
    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">
        <div class="px-6 py-3 bg-amber-50 border-b border-amber-100 text-amber-700 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            CA max is 40 marks, Exam max is 60 marks. Total is auto-calculated. Use "Save Draft" before "Publish Results".
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Matric No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">CA Score <span class="text-slate-400 font-normal">(max 40)</span></th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Exam Score <span class="text-slate-400 font-normal">(max 60)</span></th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-24">Total</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-20">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="studentRows">
                    <?php foreach ($students as $student): 
                        $sid  = $student['student_id'];
                        $existing = $resultMap[$sid] ?? null;
                        $ca   = $existing['ca_score'] ?? '';
                        $exam = $existing['exam_score'] ?? '';
                        $total = ($ca !== '' && $exam !== '') ? $ca + $exam : '';
                        $grade = $existing['grade'] ?? '';
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors result-row" data-student-id="<?= $sid ?>">
                        <input type="hidden" name="student_ids[]" value="<?= $sid ?>">
                        <td class="px-5 py-3">
                            <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                <?= htmlspecialchars($student['matric_number'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-slate-800"><?= htmlspecialchars($student['student_name']) ?></td>
                        <td class="px-5 py-3 text-center">
                            <input type="number" name="ca_score[<?= $sid ?>]" value="<?= htmlspecialchars($ca) ?>"
                                   min="0" max="40" step="0.5"
                                   class="ca-input w-24 text-center px-2 py-1.5 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm font-bold bg-white transition-all"
                                   oninput="calcTotal(this.closest('tr'))">
                        </td>
                        <td class="px-5 py-3 text-center">
                            <input type="number" name="exam_score[<?= $sid ?>]" value="<?= htmlspecialchars($exam) ?>"
                                   min="0" max="60" step="0.5"
                                   class="exam-input w-24 text-center px-2 py-1.5 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm font-bold bg-white transition-all"
                                   oninput="calcTotal(this.closest('tr'))">
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="total-display font-black text-slate-800 text-base"><?= $total !== '' ? number_format((float)$total, 1) : '—' ?></span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="grade-display font-bold text-xs px-2 py-1 rounded-full <?= $grade ? 'bg-slate-800 text-white' : 'text-slate-400 italic' ?>">
                                <?= $grade ?: '—' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="<?= url('/results') ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
            ← Back to Courses
        </a>
        
        <div class="flex items-center gap-3">
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                Save Draft
            </button>
        </div>
    </div>
</form>

<!-- Publish Form (separate, so it doesn't interfere with save) -->
<?php if (!empty($resultMap)): ?>
<form action="<?= url('/results/courses/' . $course['id'] . '/publish') ?>" method="POST" class="mt-4 p-5 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between">
    <input type="hidden" name="session_id" value="<?= (int)$sessionId ?>">
    <div>
        <p class="text-sm font-bold text-emerald-800">Publish Results to Students</p>
        <p class="text-xs text-emerald-600 mt-0.5">Once published, students will be able to see their scores for this course.</p>
    </div>
    <button type="submit" onclick="return confirm('Are you sure you want to publish results for this course? Students will be able to view them immediately.')"
            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm shrink-0">
        Publish Results
    </button>
</form>
<?php endif; ?>

<?php endif; ?>

<script>
// Grade computation (mirrors server-side logic)
const gradeMap = [
    {min: 70, grade: 'A'}, {min: 60, grade: 'B'}, {min: 50, grade: 'C'},
    {min: 45, grade: 'D'}, {min: 40, grade: 'E'}, {min:  0, grade: 'F'}
];

function computeGrade(total) {
    for (const g of gradeMap) {
        if (total >= g.min) return g.grade;
    }
    return 'F';
}

function calcTotal(row) {
    const ca   = parseFloat(row.querySelector('.ca-input').value) || 0;
    const exam = parseFloat(row.querySelector('.exam-input').value) || 0;
    const total = Math.min(ca + exam, 100);

    const totalDisplay = row.querySelector('.total-display');
    const gradeDisplay = row.querySelector('.grade-display');
    const grade = computeGrade(total);

    totalDisplay.textContent = total.toFixed(1);
    gradeDisplay.textContent = grade;
    gradeDisplay.className = `grade-display font-bold text-xs px-2 py-1 rounded-full bg-slate-800 text-white`;
}

// Init totals on page load
document.querySelectorAll('.result-row').forEach(row => {
    const ca   = parseFloat(row.querySelector('.ca-input').value);
    const exam = parseFloat(row.querySelector('.exam-input').value);
    if (!isNaN(ca) && !isNaN(exam)) calcTotal(row);
});
</script>
