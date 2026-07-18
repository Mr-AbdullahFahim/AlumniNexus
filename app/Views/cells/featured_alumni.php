<?php if ($compact ?? false): ?>
<div class="w-full relative overflow-hidden bg-slate-900 border-b border-yellow-500/30">
    <!-- Premium background effects -->
    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500/5 via-transparent to-yellow-500/5 z-0"></div>
    <div class="absolute right-0 top-0 w-64 h-full bg-yellow-400 opacity-10 rounded-full blur-3xl transform translate-x-1/2"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-3">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto text-left">
                <!-- Profile Image -->
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 rounded-full p-0.5 bg-gradient-to-tr from-yellow-300 to-yellow-600 shadow-md">
                        <?php if(!empty($alumni['photo_url'])): ?>
                            <img src="<?= esc($alumni['photo_url']) ?>" alt="<?= esc($alumni['name']) ?>" class="w-full h-full object-cover rounded-full border-2 border-slate-900">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($alumni['name']) ?>&background=random" alt="<?= esc($alumni['name']) ?>" class="w-full h-full object-cover rounded-full border-2 border-slate-900">
                        <?php endif; ?>
                    </div>
                    <!-- Featured Star Badge -->
                    <div class="absolute -bottom-1 -right-1 bg-yellow-500 text-slate-900 p-0.5 rounded-full shadow-sm border border-slate-900">
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>

                <!-- Details -->
                <div class="flex-1 flex flex-col justify-center">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="inline-flex items-center justify-center w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-yellow-400">Featured Alumni</span>
                    </div>
                    <div class="flex items-center gap-3 text-slate-300 text-sm">
                        <h2 class="font-bold text-white leading-tight"><?= esc($alumni['name']) ?></h2>
                        <?php if(!empty($alumni['position']) || !empty($alumni['company'])): ?>
                        <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="hidden sm:inline-block truncate max-w-[200px] text-xs">
                            <?= esc($alumni['position']) ?> <?= !empty($alumni['company']) ? 'at ' . esc($alumni['company']) : '' ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if(!empty($alumni['industry'])): ?>
                        <span class="hidden md:inline-block w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="hidden md:inline-block truncate max-w-[150px] text-xs">
                            <?= esc($alumni['industry']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="flex-shrink-0 w-full sm:w-auto">
                <a href="<?= base_url('alumni/profile/' . $alumni['alumni_id']) ?>" class="group relative flex items-center justify-center w-full sm:w-auto px-4 py-2 text-xs font-bold text-slate-900 transition-all duration-200 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg hover:from-yellow-300 hover:to-yellow-400 shadow-md">
                    <span class="flex items-center gap-1.5">
                        View Profile
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-yellow-500 via-yellow-400 to-yellow-500 p-[2px] shadow-2xl">
    <!-- Inner Container to create border effect -->
    <div class="relative h-full w-full bg-slate-900 rounded-[14px] overflow-hidden">
        
        <!-- Premium background effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-transparent z-0"></div>
        <div class="absolute right-0 top-0 w-64 h-64 bg-yellow-400 opacity-20 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 p-6 md:p-8">
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 w-full text-center md:text-left">
                <!-- Profile Image with Golden Border -->
                <div class="relative">
                    <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-yellow-300 to-yellow-600 shadow-lg">
                        <?php if(!empty($alumni['photo_url'])): ?>
                            <img src="<?= esc($alumni['photo_url']) ?>" alt="<?= esc($alumni['name']) ?>" class="w-full h-full object-cover rounded-full border-2 border-slate-900">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($alumni['name']) ?>&background=random" alt="<?= esc($alumni['name']) ?>" class="w-full h-full object-cover rounded-full border-2 border-slate-900">
                        <?php endif; ?>
                    </div>
                    <!-- Featured Star Badge -->
                    <div class="absolute -bottom-2 -right-2 bg-yellow-500 text-slate-900 p-1.5 rounded-full shadow-lg border-2 border-slate-900" title="Featured Alumni">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>

                <!-- Details -->
                <div class="flex-1">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                        Today's Featured Alumni
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight text-white mb-1"><?= esc($alumni['name']) ?></h2>
                    
                    <div class="flex flex-col md:flex-row gap-1 md:gap-4 text-slate-300 mt-2">
                        <?php if(!empty($alumni['position']) || !empty($alumni['company'])): ?>
                        <div class="flex items-center gap-1.5 justify-center md:justify-start">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-sm">
                                <?= esc($alumni['position']) ?> <?= !empty($alumni['company']) ? 'at ' . esc($alumni['company']) : '' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($alumni['industry'])): ?>
                        <div class="flex items-center gap-1.5 justify-center md:justify-start">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-sm"><?= esc($alumni['industry']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($alumni['graduation_year'])): ?>
                        <div class="flex items-center gap-1.5 justify-center md:justify-start">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            <span class="text-sm">Class of <?= esc($alumni['graduation_year']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="mt-4 md:mt-0 flex-shrink-0">
                <a href="<?= base_url('alumni/profile/' . $alumni['alumni_id']) ?>" class="group relative inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-slate-900 transition-all duration-200 bg-gradient-to-r from-yellow-400 to-yellow-500 border border-transparent rounded-xl hover:from-yellow-300 hover:to-yellow-400 hover:shadow-[0_0_20px_rgba(234,179,8,0.4)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 focus:ring-offset-slate-900 shadow-xl overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                    <span class="relative flex items-center gap-2">
                        View Premium Profile
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </a>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>
