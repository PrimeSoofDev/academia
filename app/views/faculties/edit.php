<?php
$pageTitle = 'Edit Faculty';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Faculties', 'href' => '/faculties'],
    ['label' => $faculty['code'], 'href' => '/faculties/' . $faculty['id']],
    ['label' => 'Edit']
];
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Edit Faculty details</h3>
                <p class="text-sm text-slate-500 mt-1">Update information for <?= htmlspecialchars($faculty['name']) ?></p>
            </div>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/faculties/' . $faculty['id'] . '/edit') ?>" method="POST" class="space-y-5">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Faculty Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required value="<?= htmlspecialchars($faculty['name']) ?>"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>
                
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Faculty Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" required value="<?= htmlspecialchars($faculty['code']) ?>"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white uppercase font-mono">
                </div>
                
                <div>
                    <label for="dean_id" class="block text-sm font-medium text-slate-700 mb-1">Appoint Dean</label>
                    <select name="dean_id" id="dean_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                        <option value="">-- No Dean Assigned Yet --</option>
                        <?php foreach ($potentialDeans as $dean): ?>
                            <option value="<?= $dean['id'] ?>" <?= $faculty['dean_id'] == $dean['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dean['name']) ?> (<?= htmlspecialchars($dean['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"><?= htmlspecialchars($faculty['description'] ?? '') ?></textarea>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <!-- Delete Button Form -->
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this faculty? This action cannot be undone.')) document.getElementById('delete-form').submit();" 
                            class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        Delete Faculty
                    </button>
                    
                    <div class="flex items-center gap-3">
                        <a href="<?= url('/faculties/' . $faculty['id']) ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="<?= url('/faculties/' . $faculty['id'] . '/delete') ?>" method="POST" style="display: none;"></form>
        </div>
    </div>
</div>
