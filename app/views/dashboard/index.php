<?php
/**
 * Dashboard View — Role-Based
 * Renders different widgets based on $role and $stats.
 */

$pageTitle  = 'Dashboard';
$breadcrumb = [['label' => 'Dashboard']];
$role       = $role ?? 'student';
$stats      = $stats ?? [];
$user       = $user ?? $_SESSION['auth'] ?? [];

// Role-label map
$roleLabels = [
    'superadmin' => 'Super Administrator',
    'vc'         => 'Vice Chancellor',
    'dean'       => 'Dean',
    'hod'        => 'Head of Department',
    'lecturer'   => 'Lecturer',
    'staff'      => 'Administrative Staff',
    'student'    => 'Student',
];
$roleLabel = $roleLabels[$role] ?? ucfirst($role);

// Time-based greeting
$hour = (int) date('H');
$greeting = match(true) {
    $hour < 12 => 'Good morning',
    $hour < 17 => 'Good afternoon',
    default    => 'Good evening',
};
?>

<!-- ══════════════════════════════════════════════════════════
     WELCOME BANNER
══════════════════════════════════════════════════════════ -->
<div class="bg-gradient-to-r from-brand-700 via-brand-600 to-purple-700 rounded-2xl p-6 mb-6 relative overflow-hidden shadow-xl">
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/3 w-40 h-40 bg-purple-500/20 rounded-full translate-y-1/2 blur-xl pointer-events-none"></div>

    <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-brand-200 text-sm font-medium mb-1"><?= $greeting ?> 👋</p>
            <h2 class="text-white text-2xl font-extrabold tracking-tight">
                <?= htmlspecialchars($user['name'] ?? 'Welcome') ?>
            </h2>
            <p class="text-brand-200 text-sm mt-1">
                <?= $roleLabel ?> &nbsp;|&nbsp;
                <span class="text-white font-semibold"><?= date('l, d F Y') ?></span>
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-3">
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                <span class="text-2xl">
                    <?= match($role) {
                        'vc','superadmin' => '🏛️',
                        'dean'           => '🎓',
                        'hod'            => '📋',
                        'lecturer'       => '👨‍🏫',
                        'student'        => '📚',
                        default          => '🏢'
                    } ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     STAT CARDS — rendered per role
