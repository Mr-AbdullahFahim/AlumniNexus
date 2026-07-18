<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div x-data="sponsorModule(<?= $user['id'] ?>, <?= $sponsorTotalThisCycle ?? 0 ?>)">
<div x-data="favoriteModule(<?= $user['id'] ?>, <?= isset($isFavorited) && $isFavorited ? 'true' : 'false' ?>)">
<!-- Back Navigation -->
<div class="mb-6">
    <a href="#" onclick="history.back(); return false;" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400">
        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Directory
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Profile Card -->
    <div class="lg:col-span-1 space-y-6">
<?php $isFeaturedUser = isset($isFeatured) && $isFeatured; ?>
        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col items-center text-center relative overflow-hidden <?= $isFeaturedUser ? 'ring-2 ring-yellow-400 dark:ring-yellow-500 shadow-yellow-500/20' : '' ?>">
            
            <?php if ($isFeaturedUser): ?>
                <!-- Premium Background Elements -->
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-transparent z-0"></div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400 opacity-20 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                
                <!-- Featured Badge -->
                <div class="absolute top-4 left-4 z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 border border-yellow-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                        Featured
                    </span>
                </div>
            <?php endif; ?>

            <div class="relative z-10 flex flex-col items-center">
                <div class="relative inline-block mb-4">
                    <img src="<?= !empty($general['photo_url']) ? esc($general['photo_url']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=random' ?>" 
                         class="w-32 h-32 rounded-full object-cover border-4 <?= $isFeaturedUser ? 'border-yellow-400 shadow-[0_0_15px_rgba(250,204,21,0.5)]' : 'border-white dark:border-slate-900 shadow-sm' ?> relative z-10">
                    
                    <?php if ($isFeaturedUser): ?>
                        <div class="absolute -bottom-1 -right-1 z-20 bg-yellow-500 text-slate-900 p-1.5 rounded-full shadow-lg border-2 border-white dark:border-slate-900" title="Featured Alumni">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h1 class="text-2xl font-bold <?= $isFeaturedUser ? 'text-yellow-600 dark:text-yellow-400' : 'text-slate-900 dark:text-white' ?>"><?= esc($user['name']) ?></h1>
                
                <?php if(!empty($general['position']) && !empty($general['company'])): ?>
                    <p class="text-sm font-medium <?= $isFeaturedUser ? 'text-yellow-700 dark:text-yellow-500' : 'text-primary-600 dark:text-primary-400' ?> mt-1"><?= esc($general['position']) ?> at <?= esc($general['company']) ?></p>
                <?php elseif(!empty($general['position'])): ?>
                    <p class="text-sm font-medium <?= $isFeaturedUser ? 'text-yellow-700 dark:text-yellow-500' : 'text-primary-600 dark:text-primary-400' ?> mt-1"><?= esc($general['position']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mt-6 w-full space-y-3">
                <?php if(!empty($general['department'])): ?>
                    <div class="flex items-center justify-between text-sm pb-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Department</span>
                        <span class="font-medium text-slate-900 dark:text-slate-200"><?= esc($general['department']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if(!empty($general['graduation_year'])): ?>
                    <div class="flex items-center justify-between text-sm pb-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Class of</span>
                        <span class="font-medium text-slate-900 dark:text-slate-200"><?= esc($general['graduation_year']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if(!empty($general['industry'])): ?>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Industry</span>
                        <span class="font-medium text-slate-900 dark:text-slate-200"><?= esc($general['industry']) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Social Links -->
            <?php if(!empty($general['social_links'])): ?>
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <?php foreach($general['social_links'] as $link): ?>
                        <?php if(is_array($link) && isset($link['url']) && isset($link['platform'])): ?>
                            <a href="<?= esc($link['url']) ?>" target="_blank" class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-primary-600 rounded-lg">
                                <span class="text-xs font-medium"><?= esc($link['platform']) ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- SPONSOR BUTTON -->
            <?php if(isset($viewer) && isset($viewer->role) && $viewer->role == 4): ?>
                <div class="w-full mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <?php if (isset($hasReachedMonthlyWinLimit) && $hasReachedMonthlyWinLimit): ?>
                        <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-800/30 text-center">
                            <p class="text-xs font-medium text-red-700 dark:text-red-400">This alumni has reached the monthly winning quota and cannot receive further sponsorships this month.</p>
                        </div>
                        <button disabled class="w-full py-3 px-4 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-sm font-bold rounded-xl shadow-sm cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Sponsor Alumni (Quota Reached)
                        </button>
                    <?php else: ?>
                        <button @click="isSponsorModalOpen = true" class="w-full py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Sponsor Alumni
                        </button>
                    <?php endif; ?>
                    
                    <button @click="openHistoryModal" class="w-full mt-3 py-3 px-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        View Sponsorships
                    </button>
                </div>
            <?php endif; ?>

            <!-- FAVORITE BUTTON FOR STUDENTS -->
            <?php if(isset($viewer) && isset($viewer->role) && $viewer->role == 3): ?>
                <div class="w-full mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button @click="toggleFavorite" :class="isFavorited ? 'bg-yellow-50 text-yellow-600 border-yellow-200 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-500 dark:border-yellow-800' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700'" class="w-full py-3 px-4 border text-sm font-bold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2" :disabled="isLoading">
                        <svg class="w-5 h-5 transition-transform" :class="isFavorited ? 'fill-current' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        <span x-text="isFavorited ? 'Favorited' : 'Add to Favorites'"></span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Statistics Widget (Placeholder) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Statistics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-100 dark:border-slate-700">
                    <span class="block text-2xl font-black text-primary-600 dark:text-primary-400"><?= number_format($general['view_count'] ?? 0) ?></span>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Profile Views</span>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-100 dark:border-slate-700">
                    <span class="block text-2xl font-black text-primary-600 dark:text-primary-400"><?= count($employment) ?></span>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Jobs</span>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-100 dark:border-slate-700">
                    <span class="block text-2xl font-black text-primary-600 dark:text-primary-400"><?= count($degrees) ?></span>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Degrees</span>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-100 dark:border-slate-700">
                    <span class="block text-2xl font-black text-primary-600 dark:text-primary-400"><?= count($certifications) ?></span>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Certs</span>
                </div>
            </div>
        </div>

        <!-- Skills -->
        <?php if(!empty($general['skills'])): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Top Skills</h3>
                <div class="flex flex-wrap gap-2">
                    <?php foreach($general['skills'] as $skill): ?>
                        <span class="px-3 py-1 bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 rounded-full text-xs font-medium">
                            <?= esc($skill) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- About -->
        <?php if(!empty($general['bio'])): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">About</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line"><?= esc($general['bio']) ?></p>
            </div>
        <?php endif; ?>

        <!-- Employment History -->
        <?php if(!empty($employment)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Employment</h3>
                <div class="space-y-6">
                    <?php foreach($employment as $job): ?>
                        <div class="relative pl-6 border-l-2 border-slate-100 dark:border-slate-800">
                            <div class="absolute w-3 h-3 bg-primary-500 rounded-full -left-[7px] top-1.5 border-2 border-white dark:border-slate-900"></div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white"><?= esc($job['position']) ?></h4>
                            <p class="text-sm font-medium text-primary-600 dark:text-primary-400"><?= esc($job['company_name'] ?? '') ?></p>
                            <p class="text-xs text-slate-500 mt-1">
                                <?= date('M Y', strtotime($job['start_date'])) ?> - 
                                <?= $job['is_current'] ? 'Present' : date('M Y', strtotime($job['end_date'])) ?>
                            </p>
                            <?php if(!empty($job['description'])): ?>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-3"><?= esc($job['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Degrees -->
        <?php if(!empty($degrees)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Education</h3>
                <div class="space-y-6">
                    <?php foreach($degrees as $degree): ?>
                        <div class="relative pl-6 border-l-2 border-slate-100 dark:border-slate-800">
                            <div class="absolute w-3 h-3 bg-slate-300 dark:bg-slate-600 rounded-full -left-[7px] top-1.5 border-2 border-white dark:border-slate-900"></div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white"><?= esc($degree['degree_name']) ?></h4>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300"><?= esc($degree['institution']) ?></p>
                            <p class="text-xs text-slate-500 mt-1">
                                <?= !empty($degree['start_date']) ? date('M Y', strtotime($degree['start_date'])) . ' - ' : '' ?>
                                <?= !empty($degree['end_date']) ? date('M Y', strtotime($degree['end_date'])) : '' ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Certifications -->
        <?php if(!empty($certifications)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Certifications</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach($certifications as $cert): ?>
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white"><?= esc($cert['name']) ?></h4>
                                <p class="text-xs text-slate-500 mt-0.5"><?= esc($cert['issuing_organization']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Courses -->
        <?php if(!empty($courses)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Professional Courses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach($courses as $course): ?>
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white"><?= esc($course['course_name']) ?></h4>
                                <p class="text-xs text-slate-500 mt-0.5"><?= esc($course['institution']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Achievements -->
        <?php if(!empty($achievements)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Achievements</h3>
                <div class="space-y-4">
                    <?php foreach($achievements as $achievement): ?>
                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                            <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mt-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div class="flex-grow">
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white"><?= esc($achievement['title']) ?></h4>
                                <?php if(!empty($achievement['date_earned'])): ?>
                                    <p class="text-xs text-slate-500 mt-1"><?= date('M d, Y', strtotime($achievement['date_earned'])) ?></p>
                                <?php endif; ?>
                                <?php if(!empty($achievement['description'])): ?>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-2"><?= esc($achievement['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Achievements</h3>
                <div class="p-8 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                    <svg class="mx-auto h-8 w-8 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No achievements added yet.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Projects -->
        <?php if(!empty($projects)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Projects</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach($projects as $project): ?>
                        <div class="flex flex-col p-5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                            <h4 class="text-base font-bold text-slate-900 dark:text-white"><?= esc($project['title']) ?></h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 flex-grow"><?= esc($project['description']) ?></p>
                            <?php if(!empty($project['link'])): ?>
                                <a href="<?= esc($project['link']) ?>" target="_blank" class="mt-4 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                    View Project <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Projects</h3>
                <div class="p-8 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                    <svg class="mx-auto h-8 w-8 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <p class="text-sm text-slate-500 dark:text-slate-400">No projects added yet.</p>
                </div>
            </div>
        <?php endif; ?>


        
    </div>
</div>

    <!-- Sponsor Modal -->
    <div x-show="isSponsorModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isSponsorModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="isSponsorModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isSponsorModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="bg-white dark:bg-slate-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Sponsor <?= esc($user['name']) ?></h3>
                        <button type="button" @click="isSponsorModalOpen = false" class="text-slate-400 hover:text-slate-500"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6 text-slate-700 dark:text-slate-300">
                    <div class="mb-6 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl border border-primary-100 dark:border-primary-800/30">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-bold text-primary-800 dark:text-primary-300">Sponsorship Rules</h4>
                            <?php if (isset($currentCycle)): ?>
                                <span class="text-xs font-semibold px-2 py-1 bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-200 rounded-lg">Cycle: <?= date('M d, Y', strtotime($currentCycle)) ?></span>
                            <?php endif; ?>
                        </div>
                        <ul class="text-xs text-primary-700 dark:text-primary-400 space-y-1 list-disc pl-4 mb-3">
                            <li>Minimum sponsorship amount is $1.00.</li>
                            <li>Sponsorships are active immediately upon submission.</li>
                            <li>You can sponsor the same alumni multiple times.</li>
                        </ul>
                        
                            <div x-show="previousTotal > 0" x-cloak class="pt-3 border-t border-primary-200 dark:border-primary-800/50">
                                <p class="text-sm font-medium text-primary-800 dark:text-primary-300 mb-2">
                                    You have already sponsored <strong class="text-primary-600 dark:text-primary-400">$<span x-text="previousTotal.toFixed(2)"></span></strong> for this alumni in the current cycle.
                                </p>
                                <div class="p-3 bg-white dark:bg-slate-800 rounded-lg border border-primary-200 dark:border-primary-700/50 shadow-sm">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">New Total For This Cycle:</p>
                                    <div class="flex items-center justify-between text-sm font-medium text-slate-700 dark:text-slate-300">
                                        <span>$<span x-text="previousTotal.toFixed(2)"></span> <span class="text-xs font-normal text-slate-400">(Previous)</span></span>
                                        <span class="text-slate-400">+</span>
                                        <span x-text="'$' + (parseFloat(amount) || 0).toFixed(2) + ' (New)'"></span>
                                        <span class="text-slate-400">=</span>
                                        <strong class="text-primary-600 dark:text-primary-400 text-base" x-text="'$' + newTotal.toFixed(2)"></strong>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div x-show="errorMessage" class="mb-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm border border-red-200" x-text="errorMessage"></div>

                    <form @submit.prevent="submitSponsorship">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sponsorship Amount ($)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" x-model="amount" step="0.01" min="1.00" class="pl-7 block w-full rounded-xl border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-slate-800 dark:border-slate-700 py-3" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" :disabled="isLoading" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
                                <span x-show="!isLoading">Submit Sponsorship</span>
                                <span x-show="isLoading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="isSuccessModalOpen" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isSuccessModalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="isSuccessModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="isSuccessModalOpen" class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-center overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full p-6 border border-slate-200 dark:border-slate-800">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-green-600 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Success!</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Your sponsorship has been successfully submitted.</p>
                <button @click="isSuccessModalOpen = false" class="w-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-white py-2 rounded-xl font-bold transition-colors">Close</button>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div x-show="isHistoryModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isHistoryModalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="isHistoryModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="isHistoryModalOpen" class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="bg-white dark:bg-slate-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Sponsorship History</h3>
                        <button type="button" @click="isHistoryModalOpen = false" class="text-slate-400 hover:text-slate-500"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6 text-slate-700 dark:text-slate-300">
                    <div x-show="isHistoryLoading" class="text-center py-4">
                        <svg class="animate-spin h-6 w-6 text-primary-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    
                    <div x-show="!isHistoryLoading && history.length === 0" class="text-center py-6 text-slate-500 dark:text-slate-400">
                        No sponsorships found for this alumni.
                    </div>
                    
                    <div x-show="!isHistoryLoading && history.length > 0" class="space-y-4 max-h-60 overflow-y-auto pr-2">
                        <template x-for="item in history" :key="item.id">
                            <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm" x-text="item.sponsor_name"></h4>
                                    <p class="text-xs text-slate-500 mt-1" x-text="item.display_date || new Date(item.created_at).toLocaleDateString()"></p>
                                </div>
                                <div class="text-right">
                                    <span class="block font-black text-primary-600 dark:text-primary-400" x-text="'$' + item.amount"></span>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase mt-1" :class="item.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" x-text="item.status"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function sponsorModule(alumniId, previousTotal) {
    return {
        previousTotal: previousTotal || 0,
        get newTotal() {
            const currentAmount = parseFloat(this.amount) || 0;
            return this.previousTotal + currentAmount;
        },
        isSponsorModalOpen: false,
        isSuccessModalOpen: false,
        isHistoryModalOpen: false,
        isLoading: false,
        isHistoryLoading: false,
        amount: '',
        errorMessage: '',
        history: [],
        
        async submitSponsorship() {
            this.isLoading = true;
            this.errorMessage = '';
            
            try {
                // Determine base URL, works for both localhost and prod
                const baseUrl = window.location.origin; 
                
                const response = await fetch(`${baseUrl}/api/alumni/sponsor`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        alumni_id: alumniId,
                        amount: this.amount
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    this.previousTotal += parseFloat(this.amount) || 0;
                    this.isSponsorModalOpen = false;
                    this.isSuccessModalOpen = true;
                    this.amount = '';
                } else {
                    this.errorMessage = result.message || 'An error occurred. Please try again.';
                }
            } catch (error) {
                this.errorMessage = 'Network error. Please try again.';
            } finally {
                this.isLoading = false;
            }
        },
        
        async openHistoryModal() {
            this.isHistoryModalOpen = true;
            this.isHistoryLoading = true;
            
            try {
                const baseUrl = window.location.origin;
                const response = await fetch(`${baseUrl}/api/alumni/sponsor/history/${alumniId}`, {
                    credentials: 'same-origin'
                });
                const result = await response.json();
                
                if (response.ok) {
                    this.history = result.data || [];
                }
            } catch (error) {
                console.error('Failed to fetch history', error);
            } finally {
                this.isHistoryLoading = false;
            }
        }
    }
}

function favoriteModule(alumniId, initialStatus) {
    return {
        isFavorited: initialStatus,
        isLoading: false,
        
        async toggleFavorite() {
            if (this.isLoading) return;
            this.isLoading = true;
            
            try {
                const baseUrl = window.location.origin;
                const response = await fetch(`${baseUrl}/api/student/favorite/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        alumni_id: alumniId
                    })
                });
                
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    this.isFavorited = result.is_favorite;
                }
            } catch (error) {
                console.error('Failed to toggle favorite', error);
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>

<?= $this->endSection() ?>
