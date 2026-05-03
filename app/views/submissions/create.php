<?php
$pageTitle = 'New Academic Submission';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions', 'href' => '/submissions'],
    ['label' => 'New']
];
?>

<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 px-8 py-10 text-white">
            <h2 class="text-3xl font-extrabold tracking-tight">Submit Academic Material</h2>
            <p class="text-brand-100 mt-2 opacity-90">Choose to upload a file or enter content electronically for approval.</p>
        </div>

        <form action="<?= url('/submissions/create') ?>" method="POST" enctype="multipart/form-data" id="submissionForm" class="p-8 space-y-8">
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
                    <select name="type" id="submissionType" required onchange="autoSwitchEntryMode()" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 bg-slate-50/50">
                        <option value="exam_questions">Exam Questions</option>
                        <option value="ca_results">CA Results</option>
                        <option value="final_results">Final Results Sheet</option>
                    </select>
                </div>
            </div>

            <!-- Submission Mode Toggle -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="bg-slate-50 p-1.5 rounded-2xl flex items-center w-64 border border-slate-100">
                    <button type="button" onclick="setMode('upload')" id="btnUpload" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200">
                        Upload File
                    </button>
                    <button type="button" onclick="setMode('electronic')" id="btnElectronic" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700">
                        Electronic Entry
                    </button>
                </div>
                
                <div id="entryTypeToggle" class="hidden bg-slate-50 p-1.5 rounded-2xl flex items-center w-64 border border-slate-100">
                    <button type="button" onclick="setEntryType('text')" id="btnTextEntry" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200">
                        Text / Paper
                    </button>
                    <button type="button" onclick="setEntryType('table')" id="btnTableEntry" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700">
                        Excel Table
                    </button>
                </div>
            </div>

            <input type="hidden" name="submission_mode" id="submissionMode" value="upload">
            <input type="hidden" name="entry_type" id="entryType" value="text">

            <!-- ── UPLOAD SECTION ── -->
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

            <!-- ── ELECTRONIC ENTRY SECTIONS ── -->
            <div id="electronicSection" class="hidden space-y-6">
                <!-- Text Entry -->
                <div id="textEntryBox">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Examination Content / Question Paper</label>
                    <textarea name="content_text" id="contentTextarea" rows="12" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 bg-slate-50/50 font-mono" placeholder="Type your examination questions here..."></textarea>
                </div>

                <!-- Table Entry (Excel Style) -->
                <div id="tableEntryBox" class="hidden">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Results Entry Sheet</label>
                        <button type="button" onclick="addRow()" class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-emerald-100 hover:bg-emerald-100 transition-colors">
                            + Add Student Row
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table id="resultsTable" class="w-full text-sm border-collapse bg-white">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">S/N</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">Student ID / Matric</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">Student Name</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">CA (40)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">Exam (60)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">Total</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-200">Grade</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Initial Rows -->
                                <?php for($i=1; $i<=5; $i++): ?>
                                <tr class="border-b border-slate-100 group">
                                    <td class="px-4 py-1 text-center font-mono text-xs text-slate-400 border-r border-slate-100"><?= $i ?></td>
                                    <td class="px-2 py-1 border-r border-slate-100">
                                        <input type="text" class="w-full px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent" placeholder="e.g. PSC123">
                                    </td>
                                    <td class="px-2 py-1 border-r border-slate-100">
                                        <input type="text" class="w-full px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent" placeholder="Full Name">
                                    </td>
                                    <td class="px-2 py-1 border-r border-slate-100">
                                        <input type="number" oninput="calcTotal(this)" class="w-20 mx-auto block px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent text-center" placeholder="0">
                                    </td>
                                    <td class="px-2 py-1 border-r border-slate-100">
                                        <input type="number" oninput="calcTotal(this)" class="w-20 mx-auto block px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent text-center" placeholder="0">
                                    </td>
                                    <td class="px-2 py-1 border-r border-slate-100 bg-slate-50/30 text-center font-bold text-slate-700">0</td>
                                    <td class="px-2 py-1 border-r border-slate-100 text-center font-black text-brand-600">F</td>
                                    <td class="px-2 py-1 text-center">
                                        <button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <textarea name="content" id="finalContent" class="hidden"></textarea>

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
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">I hereby certify that the content/file above is accurate and I authorize the system to attach my digital signature to this submission.</p>
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
    const toggle    = document.getElementById('entryTypeToggle');
    const modeInput = document.getElementById('submissionMode');
    const fileInput = document.getElementById('fileInput');

    modeInput.value = mode;

    if (mode === 'upload') {
        uploadBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200';
        electBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700';
        uploadSec.classList.remove('hidden');
        electSec.classList.add('hidden');
        toggle.classList.add('hidden');
        fileInput.required = true;
    } else {
        electBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200';
        uploadBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700';
        uploadSec.classList.add('hidden');
        electSec.classList.remove('hidden');
        toggle.classList.remove('hidden');
        fileInput.required = false;
        autoSwitchEntryMode();
    }
}

