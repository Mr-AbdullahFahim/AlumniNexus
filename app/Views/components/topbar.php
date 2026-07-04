<div class="sticky top-0 z-30 flex-shrink-0 flex h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-300">
    <!-- Mobile hamburger -->
    <button @click="sidebarOpen = true" type="button" class="px-4 border-r border-slate-200 dark:border-slate-800 text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 md:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
        </svg>
    </button>
    
    <div class="flex-1 px-4 flex justify-between">
        
        <!-- Breadcrumb Area -->
        <div class="flex-1 flex items-center">
            <?= view('components/breadcrumb', ['breadcrumbs' => $breadcrumbs ?? []]) ?>
        </div>
        
        <div class="ml-4 flex items-center md:ml-6 gap-3">
            
            <!-- Profile dropdown -->
            <?= view('components/profile_dropdown') ?>
            
        </div>
    </div>
</div>
