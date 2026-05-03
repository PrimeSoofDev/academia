<?php
$pageTitle = 'Add Faculty';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Faculties', 'href' => '/faculties'],
    ['label' => 'Add Faculty']
];
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Create New Faculty</h3>
            <p class="text-sm text-slate-500 mt-1">Add a new academic faculty to the university.</p>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/faculties/create') ?>" method="POST" class="space-y-5">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Faculty Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Faculty of Engineering"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>
                
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Faculty Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" required placeholder="e.g. ENG"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white uppercase font-mono">
                    <p class="text-xs text-slate-500 mt-1">A short 3-4 letter identifier.</p>
                </div>
                
                <div>
                    <label for="dean_id" class="block text-sm font-medium text-slate-700 mb-1">Appoint Dean (Optional)</label>
                    <select name="dean_id" id="dean_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                        <option value="">-- No Dean Assigned Yet --</option>
                        <?php foreach ($potentialDeans as $dean): ?>
                            <option value="<?= $dean['id'] ?>">
                                <?= htmlspecialchars($dean['name']) ?> (<?= htmlspecialchars($dean['email']) ?>)
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
                    <a href="<?= url('/faculties') ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Create Faculty
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
