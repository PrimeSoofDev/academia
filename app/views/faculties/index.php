<?php
$pageTitle = 'Faculties';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Faculties']
];
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Faculties Overview</h2>
        <p class="text-slate-500 text-sm mt-1">Manage university faculties and their appointed deans.</p>
    </div>
    <?php if (Auth::hasRole(['superadmin', 'vc'])): ?>
    <a href="<?= url('/faculties/create') ?>" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Faculty
    </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($faculties)): ?>
        <div class="col-span-full bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-sm">
            <span class="text-4xl block mb-3">🏛️</span>
            <p class="text-slate-500 text-sm">No faculties have been created yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($faculties as $faculty): ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow flex flex-col relative group">
            
            <?php if (Auth::hasRole(['superadmin', 'vc'])): ?>
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="<?= url('/faculties/' . $faculty['id'] . '/edit') ?>" class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-600 hover:text-brand-600 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </a>
            </div>
            <?php endif; ?>

            <div class="p-6 border-b border-slate-50">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-100 to-indigo-100 flex items-center justify-center text-brand-600 font-bold text-lg mb-4">
                    <?= htmlspecialchars($faculty['code']) ?>
                </div>
                <h3 class="text-lg font-bold text-slate-800 line-clamp-1" title="<?= htmlspecialchars($faculty['name']) ?>">
                    <a href="<?= url('/faculties/' . $faculty['id']) ?>" class="hover:text-brand-600 transition-colors">
                        <?= htmlspecialchars($faculty['name']) ?>
                    </a>
                </h3>
                <p class="text-slate-500 text-xs mt-1">Dean: <span class="font-medium text-slate-700"><?= htmlspecialchars($faculty['dean_name'] ?? 'Not Assigned') ?></span></p>
            </div>
            
            <div class="p-4 bg-slate-50/50 flex-1 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-2xl font-black text-slate-700"><?= number_format((int)$faculty['department_count']) ?></p>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400 mt-0.5">Departments</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-700"><?= number_format((int)$faculty['student_count']) ?></p>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400 mt-0.5">Students</p>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-50">
                <a href="<?= url('/faculties/' . $faculty['id']) ?>" class="text-brand-600 hover:text-brand-800 text-sm font-medium inline-flex items-center gap-1 transition-colors">
                    View Details
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
