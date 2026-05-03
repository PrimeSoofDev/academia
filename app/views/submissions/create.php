<?php
$pageTitle = 'New Academic Submission';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions', 'href' => '/submissions'],
    ['label' => 'New']
];
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 px-8 py-10 text-white">
            <h2 class="text-3xl font-extrabold tracking-tight">Submit Academic Material</h2>
            <p class="text-brand-100 mt-2 opacity-90">Upload exam questions or results for departmental approval and signature.</p>
        </div>

        <form action="<?= url('/submissions/create') ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Select Course</label>
                    <select name="course_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 bg-slate-50/50">
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['code']) ?> - <?= htmlspecialchars($c['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Submission Type</label>
                    <select name="type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 bg-slate-50/50">
                        <option value="exam_questions">Exam Questions</option>
                        <option value="ca_results">CA Results</option>
                        <option value="final_results">Final Results Sheet</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Upload File (PDF/Doc/Excel)</label>
                <div class="relative group">
                    <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="p-8 border-2 border-dashed border-slate-200 group-hover:border-brand-400 rounded-2xl bg-slate-50 transition-all text-center">
                        <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Click to upload or drag & drop</p>
                        <p class="text-xs text-slate-400 mt-1">PDF, DOCX, or XLSX (Max 10MB)</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Additional Remarks (Optional)</label>
                <textarea name="content" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 bg-slate-50/50" placeholder="Enter any notes for the HOD or Dean..."></textarea>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100">
                <div class="flex gap-3">
                    <span class="text-xl">✍️</span>
                    <div>
                        <p class="text-xs font-bold text-amber-800 uppercase tracking-wider">Digital Signing</p>
                        <p class="text-xs text-amber-700 mt-0.5">By submitting, your electronic signature will be automatically attached to this document. Ensure your signature is uploaded in your profile.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex gap-4">
                <a href="<?= url('/submissions') ?>" class="flex-1 px-6 py-3.5 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-[2] px-6 py-3.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Submit & Sign
                </button>
            </div>
        </form>
    </div>
</div>
