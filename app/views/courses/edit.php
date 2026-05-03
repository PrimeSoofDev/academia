<?php
$pageTitle = 'Edit Course';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Courses', 'href' => '/courses'],
    ['label' => $course['code'], 'href' => '/courses/' . $course['id']],
    ['label' => 'Edit']
];
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Edit Course details</h3>
            <p class="text-sm text-slate-500 mt-1">Update curriculum data for <?= htmlspecialchars($course['title']) ?></p>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/courses/' . $course['id'] . '/edit') ?>" method="POST" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Host Department <span class="text-red-500">*</span></label>
                        <select name="department_id" id="department_id" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-medium">
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $course['department_id'] == $dept['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['name']) ?> (<?= htmlspecialchars($dept['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="lecturer_id" class="block text-sm font-medium text-slate-700 mb-1">Assign Lecturer</label>
                        <select name="lecturer_id" id="lecturer_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($potentialLecturers as $lecturer): ?>
                                <option value="<?= $lecturer['id'] ?>" <?= $course['lecturer_id'] == $lecturer['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lecturer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Course Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required value="<?= htmlspecialchars($course['title']) ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Course Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" required value="<?= htmlspecialchars($course['code']) ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white uppercase font-mono tracking-wider">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="credit_units" class="block text-sm font-medium text-slate-700 mb-1">Credit Units <span class="text-red-500">*</span></label>
                        <input type="number" name="credit_units" id="credit_units" required value="<?= (int)$course['credit_units'] ?>" min="1" max="10"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white text-center font-bold">
                    </div>
                    
                    <div>
                        <label for="level" class="block text-sm font-medium text-slate-700 mb-1">Level <span class="text-red-500">*</span></label>
                        <select name="level" id="level" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <?php foreach (['100', '200', '300', '400', '500', 'PG'] as $lvl): ?>
                                <option value="<?= $lvl ?>" <?= $course['level'] == $lvl ? 'selected' : '' ?>><?= $lvl ?> Level</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-slate-700 mb-1">Semester <span class="text-red-500">*</span></label>
                        <select name="semester" id="semester" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="first" <?= $course['semester'] == 'first' ? 'selected' : '' ?>>First Semester</option>
                            <option value="second" <?= $course['semester'] == 'second' ? 'selected' : '' ?>>Second Semester</option>
                            <option value="year" <?= $course['semester'] == 'year' ? 'selected' : '' ?>>Full Year</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" id="status" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-medium">
                            <option value="active" <?= $course['status'] == 'active' ? 'selected' : '' ?> class="text-emerald-600">Active</option>
                            <option value="inactive" <?= $course['status'] == 'inactive' ? 'selected' : '' ?> class="text-slate-500">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Course Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"><?= htmlspecialchars($course['description'] ?? '') ?></textarea>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <?php if (Auth::hasRole(['superadmin', 'vc', 'dean', 'hod'])): ?>
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this course? All student enrollments will be lost.')) document.getElementById('delete-form').submit();" 
                            class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        Delete Course
                    </button>
                    <?php else: ?>
                    <div></div> <!-- Spacer -->
                    <?php endif; ?>
                    
                    <div class="flex items-center gap-3">
                        <a href="<?= url('/courses/' . $course['id']) ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

            <?php if (Auth::hasRole(['superadmin', 'vc', 'dean', 'hod'])): ?>
            <form id="delete-form" action="<?= url('/courses/' . $course['id'] . '/delete') ?>" method="POST" style="display: none;"></form>
            <?php endif; ?>
        </div>
    </div>
</div>
