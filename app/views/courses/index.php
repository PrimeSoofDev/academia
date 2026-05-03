<?php
$pageTitle = 'Courses';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Courses']
];
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Course Catalog</h2>
        <p class="text-slate-500 text-sm mt-1">Manage academic courses and assigned lecturers.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="relative max-w-xs">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchCourses" onkeyup="tableSearch('searchCourses', 'coursesTable')" placeholder="Search courses by code or title..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white" />
        </div>
        
        <?php if (Auth::hasRole(['superadmin', 'vc', 'dean', 'hod'])): ?>
        <a href="<?= url('/courses/create') ?>" class="px-5 py-2 whitespace-nowrap bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Course
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table id="coursesTable" class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Course Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lecturer</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Level & Units</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Enrolled</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($courses)): ?>
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">No courses have been added to the catalog yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-sm font-bold"><?= htmlspecialchars($course['code']) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 block"><?= htmlspecialchars($course['title']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            <?= htmlspecialchars($course['department_name'] ?? 'Unknown') ?>
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            <?= htmlspecialchars($course['lecturer_name'] ?? 'Unassigned') ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                    Lvl <?= htmlspecialchars($course['level']) ?>
                                </span>
                                <span class="font-bold text-slate-600"><?= (int)$course['credit_units'] ?> U</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <?= number_format((int)$course['enrolled_count']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <?php if (Auth::hasRole(['superadmin', 'vc', 'dean', 'hod'])): ?>
                                    <button onclick='openAssignModal(<?= json_encode(["id" => $course["id"], "title" => $course["title"], "code" => $course["code"], "lecturer_id" => $course["lecturer_id"]]) ?>)' 
                                            class="text-amber-600 hover:text-amber-800 text-sm font-medium inline-flex items-center gap-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Assign
                                    </button>
                                <?php endif; ?>
                                <a href="<?= url('/courses/' . $course['id']) ?>" class="text-brand-600 hover:text-brand-800 text-sm font-medium inline-flex items-center gap-1 transition-colors">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-8 pt-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Assign Lecturer</h3>
                    <button onclick="closeAssignModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg>
                    </button>
                </div>

                <form action="<?= url('/courses/assign') ?>" method="POST" class="space-y-5">
                    <input type="hidden" name="course_id" id="assign_course_id">
                    
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 mb-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Course</p>
                        <p class="text-sm font-bold text-slate-800" id="assign_course_text"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Select Lecturer</label>
                        <select name="lecturer_id" id="assign_lecturer_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                            <option value="">Unassigned</option>
                            <?php foreach ($lecturers as $l): ?>
                                <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeAssignModal()"
                                class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-[2] px-4 py-3 bg-brand-600 text-white text-sm font-bold rounded-xl hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/20">
                            Save Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAssignModal(course) {
    document.getElementById('assign_course_id').value = course.id;
    document.getElementById('assign_course_text').textContent = course.code + ' - ' + course.title;
    document.getElementById('assign_lecturer_id').value = course.lecturer_id || '';
    document.getElementById('assignModal').classList.remove('hidden');
}
function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}
</script>

