<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div x-data="alumniDashboard()" x-init="fetchStats()">

    <!-- Error State -->
    <div x-show="error" style="display: none;" class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span x-text="error"></span>
        </div>
        <button @click="fetchStats()" class="mt-2 text-sm font-semibold hover:underline">Try Again</button>
    </div>

    <?= view_cell('\App\Cells\FeaturedAlumniCell::render') ?>

    <!-- Top Summary Grid -->
    
    <!-- Countdown Timer -->
    <div x-show="!isLoading && stats.next_cycle_end_time" style="display: none;" class="bg-indigo-600 dark:bg-indigo-900 rounded-2xl p-6 text-white shadow-md mb-8 flex flex-col sm:flex-row items-center justify-between">
        <div>
            <h3 class="text-xl font-bold">Next Cycle Begins In:</h3>
            <p class="text-indigo-200 text-sm mt-1">Bids settle daily at 6:00 PM</p>
        </div>
        <div class="text-3xl font-mono font-bold mt-4 sm:mt-0 tracking-widest bg-indigo-800/50 px-6 py-3 rounded-xl border border-indigo-500/30">
            <span x-text="countdown">00:00:00</span>
        </div>
    </div>
    <div x-show="!isLoading" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <!-- Remaining Wins -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col justify-center">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Remaining Monthly Wins</p>
            <div class="mt-2">
                <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="(stats.remaining_wins ?? 0) + ' / 3'"></p>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 mt-3">
                <div class="bg-primary-500 h-2 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + (((stats.remaining_wins ?? 0) / 3) * 100) + '%'"></div>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2" x-show="stats.quota_reached">You have reached the maximum wins for this month.</p>
        </div>
        
        <!-- Quick Sponsor Action -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col justify-center items-start">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Want more visibility?</p>
            <p class="text-xl font-bold text-slate-900 dark:text-white mt-2">Request Sponsorships</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 mb-4">Share your profile link to receive more sponsorships and increase your bidding power.</p>
            <button @click="copyProfileLink()" class="px-5 py-2.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 font-semibold rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                <span x-ref="copyBtnText">Share Profile Link</span>
            </button>
        </div>
    </div>

    <!-- Bidding Cycles List -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Bidding Cycles Records</h3>
            <button type="button" @click.prevent="openGlobalHistoryModal()" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold rounded-lg text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors border border-transparent dark:border-indigo-800">Global History</button>
        </div>
        
        <div x-show="isLoading" class="p-6">
            <?= view('components/skeleton', ['type' => 'list', 'count' => 3]) ?>
        </div>
        
        <div x-show="!isLoading" style="display: none;">
            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                <template x-for="cycle in stats.cycles" :key="cycle.id">
                    <li class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <p class="text-base font-semibold text-slate-900 dark:text-white" x-text="cycle.date"></p>
                                    <span x-show="cycle.is_active" class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">Current Cycle</span>
                                </div>
                                <div class="mt-1 flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                                    <span>Sponsorships: <span class="font-medium text-slate-700 dark:text-slate-300" x-text="'$' + cycle.total_sponsorships.toFixed(2)"></span></span>
                                    <span>Bid: <span class="font-medium text-slate-700 dark:text-slate-300" x-text="'$' + cycle.bid_amount.toFixed(2)"></span></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border"
                                      :class="{
                                          'bg-slate-50 border-slate-200 text-slate-600 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400': cycle.status === 'pending',
                                          'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-400': cycle.status === 'won',
                                          'bg-red-50 border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400': cycle.status === 'lost'
                                      }">
                                    <span class="w-1.5 h-1.5 rounded-full" 
                                          :class="{
                                              'bg-slate-400': cycle.status === 'pending',
                                              'bg-amber-500': cycle.status === 'won',
                                              'bg-red-500': cycle.status === 'lost'
                                          }"></span>
                                    <span x-text="cycle.status.charAt(0).toUpperCase() + cycle.status.slice(1)"></span>
                                </span>
                                <button @click="openCycleModal(cycle)" class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    View
                                </button>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
        
        <div x-show="!isLoading && stats.cycles?.length === 0" style="display: none;">
            <?= view('components/empty_state', [
                'emptyTitle' => 'No cycles found',
                'message' => 'You haven\'t participated in any bidding cycles yet.',
                'actionText' => 'Refresh',
                'actionUrl' => 'javascript:window.location.reload()'
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
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400" x-text="selectedCycle?.date"></span>
                        </h3>
                        <button @click="closeCycleModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Cycle Financials -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Total Sponsorships</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white" x-text="'$' + (selectedCycle?.total_sponsorships || 0).toFixed(2)"></p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Current Bid</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white" x-text="'$' + (selectedCycle?.bid_amount || 0).toFixed(2)"></p>
                        </div>
                        
                        <!-- Remaining Winnings (Only if won) -->
                        <div x-show="selectedCycle?.status === 'won'" class="col-span-2 bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-200 dark:border-amber-800/30 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-amber-700 dark:text-amber-400 mb-1 font-semibold">Remaining Winnings</p>
                                <p class="text-xs text-amber-600 dark:text-amber-500">Sponsorships minus winning bid</p>
                            </div>
                            <p class="text-2xl font-bold text-amber-700 dark:text-amber-400" x-text="'$' + (selectedCycle?.remaining_winnings || 0).toFixed(2)"></p>
                        </div>
                    </div>

                    <!-- Sponsors List -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Sponsors for this Cycle
                        </h4>
                        
                        <div x-show="!selectedCycle?.sponsors || selectedCycle.sponsors.length === 0" class="text-sm text-slate-500 italic p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg text-center">
                            No sponsorships received during this cycle.
                        </div>
                        
                        <ul x-show="selectedCycle?.sponsors && selectedCycle.sponsors.length > 0" class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-100 dark:border-slate-800 rounded-lg max-h-48 overflow-y-auto">
                            <template x-for="(sp, idx) in selectedCycle?.sponsors" :key="idx">
                                <li class="p-3 flex justify-between items-center text-sm">
                                    <span class="font-medium text-slate-700 dark:text-slate-300" x-text="sp.sponsor_name"></span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold" x-text="'+$' + parseFloat(sp.amount).toFixed(2)"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Bidding Controls (Only active/pending cycles) -->
                    <div x-show="selectedCycle?.is_active && selectedCycle?.status === 'pending'">
                        <div x-show="stats.quota_reached" class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-200 text-sm mb-4">
                            You have reached your monthly winning quota (3/3). You cannot place bids until next month.
                        </div>
                        
                        <div x-show="!stats.quota_reached && selectedCycle?.total_sponsorships < 1" class="p-4 bg-amber-50 text-amber-700 rounded-xl border border-amber-200 text-sm mb-4">
                            You need at least $1.00 in total sponsorships this cycle to place a bid. Share your profile to get sponsored!
                        </div>

                        <div x-show="!stats.quota_reached && selectedCycle?.total_sponsorships >= 1">
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Update Your Bid</h4>
                                
                                <div x-show="bidError" class="mb-4 bg-red-50 text-red-600 p-3 rounded-xl border border-red-200 text-sm" x-text="bidError"></div>

                                <form @submit.prevent="submitBid">
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">New Bid Amount ($)</label>
                                        <input type="number" step="1" 
                                               :min="Math.max(1, (selectedCycle?.bid_amount || 0) + 1)" 
                                               :max="selectedCycle?.total_sponsorships" 
                                               x-model.number="bidAmount" 
                                               class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all dark:text-white text-lg font-bold shadow-sm">
                                        <p class="text-xs text-slate-500 mt-2">Must be higher than your current bid and less than or equal to your total sponsorships.</p>
                                    </div>
                                    <div class="flex justify-end gap-3">
                                        <button type="submit" :disabled="isSubmittingBid" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold rounded-xl shadow-md transition-all flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed w-full sm:w-auto">
                                            <svg x-show="isSubmittingBid" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span x-text="isSubmittingBid ? 'Updating...' : (selectedCycle?.bid_amount > 0 ? 'Increase Bid' : 'Place Bid')"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
                                
                                <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-100 dark:border-slate-800" x-show="expandedHistoryCycle && !expandedHistoryCycle.is_me">
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        <span class="font-bold">Your Bid:</span> 
                                        <span x-show="expandedHistoryCycle?.my_bid !== null" class="text-indigo-600 dark:text-indigo-400 font-semibold" x-text="'$' + parseFloat(expandedHistoryCycle?.my_bid || 0).toFixed(2)"></span>
                                        <span x-show="expandedHistoryCycle?.my_bid === null" class="text-slate-500 italic font-normal">You did not place a bid.</span>
                                    </p>
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
function alumniDashboard() {
    return {
        isLoading: true,
        error: null,
        stats: {},
        
        // Countdown
        countdown: '00:00:00',
        timer: null,

        // Modal State
        selectedCycle: null,
        bidAmount: 1,
        isSubmittingBid: false,
        bidError: null,
        
        // Global History State
        showGlobalHistory: false,
        globalHistory: [],
        isHistoryLoading: false,
        expandedHistoryCycle: null,

        init() {
            this.fetchStats();
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
                
                const hours = Math.floor((diff / (1000 * 60 * 60))); // Allow > 24 hours
                const mins = Math.floor((diff / 1000 / 60) % 60);
                const secs = Math.floor((diff / 1000) % 60);
                
                this.countdown = 
                    String(hours).padStart(2, '0') + ':' + 
                    String(mins).padStart(2, '0') + ':' + 
                    String(secs).padStart(2, '0');
            }, 1000);
        },

        openCycleModal(cycle) {
            this.selectedCycle = cycle;
            this.bidError = null;
            if (cycle.is_active) {
                this.bidAmount = Math.max(1, (cycle.bid_amount || 0) + 1);
            }
        },
        
        closeCycleModal() {
            this.selectedCycle = null;
            this.bidError = null;
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

        copyProfileLink() {
            if (!this.stats.alumni_id) return;
            const url = '<?= base_url('alumni/profile/') ?>' + this.stats.alumni_id;
            navigator.clipboard.writeText(url).then(() => {
                const span = this.$refs.copyBtnText;
                if(span) {
                    const original = span.innerText;
                    span.innerText = 'Copied!';
                    setTimeout(() => span.innerText = original, 2000);
                }
            }).catch(err => {
                console.error("Failed to copy link:", err);
            });
        },

        async fetchGlobalHistory() {
            this.isHistoryLoading = true;
            try {
                const response = await fetch('<?= base_url('api/alumni/dashboard/history') ?>', {
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
        },

        async submitBid() {
            this.isSubmittingBid = true;
            this.bidError = null;
            try {
                const response = await fetch('<?= base_url('api/alumni/bid') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ amount: this.bidAmount })
                });

                const data = await response.json();
                if (!response.ok) {
                    if (data.messages && data.messages.amount) throw new Error(data.messages.amount);
                    if (data.message) throw new Error(data.message);
                    throw new Error('Failed to place bid.');
                }

                this.closeCycleModal();
                await this.fetchStats(); 
            } catch (err) {
                this.bidError = err.message;
            } finally {
                this.isSubmittingBid = false;
            }
        },

        async fetchStats() {
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await fetch('<?= base_url('api/alumni/dashboard/stats') ?>', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    if (response.status === 401 || response.status === 403) {
                        window.location.href = '<?= base_url('auth/login') ?>';
                        return;
                    }
                    throw new Error('Failed to load dashboard data');
                }
                
                this.stats = await response.json();
                if (this.stats.server_time && this.stats.next_cycle_end_time) {
                    this.startCountdown(this.stats.next_cycle_end_time, this.stats.server_time);
                }
            } catch (err) {
                this.error = err.message;
            } finally {
                this.isLoading = false;
            }
        },
    }
}
</script>

<?= $this->endSection() ?>
