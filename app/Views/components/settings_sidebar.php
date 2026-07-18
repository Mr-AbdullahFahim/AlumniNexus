<?php
$userRole = service('request')->user->role ?? null;

$backLink = base_url('alumni/dashboard');
$backText = 'Back to Dashboard';

if ($userRole == 4) {
    $backLink = base_url('sponsor/dashboard');
} elseif ($userRole == 3) {
    $backLink = base_url('student/favorites');
    $backText = 'Back to Favorites';
}
?>
<!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
<div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" x-cloak role="dialog" aria-modal="true">
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-600 bg-opacity-75" aria-hidden="true" @click="sidebarOpen = false"></div>

    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 pb-4 pt-5">
        
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button @click="sidebarOpen = false" type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="flex-shrink-0 flex items-center px-4 gap-2">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">A</div>
            <span class="text-xl font-bold text-slate-900 dark:text-white">AlumniNexus</span>
        </div>
        
        <div class="mt-5 flex-1 h-0 overflow-y-auto flex flex-col">
            <nav class="px-2 space-y-1 flex-1 flex flex-col">
                
                <!-- Back Button -->
                <a href="<?= $backLink ?>" class="text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white group flex items-center px-2 py-2 text-base font-medium rounded-md transition-colors mb-4">
                    <svg class="mr-4 flex-shrink-0 h-6 w-6 text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?= $backText ?>
                </a>

                <!-- Account Settings -->
                <a href="<?= base_url('settings') ?>" class="bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                    <svg class="mr-4 flex-shrink-0 h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Account Settings
                </a>
            </nav>
        </div>
        <div class="flex-shrink-0 flex border-t border-slate-200 dark:border-slate-800 p-4">
            <a href="<?= base_url('/') ?>" class="text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white transition-colors group w-full flex items-center px-2 py-2 text-base font-medium rounded-md">
                <svg class="mr-4 flex-shrink-0 h-6 w-6 text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Home
            </a>
        </div>
    </div>
    
    <div class="flex-shrink-0 w-14" aria-hidden="true">
        <!-- Dummy element to force sidebar to shrink to fit close icon -->
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:flex-shrink-0 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-colors duration-300">
    <div class="flex flex-col w-64">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex-1 flex flex-col min-h-0">
            <div class="flex items-center h-16 flex-shrink-0 px-4 border-b border-slate-100 dark:border-slate-800 gap-2">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">A</div>
                <span class="text-xl font-bold text-slate-900 dark:text-white">AlumniNexus</span>
            </div>
            
            <div class="flex-1 flex flex-col overflow-y-auto">
                <nav class="flex-1 px-3 py-4 space-y-1 flex flex-col">
                    
                    <!-- Back Button -->
                    <a href="<?= $backLink ?>" class="text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors mb-4">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <?= $backText ?>
                    </a>

                    <!-- Account Settings -->
                    <a href="<?= base_url('settings') ?>" class="bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Account Settings
                    </a>
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-slate-200 dark:border-slate-800 p-4">
                <a href="<?= base_url('/') ?>" class="text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white transition-colors group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5 text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
            </div>
        </div>
    </div>
</div>
