<!-- ── LOGIN FORM VIEW ── -->
<!-- Rendered inside layouts/auth.php -->

<div>
    <!-- Heading -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-white tracking-tight">Welcome back</h2>
        <p class="text-slate-400 mt-2 text-sm">Sign in to your university portal</p>
    </div>

    <!-- Error messages -->
    <?php if (!empty($errors)): ?>
    <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl p-4">
        <div class="flex gap-2">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <ul class="space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li class="text-red-300 text-sm"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form id="login-form" action="<?= url('/login') ?>" method="POST" class="space-y-5" novalidate>

        <!-- University Code -->
        <div>
            <label for="tenant_slug" class="block text-sm font-medium text-slate-300 mb-1.5">
                University Code
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <input
                    id="tenant_slug"
                    name="tenant_slug"
                    type="text"
                    required
                    autocomplete="organization"
                    placeholder="e.g. unilag"
                    value="<?= htmlspecialchars($old['slug'] ?? '') ?>"
                    class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white
                           placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                           focus:border-transparent transition-all"
                />
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="you@university.edu"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white
                           placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                           focus:border-transparent transition-all"
                />
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                <a href="<?= url('/forgot-password') ?>" class="text-xs text-brand-400 hover:text-brand-300 transition-colors">
                    Forgot password?
                </a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-10 pr-12 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white
                           placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                           focus:border-transparent transition-all"
                />
                <!-- Toggle visibility -->
                <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors">
                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember me -->
        <div class="flex items-center gap-2">
            <input id="remember" name="remember" type="checkbox"
                   class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-brand-500 focus:ring-brand-500 focus:ring-offset-slate-900"/>
            <label for="remember" class="text-sm text-slate-400 cursor-pointer">Keep me signed in</label>
        </div>

        <!-- Submit -->
        <button
            id="login-btn"
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500
                   text-white font-semibold rounded-xl text-sm shadow-lg shadow-brand-900/50
                   transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2
                   focus:ring-brand-400 focus:ring-offset-2 focus:ring-offset-slate-900
                   flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Sign In to Portal
        </button>

    </form>

    <!-- Demo credentials hint (dev only) -->
    <div class="mt-8 p-4 bg-slate-800/50 border border-slate-700/50 rounded-xl">
        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2">Demo Credentials</p>
        <div class="space-y-1 text-xs text-slate-400">
            <p><span class="text-slate-300 font-medium">Code:</span> demo-university</p>
            <p><span class="text-slate-300 font-medium">Email:</span> vc@demo.edu</p>
            <p><span class="text-slate-300 font-medium">Password:</span> password</p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input   = document.getElementById('password');
    const icon    = document.getElementById('eye-icon');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';

    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}

// Loading state on submit
document.getElementById('login-form').addEventListener('submit', () => {
    const btn = document.getElementById('login-btn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Signing in...`;
});
</script>
