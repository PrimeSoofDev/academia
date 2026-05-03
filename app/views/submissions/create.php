<?php
$pageTitle = 'New Academic Submission';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions', 'href' => '/submissions'],
    ['label' => 'New']
];
?>

<style>
    .a4-container { background: #f1f5f9; padding: 40px 20px; display: flex; justify-content: center; overflow-x: auto; }
    .a4-paper { width: 210mm; min-height: 297mm; background: white; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); padding: 25mm; outline: none; position: relative; }
    .editor-toolbar { position: sticky; top: 0; z-index: 50; background: white; border-bottom: 1px solid #e2e8f0; padding: 8px; display: flex; flex-wrap: wrap; gap: 4px; border-radius: 12px 12px 0 0; }
    .toolbar-btn { padding: 6px; border-radius: 6px; color: #475569; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
    .toolbar-btn:hover { background: #f1f5f9; color: #0f172a; }
    select.toolbar-select { font-size: 11px; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; outline: none; }
    
    #sig-canvas { border: 2px dashed #e2e8f0; border-radius: 12px; cursor: crosshair; background: #fafafa; }
</style>

<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 px-8 py-10 text-white">
            <h2 class="text-3xl font-extrabold tracking-tight">Submit Academic Material</h2>
            <p class="text-brand-100 mt-2 opacity-90">Choose to upload a file or enter content electronically for approval.</p>
        </div>

        <form action="<?= url('/submissions/create') ?>" method="POST" enctype="multipart/form-data" id="submissionForm" class="p-0">
            <div class="p-8 space-y-8">
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
                        <button type="button" onclick="setMode('upload')" id="btnUpload" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200">Upload File</button>
                        <button type="button" onclick="setMode('electronic')" id="btnElectronic" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700">Electronic Entry</button>
                    </div>
                    
                    <div id="entryTypeToggle" class="hidden bg-slate-50 p-1.5 rounded-2xl flex items-center w-64 border border-slate-100">
                        <button type="button" onclick="setEntryType('text')" id="btnTextEntry" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all bg-white text-brand-600 shadow-sm border border-slate-200">A4 Paper</button>
                        <button type="button" onclick="setEntryType('table')" id="btnTableEntry" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition-all text-slate-500 hover:text-slate-700">Excel Table</button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="submission_mode" id="submissionMode" value="upload">
            <input type="hidden" name="entry_type" id="entryType" value="text">

            <!-- UPLOAD SECTION -->
            <div id="uploadSection" class="p-8 pt-0">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Upload Document</label>
                <div class="relative group">
                    <input type="file" name="file" id="fileInput" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="p-10 border-2 border-dashed border-slate-200 group-hover:border-brand-400 rounded-2xl bg-slate-50 transition-all text-center">
                        <svg class="w-12 h-12 text-brand-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-sm font-bold text-slate-700">Click to upload or drag & drop</p>
                    </div>
                </div>
            </div>

            <!-- ELECTRONIC ENTRY -->
            <div id="electronicSection" class="hidden">
                <div id="textEntryBox">
                    <div class="editor-toolbar mx-8">
                        <button type="button" onclick="execCmd('bold')" class="toolbar-btn"><b>B</b></button>
                        <button type="button" onclick="execCmd('italic')" class="toolbar-btn"><i>I</i></button>
                        <button type="button" onclick="execCmd('underline')" class="toolbar-btn"><u>U</u></button>
                        <select onchange="execCmd('fontName', this.value)" class="toolbar-select"><option value="Arial">Arial</option><option value="Times New Roman">Times New Roman</option></select>
                        <input type="color" onchange="execCmd('foreColor', this.value)" class="w-8 h-8">
                    </div>
                    <div class="a4-container">
                        <div id="a4Editor" class="a4-paper" contenteditable="true">
                            <h1 style="text-align:center">ACADEMIC MATERIAL</h1><hr><p>Start typing...</p>
                        </div>
                    </div>
                </div>

                <div id="tableEntryBox" class="hidden p-8 pt-0">
                    <button type="button" onclick="addRow()" class="mb-3 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg border border-emerald-100 uppercase">+ Add Row</button>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table id="resultsTable" class="w-full text-sm bg-white">
                            <thead class="bg-slate-50 border-b"><tr><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">S/N</th><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">Student ID</th><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">Name</th><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">CA</th><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">Exam</th><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">Total</th><th class="px-4 py-3 border-r text-[10px] font-bold text-slate-400 uppercase">Grade</th><th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase">Action</th></tr></thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <textarea name="content" id="finalContent" class="hidden"></textarea>

            <!-- DIGITAL SIGNING BOX -->
            <div class="p-8 pt-0 space-y-6">
                <hr class="border-slate-100">
                <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Digital Signing Authorization
                        </h3>
                        <div class="bg-slate-50 p-1 rounded-xl flex items-center border border-slate-200">
                            <button type="button" onclick="setSigMode('saved')" id="btnSigSaved" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-white text-brand-600 shadow-sm border border-slate-200">Use Profile</button>
                            <button type="button" onclick="setSigMode('draw')" id="btnSigDraw" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg text-slate-500">Draw New</button>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <!-- Saved Signature View -->
                        <div id="savedSigBox" class="w-full md:w-64 h-32 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2">
                            <?php if (!empty($user['signature_path'])): ?>
                                <img src="<?= url($user['signature_path']) ?>" class="max-w-full max-h-full object-contain mix-blend-multiply">
                            <?php else: ?>
                                <div class="text-center p-2"><p class="text-[9px] text-red-500 font-bold uppercase">No Profile Signature Found</p></div>
                            <?php endif; ?>
                        </div>

                        <!-- Draw Signature Canvas -->
                        <div id="drawSigBox" class="hidden w-full md:w-64">
                            <canvas id="sig-canvas" width="256" height="128" class="w-full h-32"></canvas>
                            <div class="flex justify-end mt-2">
                                <button type="button" onclick="clearSig()" class="text-[10px] font-bold text-red-500 uppercase hover:underline">Clear Canvas</button>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required name="confirm_sign" class="mt-1 w-5 h-5 rounded border-slate-300 text-brand-600">
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Confirm Electronic Signature</p>
                                    <p class="text-xs text-slate-500 mt-0.5">I certify the accuracy of this submission and authorize the attachment of my digital signature.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="drawn_signature" id="drawnSigInput">

                <div class="flex gap-4">
                    <a href="<?= url('/submissions') ?>" class="flex-1 px-6 py-4 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors text-center">Cancel</a>
                    <button type="submit" class="flex-[2] px-6 py-4 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 font-bold uppercase tracking-widest text-xs">
                        Finalize & Sign Submission
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Canvas Logic
const canvas = document.getElementById('sig-canvas');
const ctx = canvas.getContext('2d');
let drawing = false;

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseout', stopDrawing);

function startDrawing(e) { drawing = true; draw(e); }
function stopDrawing() { drawing = false; ctx.beginPath(); }
function draw(e) {
    if (!drawing) return;
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#0f172a';
    
    const rect = canvas.getBoundingClientRect();
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
}

function clearSig() { ctx.clearRect(0, 0, canvas.width, canvas.height); }

function setSigMode(mode) {
    const savedBtn = document.getElementById('btnSigSaved');
    const drawBtn = document.getElementById('btnSigDraw');
    const savedBox = document.getElementById('savedSigBox');
    const drawBox = document.getElementById('drawSigBox');
    
    if (mode === 'saved') {
        savedBtn.className = 'px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-white text-brand-600 shadow-sm border border-slate-200';
        drawBtn.className = 'px-3 py-1 text-[10px] font-bold uppercase rounded-lg text-slate-500';
        savedBox.classList.remove('hidden');
        drawBox.classList.add('hidden');
    } else {
        drawBtn.className = 'px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-white text-brand-600 shadow-sm border border-slate-200';
        savedBtn.className = 'px-3 py-1 text-[10px] font-bold uppercase rounded-lg text-slate-500';
        savedBox.classList.add('hidden');
        drawBox.classList.remove('hidden');
    }
}

function execCmd(cmd, val = null) { document.execCommand(cmd, false, val); }

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
    if (subType === 'exam_questions') { setEntryType('text'); } else { setEntryType('table'); }
}

function addRow() {
    const body = document.getElementById('tableBody');
    const rowCount = body.rows.length + 1;
    const row = document.createElement('tr');
    row.className = 'border-b border-slate-100 group';
    row.innerHTML = `<td class="px-4 py-1 text-center font-mono text-xs text-slate-400">${rowCount}</td><td><input type="text" class="w-full px-2 py-2 border-0 text-sm bg-transparent"></td><td><input type="text" class="w-full px-2 py-2 border-0 text-sm bg-transparent"></td><td><input type="number" oninput="calcTotal(this)" class="w-20 mx-auto block border-0 text-sm bg-transparent text-center"></td><td><input type="number" oninput="calcTotal(this)" class="w-20 mx-auto block border-0 text-sm bg-transparent text-center"></td><td class="text-center font-bold">0</td><td class="text-center font-black text-brand-600">F</td><td class="text-center"><button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100">×</button></td>`;
    body.appendChild(row);
}

function removeRow(btn) { btn.closest('tr').remove(); const rows = document.getElementById('tableBody').rows; for (let i = 0; i < rows.length; i++) { rows[i].cells[0].textContent = i + 1; } }

function calcTotal(input) {
    const row = input.closest('tr');
    const ca = parseFloat(row.cells[3].querySelector('input').value) || 0;
    const exam = parseFloat(row.cells[4].querySelector('input').value) || 0;
    const total = ca + exam;
    row.cells[5].textContent = total;
    let grade = 'F';
    if (total >= 70) grade = 'A'; else if (total >= 60) grade = 'B'; else if (total >= 50) grade = 'C'; else if (total >= 45) grade = 'D'; else if (total >= 40) grade = 'E';
    row.cells[6].textContent = grade;
    row.cells[6].className = `text-center font-black ${total >= 40 ? 'text-brand-600' : 'text-red-500'}`;
}

document.getElementById('submissionForm').onsubmit = function() {
    const mode = document.getElementById('submissionMode').value;
    const type = document.getElementById('entryType').value;
    const finalContent = document.getElementById('finalContent');
    const drawnSigInput = document.getElementById('drawnSigInput');

    if (document.getElementById('btnSigDraw').classList.contains('bg-white')) {
        drawnSigInput.value = canvas.toDataURL();
    }

    if (mode === 'electronic') {
        if (type === 'text') {
            finalContent.value = JSON.stringify({ type: 'rich-text', data: document.getElementById('a4Editor').innerHTML });
        } else {
            const data = [];
            const rows = document.getElementById('tableBody').rows;
            for (let i = 0; i < rows.length; i++) {
                const r = rows[i];
                data.push({ id: r.cells[1].querySelector('input').value, name: r.cells[2].querySelector('input').value, ca: r.cells[3].querySelector('input').value, exam: r.cells[4].querySelector('input').value, total: r.cells[5].textContent, grade: r.cells[6].textContent });
            }
            finalContent.value = JSON.stringify({ type: 'table', data: data });
        }
    }
};

// Add 3 rows by default
for(let i=0; i<3; i++) addRow();
</script>
