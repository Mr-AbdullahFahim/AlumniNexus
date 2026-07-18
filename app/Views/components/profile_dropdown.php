<?php
$dbUser = null;
$profile = null;
if (isset(service('request')->user->sub)) {
    $userModel = new \App\Models\UserModel();
    $dbUser = $userModel->find(service('request')->user->sub);
    
    $profileModel = new \App\Models\ProfileModel();
    $profile = $profileModel->where('user_id', service('request')->user->sub)->first();
}
$userName = $dbUser ? esc($dbUser['name']) : 'User Name';
$userEmail = $dbUser ? esc($dbUser['email']) : 'user@example.com';
$initial = $dbUser ? strtoupper(substr($dbUser['name'], 0, 1)) : 'U';
$photoUrl = ($profile && !empty($profile['photo_url'])) ? esc($profile['photo_url']) : null;
?>
<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <div>
        <button @click="open = !open" type="button" class="max-w-xs bg-white dark:bg-slate-900 flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
            <span class="sr-only">Open user menu</span>
            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-primary-500 to-primary-600 text-white flex items-center justify-center font-bold shadow-md overflow-hidden">
                <?php if ($photoUrl): ?>
                    <img src="<?= $photoUrl ?>" alt="<?= $userName ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <?= $initial ?>
                <?php endif; ?>
            </div>

        </button>
    </div>

    <!-- Dropdown menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95" 
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none z-50" 
         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" x-cloak>
        
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 mb-1">
            <p class="text-sm font-medium text-slate-900 dark:text-white truncate"><?= $userName ?></p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate"><?= $userEmail ?></p>
        </div>

<?php
$profileUrl = base_url('alumni/profile'); // Default
if ($dbUser && $dbUser['role_id'] == 4) {
    $profileUrl = base_url('sponsor/profile');
} elseif ($dbUser && $dbUser['role_id'] == 3) {
    $profileUrl = base_url('student/profile');
}
?>
        <a href="<?= $profileUrl ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white transition-colors" role="menuitem" tabindex="-1">Your Profile</a>
        <a href="<?= base_url('settings') ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white transition-colors" role="menuitem" tabindex="-1">Settings</a>
        
        <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
        
        <!-- Assuming JS will handle the API logout call -->
        <a href="#" @click.prevent="logout()" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors" role="menuitem" tabindex="-1">Sign out</a>
    </div>
</div>

<script>
    // Global Alpine component for logout, can be placed here or in app.js
    function logout() {
        fetch('<?= base_url('api/auth/logout') ?>', { method: 'POST' })
            .then(() => window.location.href = '<?= base_url('auth/login') ?>');
    }
</script>
