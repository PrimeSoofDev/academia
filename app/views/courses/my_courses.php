<?php
$pageTitle = 'My Courses';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'My Courses']
];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">My Enrolled Courses</h2>
        <p class="text-slate-500 text-sm mt-1">Courses you are registered for this academic session.</p>
    </div>
</div>

<?php if (empty($enrolledCourses)): ?>
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10 text-center">
    <span class="text-4xl block mb-4">📚</span>
    <h3 class="font-bold text-slate-700 text-lg mb-1">Not Enrolled in Any Courses</h3>
    <p class="text-slate-500 text-sm">Contact your department to register for courses this semester.</p>
</div>
<?php else: ?>

<!-- Summary Stats -->
<?php
$totalUnits = array_sum(array_column($enrolledCourses, 'credit_units'));
?>
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Courses Enrolled</p>
        <p class="text-3xl font-black text-slate-800"><?= count($enrolledCourses) ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Credit Units</p>
        <p class="text-3xl font-black text-slate-800"><?= $totalUnits ?></p>
    </div>
    <div class="bg-white col-span-2 md:col-span-1 rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">View Results</p>
            <a href="<?= url('/results') ?>" class="text-brand-600 font-bold text-sm hover:underline">Check Transcript →</a>
        </div>
    </div>
</div>

<!-- Enrolled Courses Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    <?php foreach ($enrolledCourses as $course): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-start justify-between mb-3">
                <span class="font-mono text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg text-sm font-bold tracking-wider">
                    <?= htmlspecialchars($course['code']) ?>
                </span>
                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-100 rounded text-[10px] font-bold uppercase tracking-wider">
                    Lvl <?= htmlspecialchars($course['level']) ?>
                </span>
            </div>
            
            <h3 class="font-bold text-slate-800 text-base mb-2 line-clamp-2"><?= htmlspecialchars($course['title']) ?></h3>
            
            <div class="text-xs text-slate-500 space-y-1.5 mt-3">
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <?= htmlspecialchars($course['lecturer_name'] ?? 'No lecturer assigned') ?>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <?= htmlspecialchars($course['department_name'] ?? 'N/A') ?>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?= ucfirst(htmlspecialchars($course['semester'])) ?> Semester • <?= (int)$course['credit_units'] ?> Units
                </div>
            </div>
        </div>
        
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded uppercase tracking-wider">Enrolled</span>
            <a href="<?= url('/courses/' . $course['course_id']) ?>" class="text-brand-600 hover:text-brand-800 text-xs font-semibold transition-colors">
                View Course →
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
