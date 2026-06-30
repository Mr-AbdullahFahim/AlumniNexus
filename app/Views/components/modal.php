<?php
/**
 * Reusable Alpine.js Modal Component
 * 
 * Usage:
 * <div x-data="{ openModal: false }">
 *     <button @click="openModal = true">Open</button>
 *     <?= view('components/modal', ['model' => 'openModal', 'title' => 'My Modal']) ?>
 *         <p>Modal Content</p>
 *     <?= view('components/modal_footer') ?> // Wait, standard slots are better.
 * </div>
 * 
 * Because CI4 views don't have block slots like Blade, we'll pass Alpine variables to bind to.
 */
$modelName = $model ?? 'modalOpen';
$title = $title ?? 'Modal Title';
$contentId = $contentId ?? 'modal-content';
?>

<div x-show="<?= $modelName ?>" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Background overlay -->
        <div x-show="<?= $modelName ?>" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true" 
             @click="<?= $modelName ?> = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div x-show="<?= $modelName ?>" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
            
            <div class="bg-white dark:bg-slate-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900 dark:text-white" id="modal-title">
                            <?= esc($title) ?>
                        </h3>
                    </div>
                    <button type="button" @click="<?= $modelName ?> = false" class="ml-auto flex-shrink-0 bg-white dark:bg-slate-900 rounded-md text-slate-400 hover:text-slate-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
            
            <div class="px-4 py-5 sm:p-6 text-slate-700 dark:text-slate-300">
                <!-- Content injected via Alpine dynamically or hardcoded inside the div wrapping this -->
                <template x-if="true">
                    <div id="<?= esc($contentId) ?>"></div>
                </template>
            </div>
            
        </div>
    </div>
</div>
