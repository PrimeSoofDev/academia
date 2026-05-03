<?php
$pageTitle = 'Degree Certificate';
$universityName = $university['name'] ?? 'ACADEMIA UNIVERSITY';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap');

    .cert-bg {
        background: #fdfdfd;
        padding: 60px;
        position: relative;
        overflow: hidden;
    }
    .cert-border {
        border: 20px solid #1e293b;
        padding: 40px;
        position: relative;
        background: white;
        box-shadow: inset 0 0 100px rgba(0,0,0,0.02);
    }
    .cert-inner-border {
        border: 2px solid #e2e8f0;
        padding: 60px;
        height: 100%;
        display: flex;
        flex-col;
        align-items: center;
        text-align: center;
        position: relative;
    }
    .cert-logo { width: 120px; height: 120px; margin-bottom: 30px; filter: grayscale(1); opacity: 0.8; }
    .cert-university { font-family: 'Cinzel', serif; font-weight: 900; font-size: 3rem; color: #0f172a; margin-bottom: 10px; letter-spacing: 4px; }
    .cert-tagline { font-family: 'Cinzel', serif; font-size: 0.9rem; color: #64748b; margin-bottom: 50px; letter-spacing: 8px; font-weight: 700; }
    .cert-title { font-family: 'Cinzel', serif; font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 40px; text-decoration: underline; text-underline-offset: 8px; }
    .cert-text { font-family: 'Playfair Display', serif; font-size: 1.25rem; color: #475569; line-height: 1.8; max-width: 800px; margin: 0 auto; }
    .cert-name { font-family: 'Great Vibes', cursive; font-size: 4.5rem; color: #0f172a; margin: 20px 0; }
    .cert-degree { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 900; color: #0f172a; font-style: italic; margin-bottom: 40px; }
    .cert-footer { width: 100%; display: flex; justify-content: space-between; align-items: flex-end; margin-top: 80px; padding: 0 40px; }
    .cert-signatory { width: 250px; text-align: center; position: relative; }
    .cert-signature-img { height: 70px; margin: 0 auto -10px; mix-blend-multiply; }
    .cert-stamp-img { position: absolute; top: -40px; right: -20px; height: 120px; opacity: 0.7; rotate: 15deg; mix-blend-multiply; }
    .cert-sign-line { border-top: 2px solid #0f172a; padding-top: 10px; font-family: 'Cinzel', serif; font-size: 0.75rem; font-weight: 900; color: #0f172a; }
    .cert-security { position: absolute; bottom: 30px; right: 30px; width: 100px; height: 100px; opacity: 0.1; }

    @media print {
        body * { visibility: hidden; }
        .cert-bg, .cert-bg * { visibility: visible; }
        .cert-bg { position: absolute; left: 0; top: 0; width: 100%; height: 100%; padding: 0; }
        .print-btn { display: none; }
    }
</style>

<div class="max-w-6xl mx-auto py-12">
    <div class="flex justify-end mb-8 print-btn">
        <button onclick="window.print()" class="px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-2xl shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM15 11h.01M11 17h4"/></svg>
            Download / Print Official Certificate
        </button>
    </div>

    <div class="cert-bg shadow-2xl rounded-3xl border border-slate-200">
        <div class="cert-border">
            <div class="cert-inner-border">
                <div class="flex flex-col items-center">
                    <?php if (!empty($university['logo'])): ?>
                        <img src="<?= url($university['logo'] ?? '') ?>" class="cert-logo">
                    <?php else: ?>
                        <div class="cert-logo flex items-center justify-center text-6xl">🏛️</div>
                    <?php endif; ?>
                    
                    <h1 class="cert-university"><?= htmlspecialchars($universityName) ?></h1>
                    <p class="cert-tagline">EXCELLENCE • KNOWLEDGE • CHARACTER</p>
                    
                    <h2 class="cert-title">DEGREE OF GRADUATION</h2>
                    
                    <p class="cert-text">This is to certify that the Governing Council of the University, upon the recommendation of the Senate, has conferred upon</p>
                    
                    <h3 class="cert-name"><?= htmlspecialchars($user['name']) ?></h3>
                    
                    <p class="cert-text">the degree of</p>
                    
                    <h4 class="cert-degree"><?= htmlspecialchars($user['degree_name'] ?? 'Bachelor of Science') ?></h4>
                    
                    <p class="cert-text">With all the rights, privileges, and honors appertaining thereto, in recognition of the successful completion of the prescribed course of study on this</p>
                    
                    <p class="cert-text font-bold mt-4">
                        <?= date('jS', strtotime($user['graduation_date'] ?? 'now')) ?> day of <?= date('F, Y', strtotime($user['graduation_date'] ?? 'now')) ?>
                    </p>
                </div>

                <div class="cert-footer">
                    <?php 
                    $vc = null;
                    $registrar = null;
                    foreach ($officials as $off) {
                        if ($off['role'] === 'vc') $vc = $off;
                        else $registrar = $off;
                    }
                    ?>
                    
                    <div class="cert-signatory">
                        <?php if ($registrar && $registrar['signature_path']): ?>
                            <img src="<?= url($registrar['signature_path']) ?>" class="cert-signature-img">
                        <?php endif; ?>
                        <?php if ($registrar && $registrar['stamp_path']): ?>
                            <img src="<?= url($registrar['stamp_path']) ?>" class="cert-stamp-img">
                        <?php endif; ?>
                        <div class="cert-sign-line">REGISTRAR</div>
                    </div>

                    <div class="cert-signatory">
                        <?php if ($vc && $vc['signature_path']): ?>
                            <img src="<?= url($vc['signature_path']) ?>" class="cert-signature-img">
                        <?php endif; ?>
                        <?php if ($vc && $vc['stamp_path']): ?>
                            <img src="<?= url($vc['stamp_path']) ?>" class="cert-stamp-img">
                        <?php endif; ?>
                        <div class="cert-sign-line">VICE CHANCELLOR</div>
                    </div>
                </div>

                <div class="cert-security">
                    <!-- Placeholder for security seal -->
                    <svg viewBox="0 0 100 100" class="w-full h-full text-slate-900"><circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="5 5"/></svg>
                </div>
            </div>
        </div>
    </div>
</div>
