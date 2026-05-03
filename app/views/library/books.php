<?php
$pageTitle = 'Library — Book Catalog';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Library', 'href' => '/library'],
    ['label' => 'Book Catalog']
];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Book List -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Catalog</h3>
        </div>
        
        <div class="p-4 border-b border-slate-100 bg-slate-50">
            <div class="relative w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchBooks" onkeyup="tableSearch('searchBooks', 'booksTable')" placeholder="Search by title, author, ISBN..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="booksTable" class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Book Information</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Availability</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($books)): ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No books found in the catalog.</td></tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3.5">
                                <span class="font-bold text-slate-800 block text-base"><?= htmlspecialchars($book['title']) ?></span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-medium text-slate-600">By <?= htmlspecialchars($book['author']) ?></span>
                                    <?php if (!empty($book['isbn'])): ?>
                                        <span class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">ISBN: <?= htmlspecialchars($book['isbn']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    <?= htmlspecialchars($book['category'] ?? 'General') ?>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <?php if ($book['copies_avail'] > 0): ?>
                                    <span class="font-bold text-emerald-600"><?= (int)$book['copies_avail'] ?> available</span>
                                <?php else: ?>
                                    <span class="font-bold text-red-500">Out of stock</span>
                                <?php endif; ?>
                                <span class="text-xs text-slate-400 block mt-0.5">out of <?= (int)$book['copies_total'] ?> total</span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <button class="text-brand-500 hover:text-brand-700 text-sm font-medium transition-colors">Edit</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Add Book Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-max">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Add New Book</h3>
        </div>
        <div class="p-6 bg-slate-50/50">
            <form action="<?= url('/library/books') ?>" method="POST" class="space-y-4">
                
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Book Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>
                
                <div>
                    <label for="author" class="block text-sm font-medium text-slate-700 mb-1">Author <span class="text-red-500">*</span></label>
                    <input type="text" name="author" id="author" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-slate-700 mb-1">ISBN</label>
                        <input type="text" name="isbn" id="isbn" placeholder="Optional" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-mono">
                    </div>
                    <div>
                        <label for="copies_total" class="block text-sm font-medium text-slate-700 mb-1">Copies <span class="text-red-500">*</span></label>
                        <input type="number" name="copies_total" id="copies_total" value="1" min="1" required class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                    <select name="category" id="category" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                        <option value="General">General / Reference</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Humanities">Humanities</option>
                        <option value="Business">Business & Finance</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add to Catalog
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
