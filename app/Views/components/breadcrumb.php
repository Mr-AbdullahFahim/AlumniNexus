<?php
// Default breadcrumbs if none provided
$breadcrumbs = $breadcrumbs ?? [['name' => 'Dashboard', 'url' => base_url('dashboard')]];
?>
<nav class="hidden sm:flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <li>
                <div class="flex items-center">
                    <?php if ($index > 0): ?>
                        <svg class="flex-shrink-0 h-5 w-5 text-slate-400 dark:text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                    <?php endif; ?>
                    
                    <?php if ($index === count($breadcrumbs) - 1): ?>
                        <!-- Current Page -->
                        <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-200" aria-current="page">
                            <?= esc($crumb['name']) ?>
                        </span>
                    <?php else: ?>
                        <!-- Link -->
                        <a href="<?= esc($crumb['url'] ?? '#') ?>" class="ml-2 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors">
                            <?= esc($crumb['name']) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
