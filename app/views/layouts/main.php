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

        .nav-link.active {
            background: rgba(99,102,241,0.15);
            color: #818cf8;
            border-right: 3px solid #6366f1;
        }
        .nav-link:hover:not(.active) {
            background: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }

        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }

        #sidebar { transition: width 0.25s ease; }

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

<?php
// Notification fetching
require_once ROOT_PATH . '/app/models/Notification.php';
$notifModel = new Notification();
$unreadCount = $notifModel->countUnread($authUser['user_id'] ?? $authUser['id'] ?? 0, $authUser['tenant_id'] ?? 0);
$topNotifs = $unreadCount > 0 ? $notifModel->getUnread($authUser['user_id'] ?? $authUser['id'] ?? 0, $authUser['tenant_id'] ?? 0, 5) : [];
?>

<?php
// Helper: is nav item active?
function navActive(string $href, string $currentPath): string {
    if ($href === '/dashboard') {
        return ($currentPath === '/dashboard' || $currentPath === '/') ? 'active' : '';
    }
    return str_starts_with($currentPath, $href) ? 'active' : '';
}
?>

<!-- ═══════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════ -->
<aside id="sidebar"
       class="w-64 flex-shrink-0 bg-slate-900 flex flex-col h-screen overflow-visible relative z-30">

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

    <!-- User Info Card (hover → drops down) -->
    <div class="px-4 py-4 border-b border-slate-800 relative group/profile" id="sidebarProfileWrapper">

        <div class="w-full bg-slate-800/60 group-hover/profile:bg-slate-700/60 rounded-xl p-3 flex items-center gap-3 transition-colors cursor-pointer">
            <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 overflow-hidden border border-slate-600">
                <?php if (!empty($authUser['profile_image'])): ?>
                    <img src="<?= url($authUser['profile_image']) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center">
                        <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="min-w-0 flex-1 text-left">
                <p class="text-white text-sm font-semibold truncate"><?= htmlspecialchars($authUser['name'] ?? '') ?></p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-brand-500/20 text-brand-300 uppercase tracking-wider">
                    <?= htmlspecialchars($currentRole) ?>
                </span>
            </div>
            <!-- Chevron -->
            <svg class="w-3.5 h-3.5 text-slate-500 group-hover/profile:text-slate-300 group-hover/profile:rotate-180 transition-all duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <!-- Dropdown Panel (opens DOWNWARD on hover) -->
        <div class="invisible opacity-0 group-hover/profile:visible group-hover/profile:opacity-100 transition-all duration-200
                    absolute left-0 right-0 top-full mt-1 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">

            <!-- Header -->
            <div class="px-4 py-3 bg-gradient-to-r from-brand-50 to-indigo-50 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-200 border border-slate-100 shrink-0">
                    <?php if (!empty($authUser['profile_image'])): ?>
                        <img src="<?= url($authUser['profile_image']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-brand-500 flex items-center justify-center text-white font-bold text-xs">
                            <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate"><?= htmlspecialchars($authUser['name'] ?? '') ?></p>
                    <p class="text-xs text-slate-500 truncate mt-0.5"><?= htmlspecialchars($authUser['email'] ?? '') ?></p>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="py-1">
                <a href="<?= url('/profile') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold">Edit Profile</p>
                        <p class="text-[10px] text-slate-400">Update your personal info</p>
                    </div>
                </a>

                <a href="<?= url('/profile#password') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold">Change Password</p>
                        <p class="text-[10px] text-slate-400">Keep your account secure</p>
                    </div>
                </a>

                <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])): ?>
                <a href="<?= url('/users') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold">Manage Users</p>
                        <p class="text-[10px] text-slate-400">University directory</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>

            <!-- Sign Out -->
            <div class="border-t border-slate-100 py-1">
                <a href="<?= url('/logout') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold">Sign Out</p>
                        <p class="text-[10px] text-red-400">End your session</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        <!-- ── MAIN ── -->
        <a href="<?= url('/dashboard') ?>"
           class="nav-link <?= navActive('/dashboard', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="<?= url('/calendar') ?>"
           class="nav-link <?= navActive('/calendar', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Calendar</span>
        </a>

        <a href="<?= url('/reports') ?>"
           class="nav-link <?= navActive('/reports', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span>Campus Reports</span>
        </a>

        <!-- ── ACADEMIC (lecturers and above) ── -->
        <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod', 'lecturer'])): ?>
        <div class="pt-4 pb-1"><p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Academic</p></div>

        <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])): ?>
        <a href="<?= url('/faculties') ?>"
           class="nav-link <?= navActive('/faculties', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Faculties</span>
        </a>

        <a href="<?= url('/departments') ?>"
           class="nav-link <?= navActive('/departments', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span>Departments</span>
        </a>
        <?php endif; ?>

        <a href="<?= url('/submissions') ?>"
           class="nav-link <?= navActive('/submissions', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Academic Materials</span>
        </a>

        <a href="<?= url('/courses') ?>"
           class="nav-link <?= navActive('/courses', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>Courses</span>
        </a>

        <a href="<?= url('/results') ?>"
           class="nav-link <?= navActive('/results', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span>Results Entry</span>
        </a>
        <?php endif; ?>

        <!-- ── STUDENT PORTAL ── -->
        <?php if ($currentRole === 'student'): ?>
        <div class="pt-4 pb-1"><p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Student Portal</p></div>

        <a href="<?= url('/my-courses') ?>"
           class="nav-link <?= navActive('/my-courses', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>My Courses</span>
        </a>

        <a href="<?= url('/results') ?>"
           class="nav-link <?= navActive('/results', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span>My Results</span>
        </a>
        <?php endif; ?>

        <!-- ── ADMINISTRATION (HOD and above) ── -->
        <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])): ?>
        <div class="pt-4 pb-1"><p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Administration</p></div>

        <a href="<?= url('/users') ?>"
           class="nav-link <?= navActive('/users', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Users</span>
        </a>
        <?php endif; ?>

        <!-- ── ADMIN UNITS (Registry / Bursary / Library) ── -->
        <?php 
        $userUnitId = (int)($_SESSION['auth']['unit_id'] ?? 0);
        $isAdmin = in_array($currentRole, ['superadmin', 'vc']);
        
        if ($isAdmin || $currentRole === 'staff'): 
            $canSeeRegistry = $isAdmin || $userUnitId === 1;
            $canSeeBursary  = $isAdmin || $userUnitId === 2;
            $canSeeLibrary  = $isAdmin || $userUnitId === 3;
            
            if ($canSeeRegistry || $canSeeBursary || $canSeeLibrary):
        ?>
        <div class="pt-4 pb-1"><p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Admin Units</p></div>

        <?php if ($canSeeRegistry): ?>
        <a href="<?= url('/registry') ?>"
           class="nav-link <?= navActive('/registry', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <span class="w-5 text-center text-base">📋</span>
            <span>Registry</span>
        </a>
        <?php endif; ?>

        <?php if ($canSeeBursary): ?>
        <a href="<?= url('/bursary') ?>"
           class="nav-link <?= navActive('/bursary', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <span class="w-5 text-center text-base">💰</span>
            <span>Bursary</span>
        </a>
        <?php endif; ?>

        <?php if ($canSeeLibrary): ?>
        <a href="<?= url('/library') ?>"
           class="nav-link <?= navActive('/library', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <span class="w-5 text-center text-base">📚</span>
            <span>Library</span>
        </a>
        <?php endif; ?>
        <?php 
            endif;
        endif; 
        ?>

        <!-- ── ACCOUNT ── -->
        <div class="pt-4 pb-1"><p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest px-3">Account</p></div>

        <a href="<?= url('/profile') ?>"
           class="nav-link <?= navActive('/profile', $currentPath) ?> flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>My Profile</span>
        </a>

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

            <!-- Page title & breadcrumb -->
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

        <!-- Right: profile chip with hover dropdown -->
        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <div class="relative group/notif">
                <button class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <?php if ($unreadCount > 0): ?>
                        <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white">
                            <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
                        </span>
                    <?php endif; ?>
                </button>

                <!-- Notification Dropdown (opens on hover) -->
                <div class="invisible opacity-0 group-hover/notif:visible group-hover/notif:opacity-100 transition-all duration-200
                            absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                    
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-800">Notifications</h3>
                        <a href="<?= url('/notifications') ?>" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">View All</a>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        <?php if (empty($topNotifs)): ?>
                            <div class="px-4 py-8 text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">All caught up!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($topNotifs as $notif): ?>
                                <div class="px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 relative">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center shrink-0 mt-0.5">
                                            <?php 
                                                $icon = '<svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                                if ($notif['type'] === 'success') $icon = '<svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                                                if ($notif['type'] === 'warning') $icon = '<svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                                                echo $icon;
                                            ?>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 leading-tight"><?= htmlspecialchars($notif['title']) ?></p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2"><?= htmlspecialchars($notif['message']) ?></p>
                                            <p class="text-[9px] text-slate-400 mt-1"><?= date('M j, g:i a', strtotime($notif['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <?php if ($notif['link']): ?>
                                        <a href="<?= url($notif['link']) ?>" class="absolute inset-0 z-10"></a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($unreadCount > 0): ?>
                    <div class="px-4 py-2 border-t border-slate-100 bg-slate-50/30 text-center">
                        <form action="<?= url('/notifications/read-all') ?>" method="POST">
                            <button type="submit" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider">Mark all as read</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile dropdown trigger (hover → drops down) -->
            <div class="relative group/nav-profile" id="profileDropdownWrapper">
                <div class="flex items-center gap-2 bg-slate-100 group-hover/nav-profile:bg-slate-200 rounded-xl px-3 py-1.5 cursor-pointer transition-colors focus:outline-none">
                    <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-white font-bold text-xs overflow-hidden border border-slate-300">
                        <?php if (!empty($authUser['profile_image'])): ?>
                            <img src="<?= url($authUser['profile_image']) ?>" alt="Avatar" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center">
                                <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-slate-700 text-xs font-semibold leading-none"><?= htmlspecialchars(explode(' ', $authUser['name'] ?? 'User')[0]) ?></p>
                        <p class="text-slate-400 text-[10px] capitalize mt-0.5"><?= htmlspecialchars($currentRole) ?></p>
                    </div>
                    <!-- Chevron -->
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover/nav-profile:rotate-180 transition-transform duration-200 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <!-- Dropdown Panel (opens on hover) -->
                <div id="profileDropdown"
                     class="invisible opacity-0 group-hover/nav-profile:visible group-hover/nav-profile:opacity-100 transition-all duration-200
                            absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">

                    <!-- Header -->
                    <div class="px-4 py-3 bg-gradient-to-r from-brand-50 to-indigo-50 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-200 border border-slate-100 shrink-0">
                            <?php if (!empty($authUser['profile_image'])): ?>
                                <img src="<?= url($authUser['profile_image']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-brand-500 flex items-center justify-center text-white font-bold text-xs">
                                    <?= strtoupper(substr($authUser['name'] ?? 'U', 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate"><?= htmlspecialchars($authUser['name'] ?? '') ?></p>
                            <p class="text-xs text-slate-500 truncate mt-0.5"><?= htmlspecialchars($authUser['email'] ?? '') ?></p>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="<?= url('/profile') ?>"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold">Edit Profile</p>
                                <p class="text-[10px] text-slate-400">Update your personal info</p>
                            </div>
                        </a>

                        <a href="<?= url('/profile#password') ?>"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold">Change Password</p>
                                <p class="text-[10px] text-slate-400">Keep your account secure</p>
                            </div>
                        </a>

                        <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])): ?>
                        <a href="<?= url('/users') ?>"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold">Manage Users</p>
                                <p class="text-[10px] text-slate-400">University directory</p>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Logout -->
                    <div class="border-t border-slate-100 py-1">
                        <a href="<?= url('/logout') ?>"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold">Sign Out</p>
                                <p class="text-[10px] text-red-400">End your session</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div><!-- /profile dropdown wrapper -->
        </div><!-- /right flex -->
    </header>

    <!-- Flash message -->
    <?php if (!empty($_SESSION['flash'])): ?>
    <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="flash-msg mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2
        <?php
        $flashColors = [
            'success' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'error'   => 'bg-red-50 text-red-700 border border-red-200',
            'info'    => 'bg-blue-50 text-blue-700 border border-blue-200',
            'warning' => 'bg-amber-50 text-amber-700 border border-amber-200',
        ];
        echo $flashColors[$flash['type']] ?? $flashColors['info'];
        ?>">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?php if ($flash['type'] === 'success'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            <?php elseif ($flash['type'] === 'error'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            <?php else: ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
// tableSearch helper used across views
function tableSearch(inputId, tableId) {
    const query = document.getElementById(inputId).value.toLowerCase();
    const rows  = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
}
</script>

</body>
</html>
