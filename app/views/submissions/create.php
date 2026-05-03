<?php
$pageTitle = 'New Academic Submission';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions', 'href' => '/submissions'],
    ['label' => 'New']
];
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 px-8 py-10 text-white">
            <h2 class="text-3xl font-extrabold tracking-tight">Submit Academic Material</h2>
            <p class="text-brand-100 mt-2 opacity-90">Choose to upload a file or enter content electronically for approval.</p>
        </div>

        <form action="<?= url('/submissions/create') ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
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

            <!-- Submission Mode Toggle -->
            <div class="bg-slate-50 p-1.5 rounded-2xl flex items-center max-w-sm">
                <button type="button" onclick="setMode('upload')" id="btnUpload" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200">
                    Upload File
                </button>
                <button type="button" onclick="setMode('electronic')" id="btnElectronic" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700">
                    Electronic Entry
                </button>
            </div>
            <input type="hidden" name="submission_mode" id="submissionMode" value="upload">

            <!-- File Upload Section -->
            <div id="uploadSection">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Upload Document (PDF/Excel)</label>
                <div class="relative group">
                    <input type="file" name="file" id="fileInput" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="p-10 border-2 border-dashed border-slate-200 group-hover:border-brand-400 rounded-2xl bg-slate-50 transition-all text-center">
                        <div class="w-16 h-16 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Click to upload or drag & drop</p>
                        <p class="text-xs text-slate-400 mt-1">PDF, DOCX, or XLSX (Max 10MB)</p>
                    </div>
                </div>
            </div>

            <!-- Electronic Entry Section -->
            <div id="electronicSection" class="hidden">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Content / Question Paper / Results</label>
                <textarea name="content" id="contentTextarea" rows="12" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 bg-slate-50/50 font-mono" placeholder="Type your examination questions or results here..."></textarea>
                <p class="text-[10px] text-slate-400 mt-2 italic">Tip: You can paste formatted text or CSV data here.</p>
            </div>

            <hr class="border-slate-100">

            <!-- Digital Signing Workbox -->
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Digital Signing Authorization
                </h3>
                
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-48 h-24 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2 relative overflow-hidden">
                        <?php if (!empty($user['signature_path'])): ?>
                            <img src="<?= url($user['signature_path']) ?>" class="max-w-full max-h-full object-contain mix-blend-multiply">
                            <div class="absolute inset-0 bg-emerald-500/5 pointer-events-none"></div>
                        <?php else: ?>
                            <div class="text-center p-2">
                                <p class="text-[9px] text-red-500 font-bold uppercase leading-tight">No Signature Found</p>
                                <a href="<?= url('/profile#images') ?>" class="text-[10px] text-brand-600 underline mt-1 block">Upload now</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="mt-1">
                                <input type="checkbox" required name="confirm_sign" class="w-5 h-5 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 group-hover:text-brand-700 transition-colors">Confirm Electronic Signature</p>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">I hereby certify that the content/file above is accurate and I authorize the system to attach my digital signature to this submission for academic approval.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex gap-4">
                <a href="<?= url('/submissions') ?>" class="flex-1 px-6 py-4 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-[2] px-6 py-4 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-brand-500/25 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Finalize & Sign Submission
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function setMode(mode) {
    const uploadBtn = document.getElementById('btnUpload');
    const electBtn = document.getElementById('btnElectronic');
    const uploadSec = document.getElementById('uploadSection');
    const electSec = document.getElementById('electronicSection');
    const modeInput = document.getElementById('submissionMode');
    const fileInput = document.getElementById('fileInput');
    const contentInput = document.getElementById('contentTextarea');

    modeInput.value = mode;

    if (mode === 'upload') {
        uploadBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200';
        electBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700';
        uploadSec.classList.remove('hidden');
        electSec.classList.add('hidden');
        fileInput.required = true;
        contentInput.required = false;
    } else {
        electBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200';
        uploadBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700';
        uploadSec.classList.add('hidden');
        electSec.classList.remove('hidden');
        fileInput.required = false;
        contentInput.required = true;
    }
}
</script>
