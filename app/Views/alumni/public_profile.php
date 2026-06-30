<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

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
        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col items-center text-center">
            <img src="<?= !empty($general['photo_url']) ? esc($general['photo_url']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=random' ?>" 
                 class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-slate-900 shadow-sm mb-4">
            
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white"><?= esc($user['name']) ?></h1>
            
            <?php if(!empty($general['position']) && !empty($general['company'])): ?>
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mt-1"><?= esc($general['position']) ?> at <?= esc($general['company']) ?></p>
            <?php elseif(!empty($general['position'])): ?>
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mt-1"><?= esc($general['position']) ?></p>
            <?php endif; ?>

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
                    <button class="w-full py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Sponsor Alumni
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

<?= $this->endSection() ?>
