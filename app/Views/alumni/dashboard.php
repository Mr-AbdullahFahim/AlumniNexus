<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="alumniDashboard()" x-init="fetchStats()">

    <!-- Error State -->
    <div x-show="error" style="display: none;" class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span x-text="error"></span>
        </div>
        <button @click="fetchStats()" class="mt-2 text-sm font-semibold hover:underline">Try Again</button>
    </div>

    <!-- Featured Banner Skeleton -->
    <div x-show="isLoading" class="mb-8">
        <?= view('components/skeleton', ['type' => 'card', 'count' => 1]) ?>
    </div>

    <!-- Featured Banner Loaded -->
    <div x-show="!isLoading && stats.featured_alumni?.is_featured" style="display: none;" 
         class="mb-8 bg-gradient-to-r from-primary-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Today's Featured Alumni!</h2>
                    <p class="text-primary-100 mt-1 text-sm sm:text-base" x-text="stats.featured_alumni?.message"></p>
                </div>
            </div>
            <a href="#" class="px-5 py-2.5 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors shadow-sm whitespace-nowrap">
                View Public Profile
            </a>
        </div>
    </div>

    <!-- Stats Grid Skeleton -->
    <div x-show="isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php for($i=0; $i<4; $i++): ?>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 h-32">
                <div class="animate-pulse flex flex-col justify-between h-full">
                    <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-1/2"></div>
                    <div class="h-8 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Stats Grid Loaded -->
    <div x-show="!isLoading" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Current Bid -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Current Bid</p>
            <div class="mt-2 flex items-baseline gap-2">
                <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="stats.widgets?.current_bid?.amount"></p>
                <span class="text-sm font-semibold text-emerald-500 flex items-center">
                    <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    <span x-text="stats.widgets?.current_bid?.trend_value"></span>
                </span>
            </div>
        </div>

        <!-- Sponsorships -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Sponsorship</p>
            <div class="mt-2 flex items-baseline gap-2">
                <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="stats.widgets?.sponsorships?.total_amount"></p>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">From <span class="font-medium text-slate-700 dark:text-slate-300" x-text="stats.widgets?.sponsorships?.active_sponsors"></span> active sponsors</p>
        </div>

        <!-- Winning Status -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Winning Status</p>
            <div class="mt-2 flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                <p class="text-2xl font-bold text-slate-900 dark:text-white" x-text="stats.widgets?.winning_status"></p>
            </div>
        </div>

        <!-- Remaining Wins -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Remaining Monthly Wins</p>
            <div class="mt-2">
                <p class="text-3xl font-bold text-slate-900 dark:text-white" x-text="stats.widgets?.remaining_wins + ' / 3'"></p>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 mt-3">
                <div class="bg-primary-500 h-2 rounded-full" :style="'width: ' + ((3 - stats.widgets?.remaining_wins) / 3 * 100) + '%'"></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Bid History Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Bid History</h3>
                <select class="bg-slate-50 dark:bg-slate-800 border-none text-sm font-medium rounded-lg text-slate-700 dark:text-slate-300 focus:ring-0">
                    <option>This Week</option>
                    <option>This Month</option>
                </select>
            </div>
            <div x-show="isLoading" class="h-72 w-full animate-pulse bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
            <div x-show="!isLoading" style="display: none;" class="h-72 w-full">
                <canvas id="bidChart"></canvas>
            </div>
        </div>

        <!-- Sponsorship Chart -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Sponsor Demographics</h3>
            <div x-show="isLoading" class="h-64 w-full animate-pulse bg-slate-100 dark:bg-slate-800 rounded-full max-w-[16rem] mx-auto"></div>
            <div x-show="!isLoading" style="display: none;" class="h-64 w-full relative">
                <canvas id="sponsorChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Activity</h3>
            </div>
            
            <div x-show="isLoading" class="p-6">
                <?= view('components/skeleton', ['type' => 'list', 'count' => 3]) ?>
            </div>
            
            <div x-show="!isLoading" style="display: none;">
                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                    <template x-for="item in stats.recent_activity" :key="item.id">
                        <li class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                                 :class="{
                                     'bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400': item.type === 'bid',
                                     'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400': item.type === 'sponsor',
                                     'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400': item.type === 'win'
                                 }">
                                 <!-- Bid Icon -->
                                <svg x-show="item.type === 'bid'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <!-- Sponsor Icon -->
                                <svg x-show="item.type === 'sponsor'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <!-- Win Icon -->
                                <svg x-show="item.type === 'win'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 dark:text-white truncate" x-text="item.description"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="item.time"></p>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
            
            <div x-show="!isLoading && stats.recent_activity?.length === 0" style="display: none;">
                <?= view('components/empty_state', [
                    'title' => 'No recent activity',
                    'message' => 'You haven\'t made any bids or received sponsorships yet.',
                    'actionText' => 'Place a Bid',
                    'actionUrl' => '#'
                ]) ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Quick Actions</h3>
            
            <div class="space-y-4">
                <button class="w-full flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Place Blind Bid</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Bid for tomorrow's spot</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <button class="w-full flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500 hover:ring-1 hover:ring-emerald-500 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Request Sponsorship</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Share your profile link</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
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
        bidChartInstance: null,
        sponsorChartInstance: null,

        async fetchStats() {
            this.isLoading = true;
            this.error = null;
            
            try {
                // In production, we need to pass the JWT token in headers if using LocalStorage.
                // Since we used HttpOnly cookies, fetch will send it automatically if credentials: 'same-origin'
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
                
                // Initialize charts after Alpine updates the DOM to un-hide the canvas
                this.$nextTick(() => {
                    this.initCharts();
                });

            } catch (err) {
                this.error = err.message;
            } finally {
                this.isLoading = false;
            }
        },

        initCharts() {
            // Colors matching our Tailwind theme
            const primaryColor = '#3b82f6';
            const primaryLight = 'rgba(59, 130, 246, 0.1)';
            
            // Bid History Line Chart
            const ctxBid = document.getElementById('bidChart').getContext('2d');
            if (this.bidChartInstance) this.bidChartInstance.destroy();
            
            this.bidChartInstance = new Chart(ctxBid, {
                type: 'line',
                data: {
                    labels: this.stats.charts.bid_history.labels,
                    datasets: [{
                        label: 'Bid Amount ($)',
                        data: this.stats.charts.bid_history.data,
                        borderColor: primaryColor,
                        backgroundColor: primaryLight,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false }
                        },
                        x: { 
                            grid: { display: false, drawBorder: false }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });

            // Sponsor Doughnut Chart
            const ctxSponsor = document.getElementById('sponsorChart').getContext('2d');
            if (this.sponsorChartInstance) this.sponsorChartInstance.destroy();

            this.sponsorChartInstance = new Chart(ctxSponsor, {
                type: 'doughnut',
                data: {
                    labels: this.stats.charts.sponsorship_distribution.labels,
                    datasets: [{
                        data: this.stats.charts.sponsorship_distribution.data,
                        backgroundColor: [
                            '#3b82f6', // primary-500
                            '#8b5cf6', // violet-500
                            '#ec4899', // pink-500
                            '#cbd5e1'  // slate-300
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
                    }
                }
            });
        }
    }
}
</script>

<?= $this->endSection() ?>
