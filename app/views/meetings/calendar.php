<?php
$pageTitle = 'Meeting Calendar';
$breadcrumb = [['label' => 'Dashboard', 'href' => '/dashboard'], ['label' => 'Calendar']];
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">University Calendar</h1>
            <p class="text-slate-500 text-sm">Manage your meetings, lectures, and academic appointments.</p>
        </div>
        <button onclick="document.getElementById('bookingModal').classList.remove('hidden')" 
                class="flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Book New Meeting
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- ── LEFT: Upcoming List ── -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Upcoming Today
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <?php 
                    $todayMeetings = array_filter($meetings, fn($m) => date('Y-m-d', strtotime($m['start_time'])) === date('Y-m-d'));
                    if (empty($todayMeetings)): ?>
                        <div class="text-center py-6">
                            <p class="text-xs text-slate-400">No meetings scheduled for today.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($todayMeetings as $m): ?>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 transition-colors cursor-pointer group">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider"><?= date('H:i', strtotime($m['start_time'])) ?></span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-brand-100 text-brand-700"><?= $m['status'] ?></span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 group-hover:text-brand-600 transition-colors"><?= htmlspecialchars($m['title']) ?></h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1"><?= htmlspecialchars($m['location'] ?: 'No location') ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Calendar Small View / Date Picker Placeholder -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-slate-800 text-sm"><?= date('F Y') ?></h4>
                    <div class="flex gap-1">
                        <button class="p-1 hover:bg-slate-100 rounded-md transition-colors"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button class="p-1 hover:bg-slate-100 rounded-md transition-colors"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-1 text-[10px] font-bold text-slate-400 uppercase mb-2">
                    <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                </div>
                <div class="grid grid-cols-7 gap-1">
                    <?php
                    $startOfMonth = date('w', strtotime(date('Y-m-01')));
                    $daysInMonth = date('t');
                    for($i=0; $i<$startOfMonth; $i++) echo '<span></span>';
                    for($d=1; $d<=$daysInMonth; $d++): 
                        $isToday = $d == date('j');
                    ?>
                        <span class="aspect-square flex items-center justify-center text-xs rounded-lg transition-colors cursor-pointer <?= $isToday ? 'bg-brand-600 text-white font-bold shadow-md' : 'text-slate-600 hover:bg-slate-100' ?>">
                            <?= $d ?>
                        </span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- ── RIGHT: Main Schedule ── -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-slate-100 bg-white sticky top-0 z-10 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 tracking-tight">Full Schedule</h3>
                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span> Confirmed
                        </span>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Past
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <?php if (empty($meetings)): ?>
                        <div class="py-20 text-center">
                            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">No meetings scheduled</h3>
                            <p class="text-slate-500 text-sm max-w-xs mx-auto mt-2">Start organizing your time by booking a meeting with colleagues or students.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                            <?php 
                            $lastDate = '';
                            foreach ($meetings as $m): 
                                $mDate = date('Y-m-d', strtotime($m['start_time']));
                                if ($mDate !== $lastDate):
                                    $lastDate = $mDate;
                                    $displayDate = ($mDate === date('Y-m-d')) ? 'Today' : date('l, M j', strtotime($mDate));
                            ?>
                                <div class="relative pl-10 pt-2 first:pt-0">
                                    <div class="absolute left-1.5 top-2 w-3.5 h-3.5 rounded-full border-4 border-white bg-brand-500 shadow-sm z-10"></div>
                                    <h5 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4"><?= $displayDate ?></h5>
                            <?php endif; ?>

                                <div class="relative bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all group mb-4">
                                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                                        <!-- Time Block -->
                                        <div class="md:w-24 shrink-0">
                                            <p class="text-sm font-black text-slate-800 leading-none"><?= date('H:i', strtotime($m['start_time'])) ?></p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1"><?= date('H:i', strtotime($m['end_time'])) ?></p>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-base font-bold text-slate-800 group-hover:text-brand-600 transition-colors truncate"><?= htmlspecialchars($m['title']) ?></h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                                <span class="flex items-center gap-1 text-[11px] font-medium text-slate-500">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    <?= htmlspecialchars($m['location'] ?: 'Online / TBD') ?>
                                                </span>
                                                <span class="flex items-center gap-1 text-[11px] font-medium text-slate-500">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    With: <?= htmlspecialchars($m['host_id'] == Auth::id() ? ($m['guest_name'] ?? 'Anyone') : $m['host_name']) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="shrink-0 flex items-center gap-2">
                                            <?php if ($m['status'] !== 'cancelled'): ?>
                                                <form action="<?= url('/calendar/cancel') ?>" method="POST" onsubmit="return confirm('Cancel this meeting?')">
                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Cancel">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-[10px] font-bold text-red-400 uppercase">Cancelled</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Booking Modal ── -->
<div id="bookingModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-8 pt-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Book a Meeting</h3>
                    <button onclick="document.getElementById('bookingModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg>
                    </button>
                </div>

                <form action="<?= url('/calendar/book') ?>" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Meeting Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Project Review Session"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Start Time *</label>
                            <input type="datetime-local" name="start_time" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">End Time *</label>
                            <input type="datetime-local" name="end_time" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Invite Colleague/Student</label>
                        <select name="guest_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                            <option value="">Select someone...</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= ucfirst($u['role']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Location / Room</label>
                        <input type="text" name="location" placeholder="e.g. Conference Room A or Zoom link"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" rows="3" placeholder="What is this meeting about?"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none text-sm text-slate-700 resize-none"></textarea>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="document.getElementById('bookingModal').classList.add('hidden')"
                                class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-[2] px-4 py-3 bg-brand-600 text-white text-sm font-bold rounded-xl hover:bg-brand-700 transition-colors shadow-lg shadow-brand-500/20">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
