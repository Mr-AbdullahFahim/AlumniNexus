<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" type="button" class="bg-white dark:bg-slate-900 p-1 rounded-full text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
        <span class="sr-only">View notifications</span>
        <div class="relative">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <!-- Unread badge -->
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white dark:ring-slate-900"></span>
        </div>
    </button>

    <!-- Dropdown menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95" 
         class="origin-top-right absolute right-0 mt-2 w-80 rounded-xl shadow-lg py-1 bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-50 overflow-hidden" 
         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" x-cloak>
        
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            <p class="text-sm font-medium text-slate-900 dark:text-white">Notifications</p>
        </div>
        
        <div class="max-h-64 overflow-y-auto">
            <!-- Sample Notification -->
            <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-b border-slate-50 dark:border-slate-700/50">
                <p class="text-sm text-slate-700 dark:text-slate-300"><strong>John Doe</strong> placed a bid on your profile.</p>
                <p class="text-xs text-slate-400 mt-1">2 mins ago</p>
            </a>
            <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <p class="text-sm text-slate-700 dark:text-slate-300">Your account was approved by Admin.</p>
                <p class="text-xs text-slate-400 mt-1">1 hr ago</p>
            </a>
        </div>
        
        <a href="#" class="block px-4 py-3 text-sm font-medium text-center text-primary-600 hover:text-primary-700 dark:text-primary-400 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
            View all
        </a>
    </div>
</div>
