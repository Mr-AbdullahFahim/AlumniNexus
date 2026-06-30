<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div x-data="alumniDirectory()" x-init="initData()">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Alumni Directory</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Connect with alumni around the world.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-6 mb-8 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Search</label>
                <div class="relative">
                    <input type="text" x-model="filters.search" @input.debounce.500ms="fetchAlumni(true)" placeholder="Search by name, company, or role..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Department -->
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Department</label>
                <select x-model="filters.department" @change="fetchAlumni(true)" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    <option value="">All Departments</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Business Administration">Business Administration</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="Law">Law</option>
                    <option value="Medicine">Medicine</option>
                </select>
            </div>

            <!-- Industry -->
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Industry</label>
                <select x-model="filters.industry" @change="fetchAlumni(true)" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    <option value="">All Industries</option>
                    <option value="Technology">Technology</option>
                    <option value="Finance">Finance</option>
                    <option value="Manufacturing">Manufacturing</option>
                    <option value="Legal">Legal</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Education">Education</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Sort By</label>
                <select x-model="filters.sort_by" @change="fetchAlumni(true)" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                    <option value="newest">Newest First</option>
                    <option value="a-z">Name (A-Z)</option>
                    <option value="z-a">Name (Z-A)</option>
                </select>
            </div>
            
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" style="display: none;">
        <template x-for="i in 8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm animate-pulse">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                    <div>
                        <div class="h-4 w-24 bg-slate-200 dark:bg-slate-700 rounded mb-2"></div>
                        <div class="h-3 w-16 bg-slate-200 dark:bg-slate-700 rounded"></div>
                    </div>
                </div>
                <div class="space-y-2 mt-6">
                    <div class="h-3 w-full bg-slate-200 dark:bg-slate-700 rounded"></div>
                    <div class="h-3 w-4/5 bg-slate-200 dark:bg-slate-700 rounded"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && alumni.length === 0" class="text-center py-20 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm" style="display: none;">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">No alumni found</h3>
        <p class="text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">We couldn't find anyone matching your current filters. Try adjusting your search criteria.</p>
        <button @click="resetFilters" class="mt-6 px-4 py-2 bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 text-sm font-medium rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors">
            Clear Filters
        </button>
    </div>

    <!-- Alumni Grid -->
    <div x-show="!loading && alumni.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="person in alumni" :key="person.id">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
                <div class="p-6 flex-grow flex flex-col items-center text-center">
                    <div class="relative mb-4">
                        <img :src="person.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(person.name)}&background=random`" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-slate-900 shadow-sm">
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="person.name"></h3>
                    <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mt-1" x-text="person.position || 'Alumni Member'"></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-1" x-text="person.company || 'Open to opportunities'"></p>
                    
                    <div class="mt-6 w-full space-y-3">
                        <template x-if="person.department">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Department</span>
                                <span class="font-medium text-slate-900 dark:text-slate-200" x-text="person.department"></span>
                            </div>
                        </template>
                        <template x-if="person.graduation_year">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Class of</span>
                                <span class="font-medium text-slate-900 dark:text-slate-200" x-text="person.graduation_year"></span>
                            </div>
                        </template>
                        <template x-if="person.industry">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Industry</span>
                                <span class="font-medium text-slate-900 dark:text-slate-200" x-text="person.industry"></span>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                    <a :href="`<?= base_url('alumni/profile/') ?>${person.id}`" 
                       class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors">
                        View Profile
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div x-show="!loading && pagination.total_pages > 1" class="mt-8 flex items-center justify-between border-t border-slate-200 dark:border-slate-800 pt-6">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Showing <span class="font-medium text-slate-900 dark:text-white" x-text="((pagination.current_page - 1) * pagination.per_page) + 1"></span> to <span class="font-medium text-slate-900 dark:text-white" x-text="Math.min(pagination.current_page * pagination.per_page, pagination.total_rows)"></span> of <span class="font-medium text-slate-900 dark:text-white" x-text="pagination.total_rows"></span> results
        </p>
        <div class="flex items-center gap-2">
            <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="p-2 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.total_pages" class="p-2 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

</div>

<script>
function alumniDirectory() {
    return {
        alumni: [],
        loading: true,
        filters: {
            search: '',
            department: '',
            industry: '',
            sort_by: 'newest',
            page: 1
        },
        pagination: {
            current_page: 1,
            total_pages: 1,
            per_page: 12,
            total_rows: 0
        },

        initData() {
            // Load state from URL parameters
            const params = new URLSearchParams(window.location.search);
            if (params.has('search')) this.filters.search = params.get('search');
            if (params.has('department')) this.filters.department = params.get('department');
            if (params.has('industry')) this.filters.industry = params.get('industry');
            if (params.has('sort_by')) this.filters.sort_by = params.get('sort_by');
            if (params.has('page')) this.filters.page = parseInt(params.get('page'));

            this.fetchAlumni();
        },

        async fetchAlumni(resetPage = false) {
            if (resetPage) this.filters.page = 1;
            this.loading = true;

            // Update URL
            const params = new URLSearchParams();
            if (this.filters.search) params.set('search', this.filters.search);
            if (this.filters.department) params.set('department', this.filters.department);
            if (this.filters.industry) params.set('industry', this.filters.industry);
            if (this.filters.sort_by !== 'newest') params.set('sort_by', this.filters.sort_by);
            if (this.filters.page > 1) params.set('page', this.filters.page);
            
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.replaceState({}, '', newUrl);

            try {
                const res = await fetch(`<?= base_url('api/directory') ?>?${params.toString()}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    this.alumni = data.data;
                    this.pagination = data.pagination;
                }
            } catch (error) {
                console.error("Failed to fetch alumni directory:", error);
            } finally {
                this.loading = false;
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.total_pages) {
                this.filters.page = page;
                this.fetchAlumni();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        resetFilters() {
            this.filters = { search: '', department: '', industry: '', sort_by: 'newest', page: 1 };
            this.fetchAlumni(true);
        }
    }
}
</script>

<?= $this->endSection() ?>
