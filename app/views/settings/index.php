<div class="max-w-4xl mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Account Settings</h1>
            <p class="text-slate-500 mt-1">Manage your security, preferences, and account details.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1 space-y-2">
            <button class="w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-brand-600 text-white font-bold shadow-lg shadow-brand-500/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Security</span>
            </button>
            <button class="w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl text-slate-500 hover:bg-slate-100 font-bold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Preferences</span>
            </button>
            <a href="<?= url('/profile') ?>" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl text-slate-500 hover:bg-slate-100 font-bold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profile Details</span>
            </a>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Security Section -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Change Password</h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Security & Access</p>
                    </div>
                </div>

                <form action="<?= url('/settings/password') ?>" method="POST" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Current Password</label>
                        <input type="password" name="current_password" required
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-slate-600 font-medium"
                            placeholder="Enter current password">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">New Password</label>
                            <input type="password" name="new_password" required
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-slate-600 font-medium"
                                placeholder="New password">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" required
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-slate-600 font-medium"
                                placeholder="Repeat new password">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full md:w-auto px-8 py-4 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transform hover:-translate-y-0.5 transition-all shadow-lg shadow-slate-200">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preferences Section (Coming Soon) -->
            <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-dashed border-slate-200 flex flex-col items-center justify-center text-center py-12">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-slate-400 mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">System Preferences</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-xs">Customization options for theme, notifications, and language are currently being optimized.</p>
            </div>
        </div>
    </div>
</div>
