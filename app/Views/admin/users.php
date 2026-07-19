<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="userManagement()">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">User Management</h1>
            <p class="text-sm text-slate-500 mt-1">View and manage all users across the platform.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="search" class="block text-sm font-medium text-slate-700 mb-1.5">Search Users</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search" x-model="filters.search" @input.debounce.500ms="fetchUsers()" placeholder="Search by name or email..." class="pl-10 w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-sm">
                </div>
            </div>
            
            <div class="w-full md:w-48">
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                <select id="role" x-model="filters.role" @change="fetchUsers()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-sm">
                    <option value="">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="Alumni">Alumni</option>
                    <option value="Student">Student</option>
                    <option value="Sponsor">Sponsor</option>
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                <select id="status" x-model="filters.status" @change="fetchUsers()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="suspended">Suspended</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User Info</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Joined Date</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <template x-for="user in users" :key="user.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-lg" x-text="user.name.charAt(0)"></div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900" x-text="user.name"></div>
                                        <div class="text-sm text-slate-500" x-text="user.email"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800" x-text="user.role_name"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="{
                                          'bg-green-100 text-green-800': user.status === 'approved',
                                          'bg-amber-100 text-amber-800': user.status === 'pending',
                                          'bg-red-100 text-red-800': user.status === 'suspended' || user.status === 'rejected'
                                      }"
                                      x-text="user.status.charAt(0).toUpperCase() + user.status.slice(1)">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span x-text="new Date(user.created_at).toLocaleDateString()"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2" x-show="user.id != <?= session()->get('user_id') ?? 0 ?>">
                                    <!-- Status Actions -->
                                    <button @click="updateStatus(user.id, 'approved')" x-show="user.status !== 'approved'" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Approve
                                    </button>
                                    <button @click="updateStatus(user.id, 'suspended')" x-show="user.status === 'approved'" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-amber-700 bg-amber-100 hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                        Suspend
                                    </button>
                                    <button @click="updateStatus(user.id, 'rejected')" x-show="user.status === 'pending'" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Reject
                                    </button>

                                    <!-- Role Update -->
                                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                        <button @click="open = !open" class="inline-flex items-center px-2.5 py-1.5 border border-slate-300 text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            Role
                                            <svg class="ml-1 h-3 w-3 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition.opacity.duration.200ms class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20" x-cloak>
                                            <div class="py-1">
                                                <button @click="updateRole(user.id, 1, 'Admin'); open = false" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" :class="{ 'bg-slate-50 font-bold': user.role_name === 'Admin' }">Admin</button>
                                                <button @click="updateRole(user.id, 2, 'Alumni'); open = false" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" :class="{ 'bg-slate-50 font-bold': user.role_name === 'Alumni' }">Alumni</button>
                                                <button @click="updateRole(user.id, 3, 'Student'); open = false" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" :class="{ 'bg-slate-50 font-bold': user.role_name === 'Student' }">Student</button>
                                                <button @click="updateRole(user.id, 4, 'Sponsor'); open = false" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" :class="{ 'bg-slate-50 font-bold': user.role_name === 'Sponsor' }">Sponsor</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="user.id == <?= session()->get('user_id') ?? 0 ?>" class="text-slate-400 text-xs italic">Current User</div>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Empty State -->
                    <tr x-show="!loading && users.length === 0" x-cloak>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p>No users found matching your criteria.</p>
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
                <span class="font-medium text-slate-900" x-text="pagination.total_rows"></span> users
            </div>
            <div class="flex gap-2">
                <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" 
                        class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm font-medium bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Previous
                </button>
                <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.total_pages" 
                        class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm font-medium bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const setupUserManagement = () => {
        if (window.Alpine.data.hasOwnProperty('userManagement')) return;
        
        Alpine.data('userManagement', () => ({
        users: [],
        loading: true,
        filters: {
            search: '',
            role: '',
            status: ''
        },
        pagination: {
            current_page: 1,
            per_page: 10,
            total_rows: 0,
            total_pages: 1
        },
        
        init() {
            this.fetchUsers();
        },
        
        fetchUsers() {
            this.loading = true;
            
            const params = new URLSearchParams({
                search: this.filters.search,
                role: this.filters.role,
                status: this.filters.status,
                page: this.pagination.current_page,
                limit: this.pagination.per_page
            });
            
            const url = `<?= rtrim(base_url(), '/') ?>/api/admin/users/list?${params.toString()}`;
            fetch(url, {
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.users = data.data;
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
                this.fetchUsers();
            }
        },

        updateStatus(userId, newStatus) {
            if(!confirm(`Are you sure you want to change this user's status to ${newStatus}?`)) return;

            fetch(`<?= rtrim(base_url(), '/') ?>/api/admin/users/status`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update locally
                    const user = this.users.find(u => u.id === userId);
                    if(user) user.status = newStatus;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: data.message } }));
                } else {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: data.message || 'Failed to update status' } }));
                }
            })
            .catch(err => {
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Network error occurred' } }));
            });
        },

        updateRole(userId, newRoleId, newRoleName) {
            if(!confirm(`Are you sure you want to change this user's role to ${newRoleName}?`)) return;

            fetch(`<?= rtrim(base_url(), '/') ?>/api/admin/users/role`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    role_id: newRoleId
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update locally
                    const user = this.users.find(u => u.id === userId);
                    if(user) user.role_name = newRoleName;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: data.message } }));
                } else {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: data.message || 'Failed to update role' } }));
                }
            })
            .catch(err => {
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Network error occurred' } }));
            });
        },
        
        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        }
    }));
    };

    if (window.Alpine) {
        setupUserManagement();
    } else {
        document.addEventListener('alpine:init', setupUserManagement);
    }
</script>
<?= $this->endSection() ?>
