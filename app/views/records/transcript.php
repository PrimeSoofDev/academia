<?php
$pageTitle = 'Academic Transcript';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Academic Records', 'href' => '#'],
    ['label' => 'Transcript']
];

// Group results by session
$groupedResults = [];
foreach ($results as $r) {
    $sessionName = $r['session_name'] ?? 'Unspecified Session';
    $groupedResults[$sessionName][] = $r;
}

// Calculate totals
$totalCredits = 0;
$totalPoints = 0;
?>

<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden print:shadow-none print:border-0">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-10 text-white flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-2xl bg-white/10 flex items-center justify-center text-4xl border border-white/20">🎓</div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="text-slate-400 font-mono mt-1"><?= htmlspecialchars($user['matric_number'] ?? 'N/A') ?></p>
                </div>
            </div>
            <div class="text-center md:text-right">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Graduation Status</p>
                <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider 
                    <?= $user['graduation_status'] === 'graduated' ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' ?>">
                    <?= strtoupper($user['graduation_status'] ?? 'Enrolled') ?>
                </span>
            </div>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Faculty</p>
                    <p class="font-bold text-slate-800">Faculty of Science & Engineering</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Department</p>
                    <p class="font-bold text-slate-800">Computer Science</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Program</p>
                    <p class="font-bold text-slate-800"><?= htmlspecialchars($user['degree_name'] ?? 'B.Sc. Computer Science') ?></p>
                </div>
            </div>

            <!-- Detailed Records -->
            <div class="space-y-12">
                <?php foreach ($groupedResults as $session => $sessionResults): 
                    $sessionCredits = 0;
                    $sessionPoints = 0;
                ?>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight">Academic Session: <?= htmlspecialchars($session) ?></h4>
                        <div class="h-px flex-1 bg-slate-100"></div>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-sm">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 font-bold text-slate-500 uppercase text-[10px] tracking-widest">Code</th>
                                    <th class="px-6 py-4 font-bold text-slate-500 uppercase text-[10px] tracking-widest">Course Title</th>
                                    <th class="px-6 py-4 text-center font-bold text-slate-500 uppercase text-[10px] tracking-widest">Units</th>
                                    <th class="px-6 py-4 text-center font-bold text-slate-500 uppercase text-[10px] tracking-widest">Score</th>
                                    <th class="px-6 py-4 text-center font-bold text-slate-500 uppercase text-[10px] tracking-widest">Grade</th>
                                    <th class="px-6 py-4 text-center font-bold text-slate-500 uppercase text-[10px] tracking-widest">Point</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php foreach ($sessionResults as $r): 
                                    $gp = (float)$r['grade_point'];
                                    $units = (int)$r['credit_units'];
                                    $sessionCredits += $units;
                                    $sessionPoints += ($gp * $units);
                                ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-slate-600"><?= htmlspecialchars($r['course_code']) ?></td>
                                    <td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($r['course_title']) ?></td>
                                    <td class="px-6 py-4 text-center text-slate-500"><?= $units ?></td>
                                    <td class="px-6 py-4 text-center font-medium"><?= number_format($r['total_score'], 1) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-lg font-black bg-slate-100 text-slate-700"><?= htmlspecialchars($r['grade']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-900"><?= number_format($gp, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-slate-50/50 border-t border-slate-100">
                                <?php 
                                    $sessionGPA = $sessionCredits > 0 ? $sessionPoints / $sessionCredits : 0;
                                    $totalCredits += $sessionCredits;
                                    $totalPoints += $sessionPoints;
                                ?>
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Session Summary</td>
                                    <td class="px-6 py-4 text-center font-black text-slate-800"><?= $sessionCredits ?> Units</td>
                                    <td colspan="2"></td>
                                    <td class="px-6 py-4 text-center font-black text-brand-600 bg-brand-50 border-l border-brand-100">GPA: <?= number_format($sessionGPA, 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Final CGPA Summary -->
                <div class="p-10 rounded-[2.5rem] bg-slate-900 text-white flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-600/20 to-indigo-600/20"></div>
                    <div class="relative">
                        <h3 class="text-2xl font-black">Cumulative Academic Standing</h3>
                        <p class="text-slate-400 mt-1">Summary of all sessions completed to date.</p>
                    </div>
                    <div class="flex items-center gap-12 relative">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Credits</p>
                            <p class="text-3xl font-black"><?= $totalCredits ?></p>
                        </div>
                        <div class="w-px h-12 bg-white/10"></div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-brand-400 uppercase tracking-widest mb-1">Cumulative GPA</p>
                            <p class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-indigo-400">
                                <?= number_format($totalCredits > 0 ? $totalPoints / $totalCredits : 0, 2) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-between items-center border-t border-slate-100 pt-8 print:hidden">
                <p class="text-xs text-slate-400 font-medium">This transcript is an unofficial representation of academic records as of <?= date('F j, Y') ?>.</p>
                <button onclick="window.print()" class="px-8 py-3 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-2xl transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM15 11h.01M11 17h4"/></svg>
                    Print Transcript
                </button>
            </div>
        </div>
    </div>
</div>
