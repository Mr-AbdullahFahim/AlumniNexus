<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="space-y-6" x-data="activityLogs()">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Activity Logs</h1>
            <p class="text-sm text-slate-500 mt-1">Track and monitor actions performed by users across the platform.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button @click="fetchActivities()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary-500/40 transition-all font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-slate-700 mb-1.5">Search User or Action</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="search" x-model.debounce.500ms="filters.search" @input="fetchActivities()" class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-sm" placeholder="Search...">
                </div>
            </div>
            
            <div class="w-full md:w-64">
                <label for="action" class="block text-sm font-medium text-slate-700 mb-1.5">Action Filter</label>
                <input type="text" id="action" x-model.debounce.500ms="filters.action" @input="fetchActivities()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-sm" placeholder="e.g. User Login">
            </div>
        </div>
    </div>

    <!-- Activities Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
        <!-- Loading Overlay -->
        <div x-show="loading" class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10">
            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date/Time</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">IP Address</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <template x-for="activity in activities" :key="activity.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span x-text="new Date(activity.created_at).toLocaleString()"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-slate-900" x-text="activity.user_name || 'System / Guest'"></div>
                                        <div class="text-xs text-slate-500" x-text="activity.user_email || ''"></div>
                                        <div class="text-xs text-slate-400 font-semibold" x-text="activity.role_name || ''"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800" x-text="activity.action"></span>
                                <div class="text-xs text-slate-400 mt-1" x-show="activity.table_name">
                                    Table: <span x-text="activity.table_name"></span> <span x-show="activity.record_id"> (ID: <span x-text="activity.record_id"></span>)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span x-text="activity.ip_address === '::1' ? '127.0.0.1' : (activity.ip_address || 'N/A')"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="viewDetails(activity)" x-show="activity.old_values || activity.new_values" class="text-primary-600 hover:text-primary-900 bg-primary-50 px-3 py-1 rounded-md transition-colors">
                                    View Data
                                </button>
                                <span x-show="!activity.old_values && !activity.new_values" class="text-slate-400 text-xs italic">No Data</span>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Empty State -->
                    <tr x-show="!loading && activities.length === 0" x-cloak>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p>No activities found matching your criteria.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between" x-show="pagination.total_pages > 1" x-cloak>
            <div class="text-sm text-slate-500">
                Showing <span class="font-medium text-slate-900" x-text="(pagination.current_page - 1) * pagination.per_page + 1"></span> to 
                <span class="font-medium text-slate-900" x-text="Math.min(pagination.current_page * pagination.per_page, pagination.total_rows)"></span> of 
                <span class="font-medium text-slate-900" x-text="pagination.total_rows"></span> logs
            </div>
            <div class="flex gap-2">
                <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Previous
                </button>
                <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.total_pages" class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- Data Details Modal -->
    <div x-show="detailsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="detailsModalOpen = false" x-transition.opacity></div>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative z-10 max-h-[90vh] flex flex-col" x-transition.scale.95>
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Activity Data Snapshot</h3>
                <button @click="detailsModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto bg-slate-50 flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Old Values -->
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Previous State</h4>
                    <template x-if="selectedActivity && selectedActivity.old_values">
                        <pre class="text-xs bg-slate-900 text-slate-50 p-4 rounded-lg overflow-x-auto whitespace-pre-wrap" x-text="JSON.stringify(selectedActivity.old_values, null, 2)"></pre>
                    </template>
                    <template x-if="!selectedActivity || !selectedActivity.old_values">
                        <div class="text-sm text-slate-400 italic">No previous state recorded.</div>
                    </template>
                </div>

                <!-- New Values -->
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">New State</h4>
                    <template x-if="selectedActivity && selectedActivity.new_values">
                        <pre class="text-xs bg-slate-900 text-slate-50 p-4 rounded-lg overflow-x-auto whitespace-pre-wrap" x-text="JSON.stringify(selectedActivity.new_values, null, 2)"></pre>
                    </template>
                    <template x-if="!selectedActivity || !selectedActivity.new_values">
                        <div class="text-sm text-slate-400 italic">No new state recorded.</div>
                    </template>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex justify-end">
                <button @click="detailsModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors font-medium text-sm">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    const setupActivityLogs = () => {
        if (window.Alpine.data.hasOwnProperty('activityLogs')) return;
        
        Alpine.data('activityLogs', () => ({
        activities: [],
        loading: true,
        detailsModalOpen: false,
        selectedActivity: null,
        filters: {
            search: '',
            action: ''
        },
        pagination: {
            current_page: 1,
            per_page: 20,
            total_rows: 0,
            total_pages: 1
        },
        
        init() {
            this.fetchActivities();
        },
        
        fetchActivities() {
            this.loading = true;
            
            const params = new URLSearchParams({
                search: this.filters.search,
                action: this.filters.action,
                page: this.pagination.current_page,
                limit: this.pagination.per_page
            });
            
            const url = `<?= rtrim(base_url(), '/') ?>/api/admin/activities/list?${params.toString()}`;
            fetch(url, {
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.activities = data.data;
                    this.pagination = data.pagination;
                }
            })
            .finally(() => {
                this.loading = false;
            });
        },
        
        changePage(page) {
            if (page >= 1 && page <= this.pagination.total_pages) {
                this.pagination.current_page = page;
                this.fetchActivities();
            }
        },

        viewDetails(activity) {
            this.selectedActivity = activity;
            this.detailsModalOpen = true;
        }
    }));
    };

    if (window.Alpine) {
        setupActivityLogs();
    } else {
        document.addEventListener('alpine:init', setupActivityLogs);
    }
</script>
<?= $this->endSection() ?>
