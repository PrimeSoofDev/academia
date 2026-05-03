<?php
$pageTitle = 'Edit User';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Users', 'href' => '/users'],
    ['label' => htmlspecialchars($user['name']), 'href' => '/users/' . $user['id']],
    ['label' => 'Edit']
];
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Edit Profile</h3>
            <p class="text-sm text-slate-500 mt-1">Update information for <?= htmlspecialchars($user['name']) ?></p>
        </div>
        
        <div class="p-6">
            <form action="<?= url('/users/' . $user['id'] . '/edit') ?>" method="POST" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required value="<?= htmlspecialchars($user['name']) ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required value="<?= htmlspecialchars($user['email']) ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Account Role <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required onchange="toggleRoleFields()" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-medium">
                            <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="lecturer" <?= $user['role'] === 'lecturer' ? 'selected' : '' ?>>Lecturer</option>
                            <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Administrative Staff</option>
                            <option value="hod" <?= $user['role'] === 'hod' ? 'selected' : '' ?>>Head of Department (HOD)</option>
                            <option value="dean" <?= $user['role'] === 'dean' ? 'selected' : '' ?>>Faculty Dean</option>
                            <option value="vc" <?= $user['role'] === 'vc' ? 'selected' : '' ?>>Vice Chancellor (VC)</option>
                            <option value="superadmin" <?= $user['role'] === 'superadmin' ? 'selected' : '' ?>>System Admin</option>
                        </select>
                    </div>

                    <div>
                        <label id="identifierLabel" for="identifier" class="block text-sm font-medium text-slate-700 mb-1">Identification Number</label>
                        <input type="text" name="identifier" id="identifier" value="<?= htmlspecialchars($user['matric_number'] ?? $user['staff_id'] ?? '') ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white font-mono uppercase">
                    </div>
                </div>

                <!-- Academic Placement Section -->
                <div id="academicSection" class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Academic Placement</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="faculty_id" class="block text-sm font-medium text-slate-700 mb-1">Faculty</label>
                            <select name="faculty_id" id="faculty_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                                <option value="">-- Select Faculty --</option>
                                <?php foreach ($faculties as $fac): ?>
                                    <option value="<?= $fac['id'] ?>" <?= $user['faculty_id'] == $fac['id'] ? 'selected' : '' ?>><?= htmlspecialchars($fac['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                            <select name="department_id" id="department_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                                <option value="">-- Select Department --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= $user['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    </div>
                    
                    <div>
                        <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                        <select name="gender" id="gender" class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                            <option value="">-- Select --</option>
                            <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Reset Password</label>
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep current password"
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 text-sm transition-all bg-white">
                    <p class="text-xs text-slate-500 mt-1">Only fill this if you want to force a password change.</p>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="<?= url('/users/' . $user['id']) ?>" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    const identifierLabel = document.getElementById('identifierLabel');
    const academicSection = document.getElementById('academicSection');

    // Toggle identifier label
    if (role === 'student') {
        identifierLabel.innerText = 'Matriculation Number';
    } else {
        identifierLabel.innerText = 'Staff ID Number';
    }

    // Toggle academic section visibility
    if (['staff', 'vc', 'superadmin'].includes(role)) {
        academicSection.style.display = 'none';
        // Note: We don't auto-clear fields on edit form so they don't accidentally lose data if they click wrong
    } else {
        academicSection.style.display = 'block';
    }
}

// Run on load
toggleRoleFields();
</script>