function setEntryType(type) {
    const textBtn = document.getElementById('btnTextEntry');
    const tableBtn = document.getElementById('btnTableEntry');
    const textBox = document.getElementById('textEntryBox');
    const tableBox = document.getElementById('tableEntryBox');
    const typeInput = document.getElementById('entryType');

    typeInput.value = type;

    if (type === 'text') {
        textBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200';
        tableBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700';
        textBox.classList.remove('hidden');
        tableBox.classList.add('hidden');
    } else {
        tableBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200';
        textBtn.className = 'flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700';
        textBox.classList.add('hidden');
        tableBox.classList.remove('hidden');
    }
}

function autoSwitchEntryMode() {
    const subType = document.getElementById('submissionType').value;
    if (subType === 'exam_questions') {
        setEntryType('text');
    } else {
        setEntryType('table');
    }
}

function addRow() {
    const body = document.getElementById('tableBody');
    const rowCount = body.rows.length + 1;
    const row = document.createElement('tr');
    row.className = 'border-b border-slate-100 group';
    row.innerHTML = `
        <td class="px-4 py-1 text-center font-mono text-xs text-slate-400 border-r border-slate-100">${rowCount}</td>
        <td class="px-2 py-1 border-r border-slate-100">
            <input type="text" class="w-full px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent" placeholder="e.g. PSC123">
        </td>
        <td class="px-2 py-1 border-r border-slate-100">
            <input type="text" class="w-full px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent" placeholder="Full Name">
        </td>
        <td class="px-2 py-1 border-r border-slate-100">
            <input type="number" oninput="calcTotal(this)" class="w-20 mx-auto block px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent text-center" placeholder="0">
        </td>
        <td class="px-2 py-1 border-r border-slate-100">
            <input type="number" oninput="calcTotal(this)" class="w-20 mx-auto block px-2 py-2 border-0 focus:ring-2 focus:ring-brand-500/20 rounded-lg text-sm bg-transparent text-center" placeholder="0">
        </td>
        <td class="px-2 py-1 border-r border-slate-100 bg-slate-50/30 text-center font-bold text-slate-700">0</td>
        <td class="px-2 py-1 border-r border-slate-100 text-center font-black text-brand-600">F</td>
        <td class="px-2 py-1 text-center">
            <button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    `;
    body.appendChild(row);
}

function removeRow(btn) {
    btn.closest('tr').remove();
    // Re-index S/N
    const rows = document.getElementById('tableBody').rows;
    for (let i = 0; i < rows.length; i++) {
        rows[i].cells[0].textContent = i + 1;
    }
}

function calcTotal(input) {
    const row = input.closest('tr');
    const ca = parseFloat(row.cells[3].querySelector('input').value) || 0;
    const exam = parseFloat(row.cells[4].querySelector('input').value) || 0;
    const total = ca + exam;
    row.cells[5].textContent = total;
    
    let grade = 'F';
    if (total >= 70) grade = 'A';
    else if (total >= 60) grade = 'B';
    else if (total >= 50) grade = 'C';
    else if (total >= 45) grade = 'D';
    else if (total >= 40) grade = 'E';
    
    row.cells[6].textContent = grade;
    row.cells[6].className = `px-2 py-1 border-r border-slate-100 text-center font-black ${total >= 40 ? 'text-brand-600' : 'text-red-500'}`;
}

// Handle form submit to serialize table
document.getElementById('submissionForm').onsubmit = function() {
    const mode = document.getElementById('submissionMode').value;
    const type = document.getElementById('entryType').value;
    const finalContent = document.getElementById('finalContent');

    if (mode === 'electronic') {
        if (type === 'text') {
            finalContent.value = document.getElementById('contentTextarea').value;
        } else {
            const data = [];
            const rows = document.getElementById('tableBody').rows;
            for (let i = 0; i < rows.length; i++) {
                const r = rows[i];
                data.push({
                    id: r.cells[1].querySelector('input').value,
                    name: r.cells[2].querySelector('input').value,
                    ca: r.cells[3].querySelector('input').value,
                    exam: r.cells[4].querySelector('input').value,
                    total: r.cells[5].textContent,
                    grade: r.cells[6].textContent
                });
            }
            finalContent.value = JSON.stringify({ type: 'table', data: data });
        }
    }
};
</script>
