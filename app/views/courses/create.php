<?php
$pageTitle = 'Add Course';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Courses', 'href' => '/courses'],
    ['label' => 'Add Course']
];
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Create New Course</h3>
            <p class="text-sm text-slate-500 mt-1">Add a new academic course to the curriculum.</p>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/courses/create') ?>" method="POST" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Host Department <span class="text-red-500">*</span></label>
                        <select name="department_id" id="department_id" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-medium">
                            <option value="">-- Choose Department --</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $selectedDeptId == $dept['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['name']) ?> (<?= htmlspecialchars($dept['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="lecturer_id" class="block text-sm font-medium text-slate-700 mb-1">Assign Lecturer (Optional)</label>
                        <select name="lecturer_id" id="lecturer_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($potentialLecturers as $lecturer): ?>
                                <option value="<?= $lecturer['id'] ?>">
                                    <?= htmlspecialchars($lecturer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Course Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required placeholder="e.g. Introduction to Programming"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Course Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" required placeholder="e.g. CSC101"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white uppercase font-mono tracking-wider">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="credit_units" class="block text-sm font-medium text-slate-700 mb-1">Credit Units <span class="text-red-500">*</span></label>
                        <input type="number" name="credit_units" id="credit_units" required value="3" min="1" max="10"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white text-center font-bold">
                    </div>
                    
                    <div>
                        <label for="level" class="block text-sm font-medium text-slate-700 mb-1">Level <span class="text-red-500">*</span></label>
                        <select name="level" id="level" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="100">100 Level</option>
                            <option value="200">200 Level</option>
                            <option value="300">300 Level</option>
                            <option value="400">400 Level</option>
                            <option value="500">500 Level</option>
                            <option value="PG">Postgraduate</option>
                        </select>
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-slate-700 mb-1">Semester <span class="text-red-500">*</span></label>
                        <select name="semester" id="semester" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="first">First Semester</option>
                            <option value="second">Second Semester</option>
                            <option value="year">Full Year</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Course Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" placeholder="Brief overview of the course content..."
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="<?= url('/courses') ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
