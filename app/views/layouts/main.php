<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Academia — University Management System" />
    <title>Academia &mdash; <?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar active link */
        .nav-link.active {
            background: rgba(99,102,241,0.15);
            color: #818cf8;
            border-right: 3px solid #6366f1;
        }
        .nav-link:hover:not(.active) {
            background: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }

        /* Card hover */
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }

        /* Sidebar transition */
        #sidebar { transition: width 0.25s ease; }

        /* Flash message animation */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .flash-msg { animation: slideDown 0.3s ease forwards; }
    </style>
</head>
<body class="h-full bg-slate-100 flex overflow-hidden">

<?php $authUser = Auth::user() ?? $_SESSION['auth'] ?? []; ?>
<?php $currentRole = $authUser['role'] ?? 'student'; ?>
<?php $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>

<!-- ═══════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════ -->
<aside id="sidebar"
       class="w-64 flex-shrink-0 bg-slate-900 flex flex-col h-screen overflow-y-auto relative z-30">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div>
            <span class="text-white font-bold text-base tracking-wide">Academia</span>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest">University SaaS</p>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="px-4 py-4 border-b border-slate-800">
        <div class="bg-slate-800/60 rounded-xl p-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-semibold truncate"><?= htmlspecialchars($authUser['name'] ?? '') ?></p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-brand-500/20 text-brand-300 uppercase tracking-wider">
                    <?= htmlspecialchars($currentRole) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1">

        <?php
        // Build navigation items based on role
        $navItems = [
            ['href' => '/dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'roles' => []],
        ];

        // VC / Superadmin
        if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])):
            $navItems[] = ['href' => '/faculties', 'label' => 'Faculties', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'roles' => []];
            $navItems[] = ['href' => '/departments', 'label' => 'Departments', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'roles' => []];
        endif;

        if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod', 'lecturer'])):
            $navItems[] = ['href' => '/courses', 'label' => 'Courses', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'roles' => []];
        endif;

        if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])):
            $navItems[] = ['href' => '/users', 'label' => 'Users', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'roles' => []];
        endif;

        if ($currentRole === 'student'):
            $navItems[] = ['href' => '/my-courses', 'label' => 'My Courses', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'roles' => []];
            $navItems[] = ['href' => '/results', 'label' => 'Results', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'roles' => []];
        endif;

        // Admin units — visible to staff & above
        if (in_array($currentRole, ['superadmin', 'vc', 'staff'])):
        ?>
            <div class="pt-3 pb-1">
                <p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Admin Units</p>
            </div>
            <?php
            $units = [
                ['href' => '/registry', 'label' => 'Registry',    'emoji' => '📋'],
                ['href' => '/bursary',  'label' => 'Bursary',     'emoji' => '💰'],
                ['href' => '/library',  'label' => 'Library',     'emoji' => '📚'],
            ];
            foreach ($units as $unit): ?>
                <a href="<?= url($unit['href']) ?>"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
                    <span class="text-base"><?= $unit['emoji'] ?></span>
                    <span><?= $unit['label'] ?></span>
                </a>
            <?php endforeach;
        endif; ?>

        <div class="pt-3 pb-1">
            <p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Account</p>
        </div>

        <?php foreach ($navItems as $item): ?>
        <a href="<?= url($item['href']) ?>"
           id="nav-<?= ltrim($item['href'], '/') ?: 'dashboard' ?>"
           class="nav-link <?= $currentPath === $item['href'] ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?= $item['icon'] ?>"/>
            </svg>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800">
        <a href="<?= url('/logout') ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-red-500/10 text-sm font-medium transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Logout</span>
        </a>
    </div>
</aside>

<!-- ═══════════════════════════════════════════════
     MAIN CONTENT AREA
═══════════════════════════════════════════════ -->
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <!-- TOP NAVBAR -->
    <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between flex-shrink-0 z-20 shadow-sm">
        <div class="flex items-center gap-4">
            <!-- Hamburger (mobile) -->
            <button onclick="document.getElementById('sidebar').classList.toggle('hidden')"
                    class="lg:hidden text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page title -->
            <div>
                <h1 class="text-slate-800 font-semibold text-base"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
                <?php if (!empty($breadcrumb)): ?>
                <nav class="flex items-center gap-1 text-xs text-slate-400 mt-0.5">
                    <?php foreach ($breadcrumb as $i => $crumb): ?>
                        <?php if ($i > 0): ?><span>/</span><?php endif; ?>
                        <?php if (isset($crumb['href'])): ?>
                            <a href="<?= url($crumb['href']) ?>" class="hover:text-brand-500 transition-colors"><?= htmlspecialchars($crumb['label']) ?></a>
                        <?php else: ?>
                            <span class="text-slate-600 font-medium"><?= htmlspecialchars($crumb['label']) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: actions & profile -->
        <div class="flex items-center gap-3">
            <!-- Notification bell -->
            <button class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- Profile chip -->
            <div class="flex items-center gap-2 bg-slate-100 rounded-xl px-3 py-1.5 cursor-pointer hover:bg-slate-200 transition-colors">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs">
                    <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="hidden sm:block">
                    <p class="text-slate-700 text-xs font-semibold leading-none"><?= htmlspecialchars(explode(' ', $authUser['name'] ?? 'User')[0]) ?></p>
                    <p class="text-slate-400 text-[10px] capitalize mt-0.5"><?= htmlspecialchars($currentRole) ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash message -->
    <?php if (!empty($_SESSION['flash'])): ?>
    <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="flash-msg mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2
        <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?php if ($flash['type'] === 'success'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            <?php else: ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            <?php endif; ?>
        </svg>
        <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <!-- ── PAGE CONTENT ── -->
    <main class="flex-1 overflow-y-auto p-6">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 px-6 py-3 text-center text-xs text-slate-400 flex-shrink-0">
        &copy; <?= date('Y') ?> Academia University SaaS &mdash; All rights reserved.
    </footer>
</div>

<script>
    // Auto-highlight active nav link based on current path
    document.addEventListener('DOMContentLoaded', () => {
        const currentPath = '<?= url($currentPath) ?>';
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
</script>

</body>
</html>
