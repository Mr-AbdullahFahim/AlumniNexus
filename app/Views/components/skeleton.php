<?php
// Types: 'card', 'list', 'table', 'text'
$type = $type ?? 'card'; 
$count = $count ?? 1;
?>

<div class="animate-pulse space-y-4">
    <?php for ($i = 0; $i < $count; $i++): ?>
        
        <?php if ($type === 'card'): ?>
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-4 mb-4">
                <div class="rounded-full bg-slate-200 dark:bg-slate-800 h-12 w-12"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-1/4"></div>
                    <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-1/3"></div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
                <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-full"></div>
                <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-5/6"></div>
            </div>
        </div>
        
        <?php elseif ($type === 'list'): ?>
        <div class="flex items-center space-x-4 py-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
            <div class="rounded-full bg-slate-200 dark:bg-slate-800 h-10 w-10"></div>
            <div class="flex-1 space-y-2">
                <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-1/3"></div>
                <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded w-1/4"></div>
            </div>
            <div class="h-8 w-20 bg-slate-200 dark:bg-slate-800 rounded-lg"></div>
        </div>

        <?php elseif ($type === 'text'): ?>
        <div class="space-y-2">
            <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
            <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-full"></div>
            <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-5/6"></div>
        </div>
        <?php endif; ?>

    <?php endfor; ?>
</div>
