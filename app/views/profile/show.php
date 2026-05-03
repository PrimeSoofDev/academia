<?php
$pageTitle = 'My Profile';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'My Profile']
];

$role = $user['role'] ?? 'student';

// Role badge colours
$roleBadge = [
    'superadmin' => 'bg-purple-100 text-purple-700 border-purple-200',
    'vc'         => 'bg-indigo-100 text-indigo-700 border-indigo-200',
    'dean'       => 'bg-blue-100 text-blue-700 border-blue-200',
    'hod'        => 'bg-cyan-100 text-cyan-700 border-cyan-200',
    'lecturer'   => 'bg-teal-100 text-teal-700 border-teal-200',
    'staff'      => 'bg-slate-100 text-slate-700 border-slate-200',
    'student'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
][$role] ?? 'bg-slate-100 text-slate-600 border-slate-200';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ── LEFT: Avatar + Quick Info ── -->
    <div class="lg:col-span-1 space-y-5">

        <!-- Avatar Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="h-28 bg-cover bg-center relative" style="background-image: url('<?= $user['banner_image'] ? url($user['banner_image']) : 'https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=800&q=80' ?>')">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>
            <div class="px-6 pb-6 -mt-12 flex flex-col items-center text-center relative z-10">
                <div class="w-24 h-24 rounded-full bg-slate-200 flex items-center justify-center text-white font-black text-3xl shadow-xl border-4 border-white mb-3 overflow-hidden">
                    <?php if ($user['profile_image']): ?>
                        <img src="<?= url($user['profile_image']) ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center">
                            <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h2 class="font-extrabold text-slate-800 text-xl leading-tight"><?= htmlspecialchars($user['name'] ?? '') ?></h2>
                <p class="text-slate-500 text-sm mt-0.5"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                <span class="mt-3 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border <?= $roleBadge ?>">
                    <?= htmlspecialchars(ucfirst($role)) ?>
                </span>
            </div>
        </div>

        <!-- Quick Details -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Quick Details</h3>

            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">ID Number</p>
                        <p class="font-mono font-semibold text-slate-700 text-sm mt-0.5">
                            <?= htmlspecialchars($user['matric_number'] ?? $user['staff_id'] ?? 'Not Assigned') ?>
                        </p>
                    </div>
                </div>

                <?php if ($user['faculty_name']): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Faculty</p>
                        <p class="font-semibold text-slate-700 text-sm mt-0.5"><?= htmlspecialchars($user['faculty_name']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($user['department_name']): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Department</p>
                        <p class="font-semibold text-slate-700 text-sm mt-0.5"><?= htmlspecialchars($user['department_name']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($user['phone']): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Phone</p>
                        <p class="font-semibold text-slate-700 text-sm mt-0.5"><?= htmlspecialchars($user['phone']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Member Since</p>
                        <p class="font-semibold text-slate-700 text-sm mt-0.5"><?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── RIGHT: Edit Forms ── -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Profile & Banner Images Form -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" id="images">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Profile & Banner Pictures</h3>
                <p class="text-sm text-slate-500 mt-0.5">Upload high-quality images to personalize your profile.</p>
            </div>

            <form action="<?= url('/profile') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                <input type="hidden" name="action" value="images">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Profile Picture</label>
                        <div class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-brand-300 transition-colors bg-slate-50">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-200 shrink-0 border border-slate-200">
                                <img id="profilePreview" src="<?= $user['profile_image'] ? url($user['profile_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'User') ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <input type="file" name="profile_image" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full"
                                       onchange="previewImage(this, 'profilePreview')">
                                <p class="text-[10px] text-slate-400 mt-1">Recommended: Square, min 400x400px</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Banner Image</label>
                        <div class="p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-brand-300 transition-colors bg-slate-50">
                            <div class="h-12 w-full rounded-lg overflow-hidden bg-slate-200 mb-2 border border-slate-200">
                                <img id="bannerPreview" src="<?= $user['banner_image'] ? url($user['banner_image']) : 'https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=800&q=80' ?>" class="w-full h-full object-cover">
                            </div>
                            <input type="file" name="banner_image" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer w-full"
                                   onchange="previewImage(this, 'bannerPreview')">
                            <p class="text-[10px] text-slate-400 mt-1">Recommended: 1200x300px</p>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Digital Signature (for Results/Exams)</label>
                    <div class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-brand-300 transition-colors bg-slate-50">
                        <div class="w-32 h-16 rounded-lg overflow-hidden bg-white shrink-0 border border-slate-200 flex items-center justify-center">
                            <?php if ($user['signature_path']): ?>
                                <img id="sigPreview" src="<?= url($user['signature_path']) ?>" class="max-w-full max-h-full object-contain">
                            <?php else: ?>
                                <div id="sigPreviewPlaceholder" class="text-[10px] text-slate-400 font-bold uppercase tracking-widest px-2 text-center">No Signature Uploaded</div>
                                <img id="sigPreview" class="hidden max-w-full max-h-full object-contain">
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <input type="file" name="signature" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer w-full"
                                   onchange="previewImage(this, 'sigPreview', 'sigPreviewPlaceholder')">
                            <p class="text-[10px] text-slate-400 mt-1">Upload a clear scan of your signature (PNG preferred).</p>
                        </div>
                    </div>
                </div>

                <?php if (in_array(Auth::role(), ['hod', 'dean', 'superadmin', 'vc'])): ?>
                <div class="pt-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Official Digital Stamp (HOD/Dean Only)</label>
                    <div class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-emerald-300 transition-colors bg-slate-50">
                        <div class="w-32 h-24 rounded-lg overflow-hidden bg-white shrink-0 border border-slate-200 flex items-center justify-center">
                            <?php if (!empty($user['stamp_path'])): ?>
                                <img id="stampPreview" src="<?= url($user['stamp_path']) ?>" class="max-w-full max-h-full object-contain">
                            <?php else: ?>
                                <div id="stampPreviewPlaceholder" class="text-[10px] text-slate-400 font-bold uppercase tracking-widest px-2 text-center">No Stamp Uploaded</div>
                                <img id="stampPreview" class="hidden max-w-full max-h-full object-contain">
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <input type="file" name="stamp" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer w-full"
                                   onchange="previewImage(this, 'stampPreview', 'stampPreviewPlaceholder')">
                            <p class="text-[10px] text-slate-400 mt-1">Upload your official office stamp (PNG with transparency preferred).</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-brand-500/20">
                        Update Pictures & Signature
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Info Form -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" id="profile">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Personal Information</h3>
                <p class="text-sm text-slate-500 mt-0.5">Update your name, contact details, and academic placement.</p>
            </div>

            <form action="<?= url('/profile') ?>" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="action" value="profile">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                               placeholder="+234 800 000 0000"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                        <select id="gender" name="gender" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="">-- Select --</option>
                            <option value="male"   <?= ($user['gender'] ?? '') === 'male'   ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other"  <?= ($user['gender'] ?? '') === 'other'  ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-slate-700 mb-1">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth'] ?? '') ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Home Address</label>
                    <textarea id="address" name="address" rows="2"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>

                <!-- Academic Placement (for academic roles) -->
                <?php if (in_array($role, ['student', 'lecturer', 'hod', 'dean'])): ?>
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Academic Placement</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="faculty_id" class="block text-sm font-medium text-slate-700 mb-1">Faculty</label>
                            <select id="faculty_id" name="faculty_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                                <option value="">-- Select Faculty --</option>
                                <?php foreach ($faculties as $fac): ?>
                                    <option value="<?= $fac['id'] ?>" <?= ($user['faculty_id'] ?? '') == $fac['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($fac['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                            <select id="department_id" name="department_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                                <option value="">-- Select Department --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= ($user['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="pt-4 flex items-center justify-end border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" id="password">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Change Password</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Keep your account secure with a strong password.</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            </div>

            <form action="<?= url('/profile') ?>" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="action" value="password">

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1">New Password <span class="text-red-500">*</span></label>
                        <input type="password" id="new_password" name="new_password" required autocomplete="new-password"
                               minlength="8"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"
                               oninput="checkStrength(this.value)">
                        <!-- Strength bar -->
                        <div class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                            <div id="strengthBar" class="h-full rounded-full transition-all duration-300 w-0"></div>
                        </div>
                        <p id="strengthLabel" class="text-[10px] text-slate-400 mt-1"></p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white"
                               oninput="checkMatch()">
                        <p id="matchLabel" class="text-[10px] mt-1"></p>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <p class="text-xs text-slate-400">Minimum 8 characters. Use letters, numbers, and symbols for a stronger password.</p>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-colors shadow-sm shrink-0 ml-4">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '0%',   color: 'bg-slate-200', text: '' },
        { w: '20%',  color: 'bg-red-500',   text: 'Very weak' },
        { w: '40%',  color: 'bg-orange-400',text: 'Weak' },
        { w: '60%',  color: 'bg-amber-400', text: 'Fair' },
        { w: '80%',  color: 'bg-lime-500',  text: 'Strong' },
        { w: '100%', color: 'bg-emerald-500',text: 'Very strong' },
    ];
    const l = levels[score] || levels[0];
    bar.style.width = l.w;
    bar.className = `h-full rounded-full transition-all duration-300 ${l.color}`;
    label.textContent = l.text;
    label.className = `text-[10px] mt-1 ${score < 2 ? 'text-red-500' : score < 4 ? 'text-amber-500' : 'text-emerald-600'}`;
}

function checkMatch() {
    const np = document.getElementById('new_password').value;
    const cp = document.getElementById('confirm_password').value;
    const lbl = document.getElementById('matchLabel');
    if (!cp) { lbl.textContent = ''; return; }
    if (np === cp) {
        lbl.textContent = '✓ Passwords match';
        lbl.className = 'text-[10px] mt-1 text-emerald-600';
    } else {
        lbl.textContent = '✗ Passwords do not match';
        lbl.className = 'text-[10px] mt-1 text-red-500';
    }
}

function previewImage(input, previewId, placeholderId = null) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholderId) {
                const placeholder = document.getElementById(placeholderId);
                if (placeholder) placeholder.classList.add('hidden');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
