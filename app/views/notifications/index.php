<?php
$pageTitle = 'Notifications';
$breadcrumb = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Notifications']
];
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Notifications</h1>
            <p class="text-slate-500 text-sm">Stay updated with the latest university alerts and activities.</p>
        </div>
        <?php if (!empty($notifications)): ?>
        <form action="<?= url('/notifications/read-all') ?>" method="POST">
            <button type="submit" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                Mark All as Read
            </button>
        </form>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <?php if (empty($notifications)): ?>
            <div class="py-20 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No notifications yet</h3>
                <p class="text-slate-500 text-sm max-w-xs mx-auto mt-2">When something happens that requires your attention, it will appear here.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-50">
                <?php foreach ($notifications as $notif): ?>
                    <div class="p-5 hover:bg-slate-50/80 transition-colors relative <?= $notif['is_read'] ? 'opacity-70' : '' ?>">
                        <div class="flex gap-4">
                            <!-- Icon -->
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 
                                <?= $notif['type'] === 'success' ? 'bg-emerald-50 text-emerald-600' : 
                                   ($notif['type'] === 'warning' ? 'bg-amber-50 text-amber-600' : 
                                   ($notif['type'] === 'error' ? 'bg-red-50 text-red-600' : 'bg-brand-50 text-brand-600')) ?>">
                                <?php 
                                    $icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                    if ($notif['type'] === 'success') $icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                                    if ($notif['type'] === 'warning') $icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                                    echo $icon;
                                ?>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="font-bold text-slate-800 leading-tight mb-1">
                                            <?= htmlspecialchars($notif['title']) ?>
                                            <?php if (!$notif['is_read']): ?>
                                                <span class="inline-block w-2 h-2 bg-brand-500 rounded-full ml-1"></span>
                                            <?php endif; ?>
                                        </h4>
                                        <p class="text-slate-600 text-sm leading-relaxed"><?= htmlspecialchars($notif['message']) ?></p>
                                    </div>
                                    <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap bg-slate-50 px-2 py-1 rounded-lg">
                                        <?= date('M j, Y — g:i a', strtotime($notif['created_at'])) ?>
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center gap-3">
                                    <?php if ($notif['link']): ?>
                                        <a href="<?= url($notif['link']) ?>" class="px-3 py-1.5 bg-brand-50 text-brand-700 text-xs font-bold rounded-lg hover:bg-brand-100 transition-colors">
                                            View Details
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!$notif['is_read']): ?>
                                        <form action="<?= url('/notifications/read') ?>" method="POST">
                                            <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                                            <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">
                                                Mark as Read
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