══════════════════════════════════════════════════════════ -->
<?php if (in_array($role, ['superadmin', 'vc'])): ?>

    <!-- VC / Superadmin Stats -->
    <?php
    $cards = [
        ['label' => 'Students',    'value' => $stats['total_students']    ?? 0, 'icon' => '🎓', 'color' => 'from-blue-500 to-cyan-500',     'bg' => 'bg-blue-50',   'text' => 'text-blue-700'],
        ['label' => 'Lecturers',   'value' => $stats['total_lecturers']   ?? 0, 'icon' => '👨‍🏫', 'color' => 'from-violet-500 to-purple-500', 'bg' => 'bg-violet-50', 'text' => 'text-violet-700'],
        ['label' => 'Faculties',   'value' => $stats['total_faculties']   ?? 0, 'icon' => '🏛️', 'color' => 'from-amber-500 to-orange-500',  'bg' => 'bg-amber-50',  'text' => 'text-amber-700'],
        ['label' => 'Departments', 'value' => $stats['total_departments'] ?? 0, 'icon' => '📋', 'color' => 'from-emerald-500 to-teal-500',  'bg' => 'bg-emerald-50','text' => 'text-emerald-700'],
        ['label' => 'Courses',     'value' => $stats['total_courses']     ?? 0, 'icon' => '📚', 'color' => 'from-rose-500 to-pink-500',     'bg' => 'bg-rose-50',   'text' => 'text-rose-700'],
        ['label' => 'Staff',       'value' => $stats['total_staff']       ?? 0, 'icon' => '🏢', 'color' => 'from-slate-500 to-slate-600',   'bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
    ];
    ?>
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <?php foreach ($cards as $card): ?>
        <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="text-2xl"><?= $card['icon'] ?></div>
                <div class="w-2 h-2 rounded-full bg-gradient-to-br <?= $card['color'] ?>"></div>
            </div>
            <p class="text-2xl font-extrabold text-slate-800 leading-none">
                <?= number_format((int)$card['value']) ?>
            </p>
            <p class="text-slate-500 text-xs font-medium mt-1"><?= $card['label'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Faculty Overview Table -->
    <?php if (!empty($stats['faculties'])): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Faculty Overview</h3>
            <a href="<?= url('/faculties') ?>" class="text-brand-500 hover:text-brand-600 text-sm font-medium transition-colors">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Faculty</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dean</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Departments</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Students</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($stats['faculties'] as $faculty): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-brand-100 flex items-center justify-center">
                                    <span class="text-brand-600 font-bold text-xs">
                                        <?= strtoupper(substr($faculty['name'], 0, 2)) ?>
                                    </span>
                                </div>
                                <span class="font-semibold text-slate-700"><?= htmlspecialchars($faculty['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600"><?= htmlspecialchars($faculty['dean_name'] ?? '—') ?></td>
                        <td class="px-6 py-3.5 text-right">
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <?= number_format((int)($faculty['department_count'] ?? 0)) ?>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <?= number_format((int)($faculty['student_count'] ?? 0)) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($role === 'dean'): ?>

    <!-- Dean Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <?php
        $cards = [
            ['label' => 'Departments', 'value' => $stats['total_departments'] ?? 0, 'icon' => '📋', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
            ['label' => 'Lecturers',   'value' => $stats['total_lecturers']   ?? 0, 'icon' => '👨‍🏫', 'color' => 'text-blue-600',   'bg' => 'bg-blue-50'],
            ['label' => 'Students',    'value' => $stats['total_students']    ?? 0, 'icon' => '🎓', 'color' => 'text-emerald-600','bg' => 'bg-emerald-50'],
        ];
        foreach ($cards as $card): ?>
        <div class="stat-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl <?= $card['bg'] ?> flex items-center justify-center text-2xl flex-shrink-0">
                <?= $card['icon'] ?>
            </div>
            <div>
                <p class="text-2xl font-extrabold <?= $card['color'] ?>"><?= number_format((int)$card['value']) ?></p>
                <p class="text-slate-500 text-xs font-medium mt-0.5"><?= $card['label'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Department list -->
    <?php if (!empty($stats['departments'])): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">
                <?= htmlspecialchars($stats['faculty']['name'] ?? 'Your Faculty') ?> — Departments
            </h3>
        </div>
        <div class="divide-y divide-slate-100">
            <?php foreach ($stats['departments'] as $dept): ?>
            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div>
                    <p class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($dept['name']) ?></p>
                    <p class="text-slate-500 text-xs mt-0.5">HOD: <?= htmlspecialchars($dept['hod_name'] ?? 'Not assigned') ?></p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                        <?= number_format((int)($dept['student_count'] ?? 0)) ?> students
                    </span>
                    <a href="<?= url('/departments/' . $dept['id']) ?>" class="text-brand-500 hover:text-brand-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($role === 'hod'): ?>

    <!-- HOD Stats -->
    <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mb-6">
        <?php
        $cards = [
            ['label' => 'Courses',  'value' => $stats['total_courses']  ?? 0, 'icon' => '📚', 'bg' => 'bg-blue-50',   'color' => 'text-blue-600'],
            ['label' => 'Students', 'value' => $stats['total_students'] ?? 0, 'icon' => '🎓', 'bg' => 'bg-emerald-50','color' => 'text-emerald-600'],
        ];
        foreach ($cards as $card): ?>
        <div class="stat-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl <?= $card['bg'] ?> flex items-center justify-center text-2xl flex-shrink-0">
                <?= $card['icon'] ?>
            </div>
            <div>
                <p class="text-3xl font-extrabold <?= $card['color'] ?>"><?= number_format((int)$card['value']) ?></p>
                <p class="text-slate-500 text-xs font-medium mt-0.5"><?= $card['label'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Course list for HOD -->
    <?php if (!empty($stats['courses'])): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Department Courses</h3>
            <a href="<?= url('/courses') ?>" class="text-brand-500 hover:text-brand-600 text-sm font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lecturer</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Enrolled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($stats['courses'] as $course): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3.5">
                            <span class="font-mono font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded text-xs">
                                <?= htmlspecialchars($course['code']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 font-medium text-slate-700"><?= htmlspecialchars($course['title'] ?? $course['name'] ?? '—') ?></td>
                        <td class="px-6 py-3.5 text-slate-500"><?= htmlspecialchars($course['lecturer_name'] ?? 'TBA') ?></td>
                        <td class="px-6 py-3.5 text-right">
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <?= number_format((int)($course['enrolled_count'] ?? 0)) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($role === 'lecturer'): ?>

    <!-- Lecturer Stats -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <?php
        $cards = [
            ['label' => 'My Courses',  'value' => $stats['total_courses']  ?? 0, 'icon' => '📚', 'bg' => 'bg-violet-50',  'color' => 'text-violet-600'],
            ['label' => 'My Students', 'value' => $stats['total_students'] ?? 0, 'icon' => '🎓', 'bg' => 'bg-emerald-50', 'color' => 'text-emerald-600'],
        ];
        foreach ($cards as $card): ?>
        <div class="stat-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl <?= $card['bg'] ?> flex items-center justify-center text-3xl flex-shrink-0">
                <?= $card['icon'] ?>
            </div>
            <div>
                <p class="text-4xl font-extrabold <?= $card['color'] ?>"><?= number_format((int)$card['value']) ?></p>
                <p class="text-slate-500 text-sm font-medium mt-1"><?= $card['label'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Lecturer's courses -->
    <?php if (!empty($stats['courses'])): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">My Assigned Courses</h3>
        </div>
        <div class="divide-y divide-slate-100">
            <?php foreach ($stats['courses'] as $course): ?>
            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="font-mono font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded text-xs">
                        <?= htmlspecialchars($course['code']) ?>
                    </span>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($course['title'] ?? $course['name'] ?? '—') ?></p>
                        <p class="text-slate-500 text-xs"><?= htmlspecialchars($course['department_name'] ?? '') ?></p>
                    </div>
                </div>
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                    <?= number_format((int)($course['enrolled_count'] ?? 0)) ?> students
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($role === 'student'): ?>

    <!-- Student Stats -->
    <div class="grid grid-cols-1 gap-4 mb-6">
        <div class="stat-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-2xl flex-shrink-0">📚</div>
            <div>
                <p class="text-3xl font-extrabold text-brand-600"><?= number_format((int)($stats['total_courses'] ?? 0)) ?></p>
                <p class="text-slate-500 text-xs font-medium mt-0.5">Enrolled Courses</p>
            </div>
        </div>
    </div>

    <!-- Student enrolled courses -->
    <?php if (!empty($stats['enrolled_courses'])): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">My Enrolled Courses</h3>
        </div>
        <div class="divide-y divide-slate-100">
            <?php foreach ($stats['enrolled_courses'] as $course): ?>
            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="font-mono font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded text-xs">
                        <?= htmlspecialchars($course['code']) ?>
                    </span>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($course['title'] ?? $course['name'] ?? '—') ?></p>
                        <p class="text-slate-500 text-xs">Lecturer: <?= htmlspecialchars($course['lecturer_name'] ?? 'TBA') ?></p>
                    </div>
                </div>
                <?php if (!empty($course['grade'])): ?>
                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">
                    Grade: <?= htmlspecialchars($course['grade']) ?>
                </span>
                <?php else: ?>
                <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs">In Progress</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

<?php else: ?>
    <!-- Generic Staff Dashboard -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
        <div class="text-5xl mb-4">🏢</div>
        <h3 class="text-slate-800 font-bold text-lg mb-2">Administrative Dashboard</h3>
        <p class="text-slate-500 text-sm">Use the sidebar to navigate to your unit.</p>
    </div>
<?php endif; ?>

<!-- Quick Actions -->
<div class="mt-6 bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
    <h3 class="font-bold text-slate-800 text-sm mb-4">Quick Actions</h3>
    <div class="flex flex-wrap gap-3">
        <?php if (in_array($role, ['superadmin','vc','dean','hod'])): ?>
        <a href="<?= url('/users/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
        <a href="<?= url('/courses/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Course
        </a>
        <?php endif; ?>
        <a href="<?= url('/profile') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
    </div>
</div>
