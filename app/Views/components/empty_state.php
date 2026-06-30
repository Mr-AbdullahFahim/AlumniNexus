<?php
$title = $title ?? 'No data available';
$message = $message ?? 'There is currently no data to display in this section.';
$icon = $icon ?? '<svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>';
$actionText = $actionText ?? null;
$actionUrl = $actionUrl ?? '#';
?>

<div class="text-center py-16 px-4 sm:px-6 lg:px-8 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50/50 dark:bg-slate-900/50">
    <?= $icon ?>
    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"><?= esc($title) ?></h3>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400"><?= esc($message) ?></p>
    
    <?php if ($actionText): ?>
    <div class="mt-6">
        <a href="<?= esc($actionUrl) ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
            <!-- Plus icon -->
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <?= esc($actionText) ?>
        </a>
    </div>
    <?php endif; ?>
</div>
