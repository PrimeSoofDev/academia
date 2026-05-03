<?php
$pageTitle = 'Submission Details';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Submissions', 'href' => '/submissions'],
    ['label' => 'Details']
];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Content & Form -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight"><?= str_replace('_', ' ', ucwords($submission['type'], '_')) ?></h3>
                    <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($submission['course_code']) ?> — <?= htmlspecialchars($submission['course_title']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                        <?= $submission['status'] === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' ?>">
                        <?= htmlspecialchars($submission['status']) ?>
                    </span>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Content View -->
                <?php if ($submission['file_path']): ?>
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-2xl shadow-sm">📄</div>
                            <div>
                                <p class="font-bold text-slate-800">Submitted Document</p>
                                <p class="text-xs text-slate-500">File-based Academic Material</p>
                            </div>
                        </div>
                        <a href="<?= url($submission['file_path']) ?>" target="_blank" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all">Download / View</a>
                    </div>
                <?php endif; ?>

                <?php if ($submission['content']): ?>
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Electronic Content / Entry</h4>
                    <?php 
                    $data = json_decode($submission['content'], true);
                    if ($data && isset($data['type'])):
                        if ($data['type'] === 'table'):
                    ?>
                        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3 font-bold text-slate-500 uppercase">Student ID</th>
                                        <th class="px-4 py-3 font-bold text-slate-500 uppercase">Name</th>
                                        <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase">CA</th>
                                        <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase">Exam</th>
                                        <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase">Total</th>
                                        <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php foreach ($data['data'] as $row): ?>
                                    <tr>
                                        <td class="px-4 py-3 font-mono"><?= htmlspecialchars($row['id']) ?></td>
                                        <td class="px-4 py-3 font-bold"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="px-4 py-3 text-center"><?= htmlspecialchars($row['ca']) ?></td>
                                        <td class="px-4 py-3 text-center"><?= htmlspecialchars($row['exam']) ?></td>
                                        <td class="px-4 py-3 text-center font-extrabold"><?= htmlspecialchars($row['total']) ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2.5 py-1 rounded-lg font-black <?= (int)$row['total'] >= 40 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
                                                <?= htmlspecialchars($row['grade']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($data['type'] === 'rich-text'): ?>
                        <div class="a4-view-container p-8 bg-slate-50 rounded-3xl border border-slate-200 flex justify-center overflow-x-auto">
                            <div class="bg-white p-12 shadow-lg min-h-[300px] w-full max-w-[210mm] prose prose-slate">
                                <?= $data['data'] ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php else: ?>
                        <div class="p-6 rounded-2xl bg-slate-50 text-slate-700 text-sm border font-mono whitespace-pre-wrap"><?= htmlspecialchars($submission['content']) ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($submission['remarks']): ?>
                <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100">
                    <h4 class="text-xs font-bold text-amber-800 uppercase tracking-widest mb-2">Reviewer Feedback</h4>
                    <p class="text-sm text-amber-700 font-medium"><?= htmlspecialchars($submission['remarks']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Approval Form (HOD/Dean) -->
        <?php if (in_array(Auth::role(), ['hod', 'dean', 'superadmin', 'vc']) && $submission['status'] !== 'approved'): ?>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-lg overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Review & Approval</h3>
                <div class="bg-slate-100 p-1 rounded-xl flex items-center border border-slate-200">
                    <button type="button" onclick="setAppSigMode('saved')" id="btnAppSigSaved" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-white text-brand-600 shadow-sm border border-slate-200">Use Profile</button>
                    <button type="button" onclick="setAppSigMode('draw')" id="btnAppSigDraw" class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg text-slate-500">Draw New</button>
                </div>
            </div>
            <form action="<?= url("/submissions/{$submission['id']}/approve") ?>" method="POST" id="approvalForm" class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Review Remarks</label>
                    <textarea name="remarks" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 outline-none text-sm text-slate-700" placeholder="Enter approval comments..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div id="appSavedSigBox" class="h-32 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-center p-2">
                        <?php if (!empty(Auth::user()['signature_path'])): ?>
                            <img src="<?= url(Auth::user()['signature_path']) ?>" class="max-w-full max-h-full object-contain mix-blend-multiply">
                        <?php else: ?>
                            <p class="text-[9px] text-red-500 font-bold uppercase">No Profile Signature</p>
                        <?php endif; ?>
                    </div>
                    <div id="appDrawSigBox" class="hidden">
                        <canvas id="app-sig-canvas" width="256" height="128" class="w-full h-32 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50"></canvas>
                        <button type="button" onclick="clearAppSig()" class="text-[10px] font-bold text-red-500 uppercase mt-1">Clear</button>
                    </div>
                    
                    <input type="hidden" name="drawn_signature" id="appDrawnSigInput">

                    <div class="flex gap-3">
                        <button type="submit" name="action" value="reject" class="flex-1 px-4 py-3.5 border border-red-200 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50 transition-colors">Reject</button>
                        <button type="submit" name="action" value="approve" class="flex-[2] px-4 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20">Approve & Sign</button>
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Signature Tracking -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6 tracking-tight">Signature Ledger</h3>
            <div class="space-y-8 relative">
                <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-slate-100"></div>

                <!-- Lecturer -->
                <div class="relative flex gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center z-10 shrink-0 shadow-sm border border-brand-100 text-xl">👨‍🏫</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lecturer</p>
                        <p class="font-bold text-slate-800 truncate"><?= htmlspecialchars($submission['lecturer_name']) ?></p>
                        <?php if ($submission['lecturer_sig_path']): ?>
                        <div class="mt-2 p-2 bg-slate-50 rounded-xl border border-slate-100">
                            <img src="<?= url($submission['lecturer_sig_path']) ?>" class="h-10 object-contain mix-blend-multiply">
                            <p class="text-[9px] text-slate-400 mt-1 text-center font-mono"><?= date('Y-m-d H:i', strtotime($submission['lecturer_signed_at'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- HOD -->
                <div class="relative flex gap-6">
                    <div class="w-12 h-12 rounded-2xl <?= $submission['hod_signed_at'] ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-100' ?> flex items-center justify-center z-10 shrink-0 shadow-sm border text-xl"><?= $submission['hod_signed_at'] ? '✅' : '⏳' ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Head of Department</p>
                        <p class="font-bold text-slate-800 truncate"><?= $submission['hod_name'] ?? 'Pending' ?></p>
                        <?php if ($submission['hod_sig_path']): ?>
                        <div class="mt-2 p-2 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <img src="<?= url($submission['hod_sig_path']) ?>" class="h-10 object-contain mix-blend-multiply">
                            <p class="text-[9px] text-emerald-600 mt-1 text-center font-mono"><?= date('Y-m-d H:i', strtotime($submission['hod_signed_at'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Dean -->
                <div class="relative flex gap-6">
                    <div class="w-12 h-12 rounded-2xl <?= $submission['dean_signed_at'] ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-100' ?> flex items-center justify-center z-10 shrink-0 shadow-sm border text-xl"><?= $submission['dean_signed_at'] ? '🏛️' : '⏳' ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Dean of Faculty</p>
                        <p class="font-bold text-slate-800 truncate"><?= $submission['dean_name'] ?? 'Pending' ?></p>
                        <?php if ($submission['dean_sig_path']): ?>
                        <div class="mt-2 p-2 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <img src="<?= url($submission['dean_sig_path']) ?>" class="h-10 object-contain mix-blend-multiply">
                            <p class="text-[9px] text-emerald-600 mt-1 text-center font-mono"><?= date('Y-m-d H:i', strtotime($submission['dean_signed_at'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Approval Signature Canvas
const appCanvas = document.getElementById('app-sig-canvas');
if (appCanvas) {
    const appCtx = appCanvas.getContext('2d');
    let appDrawing = false;
    appCanvas.addEventListener('mousedown', (e) => { appDrawing = true; drawApp(e); });
    appCanvas.addEventListener('mousemove', drawApp);
    appCanvas.addEventListener('mouseup', () => { appDrawing = false; appCtx.beginPath(); });
    appCanvas.addEventListener('mouseout', () => { appDrawing = false; appCtx.beginPath(); });

    function drawApp(e) {
        if (!appDrawing) return;
        appCtx.lineWidth = 2;
        appCtx.lineCap = 'round';
        appCtx.strokeStyle = '#0f172a';
        const rect = appCanvas.getBoundingClientRect();
        appCtx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        appCtx.stroke();
        appCtx.beginPath();
        appCtx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    }
}

function clearAppSig() {
    const appCtx = appCanvas.getContext('2d');
    appCtx.clearRect(0, 0, appCanvas.width, appCanvas.height);
}

function setAppSigMode(mode) {
    const savedBtn = document.getElementById('btnAppSigSaved');
    const drawBtn = document.getElementById('btnAppSigDraw');
    const savedBox = document.getElementById('appSavedSigBox');
    const drawBox = document.getElementById('appDrawSigBox');
    
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

const approvalForm = document.getElementById('approvalForm');
if (approvalForm) {
    approvalForm.onsubmit = function() {
        if (document.getElementById('btnAppSigDraw').classList.contains('bg-white')) {
            document.getElementById('appDrawnSigInput').value = appCanvas.toDataURL();
        }
    };
}
</script>
