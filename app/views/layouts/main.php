<?php
/**
 * main.php
 * Premium Midnight Sidebar Navigation Layout
 */

$currentPath = $_SERVER['REQUEST_URI'];
$currentRole = $_SESSION['auth']['role'] ?? 'student';

function navActive($path, $current) {
    return (str_starts_with($current, $path)) 
        ? 'bg-brand-500/10 text-brand-400 border-l-4 border-brand-500 shadow-[0_0_20px_rgba(14,165,233,0.1)]' 
        : 'text-slate-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent';
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Academia' ?> | University Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
        .sidebar-premium { background: #0f172a; transition: all 0.3s ease; }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .nav-link { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

        @media print { .no-print { display: none !important; } }
        
        /* Mobile Sidebar State */
        #sidebar.mobile-hidden { transform: translateX(-100%); }
        #sidebar-overlay.hidden { display: none; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc',
                            400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1',
                            800: '#075985', 900: '#0c4a6e',
                        }
                    }
                }
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('mobile-hidden');
            overlay.classList.toggle('hidden');
        }
    </script>
</head>
<body class="h-full text-slate-900">

<div class="flex h-full no-print">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[45] hidden lg:hidden transition-all duration-300"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 sidebar-premium z-50 shadow-2xl lg:static lg:translate-x-0 mobile-hidden transform transition-transform duration-300 flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center gap-4 px-8 py-10">
            <div class="w-12 h-12 bg-gradient-to-tr from-brand-600 to-brand-400 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/30 transform rotate-3">
                <span class="text-white font-black text-2xl">A</span>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight text-white leading-none">ACADEMIA</h1>
                <p class="text-[10px] font-bold text-brand-400 uppercase tracking-[0.2em] mt-1.5">SaaS University</p>
            </div>
        </div>

        <!-- User Profile Quick View -->
        <div class="px-5 mb-8">
            <div class="group relative bg-white/5 rounded-[2rem] p-5 border border-white/10 backdrop-blur-sm flex items-center gap-4 hover:bg-white/10 transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-slate-800 overflow-hidden ring-2 ring-brand-500/20 p-0.5 group-hover:ring-brand-500/50 transition-all">
                    <?php if (!empty($_SESSION['auth']['profile_image'])): ?>
                        <img src="<?= url($_SESSION['auth']['profile_image']) ?>" class="w-full h-full object-cover rounded-xl">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-500 rounded-xl">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-white truncate"><?= htmlspecialchars($_SESSION['auth']['name'] ?? 'User') ?></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= htmlspecialchars($currentRole) ?></p>
                    </div>
                </div>
                <!-- Logout Quick Action -->
                <a href="<?= url('/logout') ?>" class="opacity-0 group-hover:opacity-100 w-10 h-10 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto custom-scrollbar">
            
            <!-- MAIN SECTION -->
            <a href="<?= url('/dashboard') ?>" class="nav-link <?= navActive('/dashboard', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- ACADEMIC STAFF SECTION -->
            <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod', 'lecturer'])): ?>
                <div class="pt-8 pb-2 px-5"><p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.25em]">Academic Management</p></div>
                
                <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])): ?>
                <a href="<?= url('/faculties') ?>" class="nav-link <?= navActive('/faculties', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Faculties</span>
                </a>
                <a href="<?= url('/departments') ?>" class="nav-link <?= navActive('/departments', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Departments</span>
                </a>
                <?php endif; ?>

                <a href="<?= url('/submissions') ?>" class="nav-link <?= navActive('/submissions', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Academic Materials</span>
                </a>
                <a href="<?= url('/courses') ?>" class="nav-link <?= navActive('/courses', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Courses</span>
                </a>
                <a href="<?= url('/results') ?>" class="nav-link <?= navActive('/results', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Results Entry</span>
                </a>
            <?php endif; ?>

            <!-- STUDENT PORTAL SECTION -->
            <?php if ($currentRole === 'student'): ?>
                <div class="pt-8 pb-2 px-5"><p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.25em]">Student Portal</p></div>
                <a href="<?= url('/my-courses') ?>" class="nav-link <?= navActive('/my-courses', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>My Courses</span>
                </a>
                <a href="<?= url('/results') ?>" class="nav-link <?= navActive('/results', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>My Results</span>
                </a>
                
                <div class="pt-4 pb-1 px-5"><p class="text-slate-600 text-[10px] font-bold uppercase tracking-widest">Academic Records</p></div>
                <a href="<?= url('/records/transcript') ?>" class="nav-link <?= navActive('/records/transcript', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>My Transcript</span>
                </a>
                <a href="<?= url('/records/certificate') ?>" class="nav-link <?= navActive('/records/certificate', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                    <span>Degree Certificate</span>
                </a>
            <?php endif; ?>

            <!-- ADMINISTRATION SECTION -->
            <?php if (in_array($currentRole, ['superadmin', 'vc', 'dean', 'hod'])): ?>
                <div class="pt-8 pb-2 px-5"><p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.25em]">Administration</p></div>
                <a href="<?= url('/users') ?>" class="nav-link <?= navActive('/users', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>User Management</span>
                </a>
            <?php endif; ?>

            <!-- ADMIN UNITS SECTION -->
            <?php 
                $userUnitId = (int)($_SESSION['auth']['unit_id'] ?? 0);
                $isAdmin = in_array($currentRole, ['superadmin', 'vc']);
                if ($isAdmin || $currentRole === 'staff'): 
                    $canSeeRegistry = $isAdmin || $userUnitId === 1;
                    $canSeeBursary  = $isAdmin || $userUnitId === 2;
                    $canSeeLibrary  = $isAdmin || $userUnitId === 3;
                    if ($canSeeRegistry || $canSeeBursary || $canSeeLibrary):
            ?>
                <div class="pt-8 pb-2 px-5"><p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.25em]">Institutional Units</p></div>
                <?php if ($canSeeRegistry): ?>
                <a href="<?= url('/registry') ?>" class="nav-link <?= navActive('/registry', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    <span>Registry</span>
                </a>
                <?php endif; ?>
                <?php if ($canSeeBursary): ?>
                <a href="<?= url('/bursary') ?>" class="nav-link <?= navActive('/bursary', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.67 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.407-2.67-1M12 16v1m-7-4h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                    <span>Bursary</span>
                </a>
                <?php endif; ?>
                <?php if ($canSeeLibrary): ?>
                <a href="<?= url('/library') ?>" class="nav-link <?= navActive('/library', $currentPath) ?> flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold tracking-wide">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Library</span>
                </a>
                <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>

        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="lg:pl-72 flex-1 min-h-screen">
        <!-- Top Header -->
        <header class="glass-header sticky top-0 z-40 px-6 sm:px-10 py-5 flex items-center justify-between no-print">
            <div class="flex items-center gap-6">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 text-slate-500 hover:bg-slate-100 rounded-2xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hidden md:flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                    <?php if (isset($breadcrumb) && is_array($breadcrumb)): ?>
                        <?php foreach ($breadcrumb as $i => $item): ?>
                            <?php if ($i > 0): ?>
                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            <?php endif; ?>
                            <?php if (isset($item['href'])): ?>
                                <a href="<?= url($item['href']) ?>" class="hover:text-brand-600 transition-colors"><?= htmlspecialchars($item['label']) ?></a>
                            <?php else: ?>
                                <span class="text-slate-900"><?= htmlspecialchars($item['label']) ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-slate-900">University Dashboard</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-center gap-8">
                <div class="hidden sm:flex items-center gap-3 px-5 py-2.5 bg-brand-50 rounded-2xl border border-brand-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                    <span class="text-[10px] font-black text-brand-700 uppercase tracking-widest">Live System</span>
                </div>
                
                <div class="relative group flex items-center gap-4 pl-8 border-l border-slate-200 cursor-pointer py-2">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black text-slate-900 leading-none"><?= htmlspecialchars($_SESSION['auth']['name'] ?? 'User') ?></p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5"><?= htmlspecialchars($currentRole) ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand-600 to-brand-400 flex items-center justify-center text-white font-black shadow-lg shadow-brand-500/20 text-lg group-hover:scale-105 transition-transform">
                        <?= substr($_SESSION['auth']['name'] ?? 'U', 0, 1) ?>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="absolute top-full right-0 mt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden p-2">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Account</p>
                            </div>
                            <a href="<?= url('/profile') ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>My Profile</span>
                            </a>
                            <a href="<?= url('/settings') ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Settings</span>
                            </a>
                            <div class="h-px bg-slate-50 my-1"></div>
                            <a href="<?= url('/logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Viewport -->
        <div class="p-8">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['flash'])): ?>
                <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                    <div class="mb-6 p-4 rounded-2xl flex items-center gap-3 border shadow-sm
                        <?= $type === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-800' : 'bg-red-50 border-red-100 text-red-800' ?>">
                        <?php if ($type === 'success'): ?>
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php else: ?>
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php endif; ?>
                        <span class="text-sm font-bold"><?= $message ?></span>
                    </div>
                <?php endforeach; unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $content ?>
        </div>
    </main>
</div>

</body>
</html>
