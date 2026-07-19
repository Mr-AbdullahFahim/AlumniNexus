<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div x-data="sponsorDashboard()" x-init="fetchStats()">

    <!-- Error State -->
    <div x-show="error" style="display: none;" class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span x-text="error"></span>
        </div>
        <button @click="fetchStats()" class="mt-2 text-sm font-semibold hover:underline">Try Again</button>
    </div>

    <?= view_cell('\App\Cells\FeaturedAlumniCell::render') ?>

    <!-- Next Cycle Banner -->
    <div x-show="!isLoading" style="display: none;" class="mb-6 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold tracking-tight">Next Cycle Begins In</h2>
                <p class="text-indigo-100 text-sm mt-1">Sponsor alumni before the settlement at 6:00 PM</p>
            </div>
        </div>
        <div class="relative z-10 bg-black/20 backdrop-blur-md px-6 py-3 rounded-xl border border-white/10 font-mono text-3xl font-bold tracking-wider">
            <span x-text="countdown"></span>
        </div>
    </div>

    <!-- Current Active Cycle Section -->
    <div x-show="!isLoading && currentCycleDate" style="display: none;" class="mb-8 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-900/30 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-emerald-50/50 dark:bg-emerald-900/10">
            <div>
                <h3 class="text-lg font-bold text-emerald-800 dark:text-emerald-400 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Current Ongoing Cycle
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1" x-text="new Date(currentCycleDate).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})"></p>
            </div>
            <a href="<?= base_url('directory') ?>" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Sponsor Alumni
            </a>
        </div>
        <div class="p-6">
            <template x-if="getCurrentCycleData()">
                <div>
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 uppercase tracking-wider">Your Sponsorships Today</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <template x-for="(sp, idx) in getCurrentCycleData().sponsorships" :key="idx">
                            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                                <a :href="'<?= base_url('alumni/profile/') ?>' + sp.alumni_id" class="flex items-center gap-3 group">
                                    <img :src="sp.photo_url ? (sp.photo_url.startsWith('http') ? sp.photo_url : '<?= base_url() ?>' + sp.photo_url) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(sp.alumni_name) + '&background=random'" alt="Profile" class="w-10 h-10 rounded-full object-cover group-hover:opacity-75 transition-opacity">
                                    <span class="font-semibold text-slate-900 dark:text-white group-hover:opacity-75 transition-opacity" x-text="sp.alumni_name"></span>
                                </a>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="'+$' + parseFloat(sp.amount).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="!getCurrentCycleData()">
                <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                    <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>You haven't sponsored anyone in the current cycle yet.</p>
                </div>
            </template>
        </div>
    </div>

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Your Sponsorship History</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Track the alumni you've sponsored and see if they won their bidding cycles.</p>
        </div>
        <button type="button" @click.prevent="openGlobalHistoryModal()" class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold rounded-xl text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-all border border-transparent dark:border-indigo-800 shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Global Cycle History
        </button>
    </div>

    <!-- Cycles List -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Cycle Records</h3>
        </div>
        
        <div x-show="isLoading" class="p-6">
            <?= view('components/skeleton', ['type' => 'list', 'count' => 3]) ?>
        </div>
        
        <div x-show="!isLoading" style="display: none;">
            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                <template x-for="cycle in cycles" :key="cycle.cycle_date">
                    <li class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <p class="text-base font-semibold text-slate-900 dark:text-white" x-text="new Date(cycle.cycle_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})"></p>
                                </div>
                                <div class="mt-1 flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                                    <span>Total Offered: <span class="font-medium text-slate-700 dark:text-slate-300" x-text="'$' + cycle.total_amount.toFixed(2)"></span></span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <div class="flex gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-400" title="Successful Sponsorships (Alumni Won)">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span x-text="'$' + cycle.successful_amount.toFixed(2) + ' Won'"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border bg-red-50 border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400" title="Failed Sponsorships (Alumni Lost)">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        <span x-text="'$' + cycle.failed_amount.toFixed(2) + ' Failed'"></span>
                                    </span>
                                </div>
                                <button @click="openCycleModal(cycle)" class="mt-2 px-4 py-1.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
        
        <div x-show="!isLoading && cycles?.length === 0" style="display: none;">
            <?= view('components/empty_state', [
                'emptyTitle' => 'No sponsorships found',
                'message' => 'You haven\'t offered any sponsorships yet.',
                'actionText' => 'Browse Alumni',
                'actionUrl' => base_url('directory')
            ]) ?>
        </div>
    </div>

    <!-- View Cycle Modal -->
    <div x-show="selectedCycle !== null" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="selectedCycle !== null" x-transition.opacity class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="selectedCycle !== null" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="closeCycleModal()"
                 class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="px-6 pt-6 pb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            Cycle Details
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400" x-text="selectedCycle ? new Date(selectedCycle.cycle_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : ''"></span>
                        </h3>
                        <button @click="closeCycleModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Sponsored Alumni</h4>
                        <ul class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-100 dark:border-slate-800 rounded-lg max-h-64 overflow-y-auto">
                            <template x-for="(sp, idx) in selectedCycle?.sponsorships" :key="idx">
                                <li class="p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white" x-text="sp.alumni_name"></p>
                                        <p class="text-sm text-slate-500" x-text="'$' + parseFloat(sp.amount).toFixed(2)"></p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border"
                                              :class="{
                                                  'bg-slate-50 border-slate-200 text-slate-600 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400': sp.bid_status === 'pending',
                                                  'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-400': sp.bid_status === 'won',
                                                  'bg-red-50 border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400': sp.bid_status === 'lost'
                                              }">
                                            <span class="w-1.5 h-1.5 rounded-full" 
                                                  :class="{
                                                      'bg-slate-400': sp.bid_status === 'pending',
                                                      'bg-amber-500': sp.bid_status === 'won',
                                                      'bg-red-500': sp.bid_status === 'lost'
                                                  }"></span>
                                            <span x-text="sp.bid_status === 'won' ? 'Alumni Won Bid' : (sp.bid_status === 'lost' ? 'Failed Sponsorship' : 'Pending Bid Result')"></span>
                                        </span>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global History Modal -->
    <div x-show="showGlobalHistory" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showGlobalHistory" x-transition.opacity class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showGlobalHistory" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="closeGlobalHistoryModal()"
                 class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        Global Cycle History
                    </h3>
                    <button @click="closeGlobalHistoryModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <div x-show="isHistoryLoading" class="text-center py-8">
                        <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-slate-500 mt-4">Loading history...</p>
                    </div>

                    <div x-show="!isHistoryLoading && globalHistory.length === 0" class="text-center py-8 text-slate-500">
                        No historical cycles found.
                    </div>

                    <div x-show="!isHistoryLoading && globalHistory.length > 0">
                        <!-- List View -->
                        <ul x-show="expandedHistoryCycle === null" class="space-y-3">
                            <template x-for="(hist, hIdx) in globalHistory" :key="hIdx">
                                <li class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm flex items-center justify-between hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors cursor-pointer" @click="expandedHistoryCycle = hist">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 p-2 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="font-semibold text-slate-900 dark:text-white" x-text="'Cycle: ' + new Date(hist.cycle_date).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})"></p>
                                    </div>
                                    <button class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition-colors">
                                        View Details
                                    </button>
                                </li>
                            </template>
                        </ul>

                        <!-- Detail View -->
                        <div x-show="expandedHistoryCycle !== null">
                            <button @click="expandedHistoryCycle = null" class="mb-4 text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Back to cycles
                            </button>
                            
                            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm" x-show="expandedHistoryCycle">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 pb-6 border-b border-slate-100 dark:border-slate-700">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 p-3 rounded-xl">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400" x-text="new Date(expandedHistoryCycle?.cycle_date || new Date()).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})"></p>
                                            <p class="text-2xl font-bold text-slate-900 dark:text-white" x-text="expandedHistoryCycle?.winner_name"></p>
                                        </div>
                                    </div>
                                    <div class="text-right mt-4 sm:mt-0">
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Winning Bid</p>
                                        <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400" x-text="'$' + parseFloat(expandedHistoryCycle?.winning_bid || 0).toFixed(2)"></p>
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 uppercase tracking-wider">Sponsors for this cycle</p>
                                    <div x-show="!expandedHistoryCycle?.sponsors || expandedHistoryCycle?.sponsors.length === 0" class="text-sm text-slate-400 italic">No sponsors.</div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(sp, sIdx) in (expandedHistoryCycle?.sponsors || [])" :key="sIdx">
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                                <span x-text="sp.sponsor_name"></span>
                                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold" x-text="'+$' + parseFloat(sp.amount).toFixed(2)"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sponsorDashboard() {
    return {
        isLoading: true,
        error: null,
        cycles: [],
        currentCycleDate: null,
        
        // Countdown
        countdown: '00:00:00',
        timer: null,

        // Modals
        selectedCycle: null,
        
        showGlobalHistory: false,
        globalHistory: [],
        isHistoryLoading: false,
        expandedHistoryCycle: null,
        
        getCurrentCycleData() {
            if (!this.currentCycleDate || !this.cycles.length) return null;
            const current = this.cycles.find(c => c.cycle_date === this.currentCycleDate);
            return current || null;
        },

        openCycleModal(cycle) {
            this.selectedCycle = cycle;
        },
        
        closeCycleModal() {
            this.selectedCycle = null;
        },

        async fetchStats() {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await fetch('<?= base_url('api/sponsor/dashboard/stats') ?>', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    if (response.status === 401 || response.status === 403) {
                        window.location.href = '<?= base_url('auth/login') ?>';
                        return;
                    }
                    throw new Error('Failed to load dashboard data');
                }
                
                const data = await response.json();
                this.cycles = data.cycles || [];
                this.currentCycleDate = data.current_cycle_date;
                
                if (data.server_time && data.next_cycle_end_time) {
                    this.startCountdown(data.next_cycle_end_time, data.server_time);
                }
            } catch (err) {
                this.error = err.message;
            } finally {
                this.isLoading = false;
            }
        },

        startCountdown(nextEndTimeString, serverTimeString) {
            if (this.timer) clearInterval(this.timer);
            
            const serverNow = new Date(serverTimeString).getTime();
            const localNow = new Date().getTime();
            const timeDiff = serverNow - localNow;
            
            const targetTime = new Date(nextEndTimeString).getTime();

            this.timer = setInterval(() => {
                const now = new Date(new Date().getTime() + timeDiff).getTime();
                const diff = targetTime - now;
                
                if (diff <= 0) {
                    this.countdown = '00:00:00';
                    clearInterval(this.timer);
                    return;
                }
                
                const hours = Math.floor((diff / (1000 * 60 * 60)));
                const mins = Math.floor((diff / 1000 / 60) % 60);
                const secs = Math.floor((diff / 1000) % 60);
                
                this.countdown = 
                    String(hours).padStart(2, '0') + ':' + 
                    String(mins).padStart(2, '0') + ':' + 
                    String(secs).padStart(2, '0');
            }, 1000);
        },

        openGlobalHistoryModal() {
            this.showGlobalHistory = true;
            if (this.globalHistory.length === 0) {
                this.fetchGlobalHistory();
            }
        },

        closeGlobalHistoryModal() {
            this.showGlobalHistory = false;
            this.expandedHistoryCycle = null;
        },

        async fetchGlobalHistory() {
            this.isHistoryLoading = true;
            try {
                const response = await fetch('<?= base_url('api/sponsor/dashboard/history') ?>', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.globalHistory = data.history || [];
                }
            } catch (err) {
                console.error("Failed to load global history:", err);
            } finally {
                this.isHistoryLoading = false;
            }
        }
    }
}
</script>

<?= $this->endSection() ?>
