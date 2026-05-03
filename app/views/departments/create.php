<?php
$pageTitle = 'Add Department';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Departments', 'href' => '/departments'],
    ['label' => 'Add Department']
];
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Create New Department</h3>
            <p class="text-sm text-slate-500 mt-1">Add a new academic department under a specific faculty.</p>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/departments/create') ?>" method="POST" class="space-y-5">
                
                <div>
                    <label for="faculty_id" class="block text-sm font-medium text-slate-700 mb-1">Select Faculty <span class="text-red-500">*</span></label>
                    <select name="faculty_id" id="faculty_id" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-medium">
                        <option value="">-- Choose Faculty --</option>
                        <?php foreach ($faculties as $faculty): ?>
                            <option value="<?= $faculty['id'] ?>" <?= $selectedFacultyId == $faculty['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($faculty['name']) ?> (<?= htmlspecialchars($faculty['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Department Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Computer Science"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>
                
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Department Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" required placeholder="e.g. CSC"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white uppercase font-mono">
                </div>
                
                <div>
                    <label for="hod_id" class="block text-sm font-medium text-slate-700 mb-1">Head of Department (Optional)</label>
                    <select name="hod_id" id="hod_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                        <option value="">-- No HOD Assigned Yet --</option>
                        <?php foreach ($potentialHods as $hod): ?>
                            <option value="<?= $hod['id'] ?>">
                                <?= htmlspecialchars($hod['name']) ?> (<?= htmlspecialchars($hod['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="<?= url('/departments') ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Create Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
