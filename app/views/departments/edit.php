<?php
$pageTitle = 'Edit Department';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Departments', 'href' => '/departments'],
    ['label' => $department['code'], 'href' => '/departments/' . $department['id']],
    ['label' => 'Edit']
];
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Edit Department details</h3>
            <p class="text-sm text-slate-500 mt-1">Update information for <?= htmlspecialchars($department['name']) ?></p>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/departments/' . $department['id'] . '/edit') ?>" method="POST" class="space-y-5">
                
                <div>
                    <label for="faculty_id" class="block text-sm font-medium text-slate-700 mb-1">Assigned Faculty <span class="text-red-500">*</span></label>
                    <select name="faculty_id" id="faculty_id" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-medium">
                        <?php foreach ($faculties as $faculty): ?>
                            <option value="<?= $faculty['id'] ?>" <?= $department['faculty_id'] == $faculty['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($faculty['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Department Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required value="<?= htmlspecialchars($department['name']) ?>"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>
                
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Department Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" required value="<?= htmlspecialchars($department['code']) ?>"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white uppercase font-mono">
                </div>
                
                <div>
                    <label for="hod_id" class="block text-sm font-medium text-slate-700 mb-1">Head of Department (HOD)</label>
                    <select name="hod_id" id="hod_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                        <option value="">-- No HOD Assigned --</option>
                        <?php foreach ($potentialHods as $hod): ?>
                            <option value="<?= $hod['id'] ?>" <?= $department['hod_id'] == $hod['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($hod['name']) ?> (<?= htmlspecialchars($hod['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"><?= htmlspecialchars($department['description'] ?? '') ?></textarea>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <!-- Delete Button Form -->
                    <?php if (Auth::hasRole(['superadmin', 'vc'])): ?>
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this department? All linked courses might be affected.')) document.getElementById('delete-form').submit();" 
                            class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        Delete Department
                    </button>
                    <?php else: ?>
                    <div></div> <!-- Spacer -->
                    <?php endif; ?>
                    
                    <div class="flex items-center gap-3">
                        <a href="<?= url('/departments/' . $department['id']) ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

            <?php if (Auth::hasRole(['superadmin', 'vc'])): ?>
            <form id="delete-form" action="<?= url('/departments/' . $department['id'] . '/delete') ?>" method="POST" style="display: none;"></form>
            <?php endif; ?>
        </div>
    </div>
</div>
