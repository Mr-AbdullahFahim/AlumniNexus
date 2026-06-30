<?php
// Can be used as a full-page overlay or inline component
$fullPage = $fullPage ?? false;
$message = $message ?? 'Loading...';
?>
<?php if ($fullPage): ?>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity">
<?php endif; ?>

<div class="flex flex-col items-center justify-center p-4">
    <svg class="animate-spin h-8 w-8 text-primary-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 animate-pulse"><?= esc($message) ?></p>
</div>

<?php if ($fullPage): ?>
</div>
<?php endif; ?>
