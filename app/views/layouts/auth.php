<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Academia — University Management System" />
    <title>Academia &mdash; Login</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        brand: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
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
        .auth-glow { box-shadow: 0 0 80px rgba(99,102,241,0.18), 0 0 160px rgba(99,102,241,0.08); }
        .gradient-text {
            background: linear-gradient(135deg, #818cf8, #6366f1, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .float-anim { animation: float 5s ease-in-out infinite; }
    </style>
</head>
<body class="h-full">

<!-- Full-screen two-column layout -->
<div class="min-h-screen flex">

    <!-- ── LEFT PANEL — Decorative / Branding ── -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-950 overflow-hidden items-center justify-center">

        <!-- Background radial gradients -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-600/15 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl"></div>
        </div>

        <!-- Grid pattern overlay -->
        <div class="absolute inset-0 opacity-5"
             style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px);
                    background-size: 40px 40px;">
        </div>

        <!-- Branding content -->
        <div class="relative z-10 text-center px-12">
            <!-- Logo icon -->
            <div class="float-anim inline-flex mb-8">
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center shadow-2xl auth-glow">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-5xl font-black text-white mb-4 tracking-tight">
                <span class="gradient-text">Academia</span>
            </h1>
            <p class="text-slate-400 text-lg font-medium mb-10 max-w-sm mx-auto leading-relaxed">
                The complete university management platform for modern institutions.
            </p>

            <!-- Feature pills -->
            <div class="flex flex-col gap-3 items-center">
                <?php
                $features = [
                    ['icon' => '🎓', 'text' => 'Multi-Faculty & Department Management'],
                    ['icon' => '🔐', 'text' => 'Role-Based Access Control'],
                    ['icon' => '🏛️', 'text' => 'Registry, Bursary & Library Units'],
                    ['icon' => '📊', 'text' => 'Real-time Academic Analytics'],
                ];
                foreach ($features as $f): ?>
                <div class="flex items-center gap-3 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-5 py-3 w-full max-w-xs">
                    <span class="text-xl"><?= $f['icon'] ?></span>
                    <span class="text-slate-300 text-sm font-medium"><?= $f['text'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── RIGHT PANEL — Login Form ── -->
    <div class="flex-1 flex items-center justify-center bg-slate-900 px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Mobile logo (visible only on small screens) -->
            <div class="lg:hidden flex justify-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center shadow-xl">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
            </div>

            <!-- The view content (login form) is injected here -->
            <?= $content ?>

        </div>
    </div>
</div>

</body>
</html>
