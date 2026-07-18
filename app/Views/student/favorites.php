<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto" x-data="favoriteDirectory()" x-init="fetchFavorites()">

    <?= view_cell('\App\Cells\FeaturedAlumniCell::render') ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Favorite Profiles</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Keep track of alumni profiles you are following.</p>
        </div>
    </div>

    <!-- Loader -->
    <div x-show="loading" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="i in 8" :key="i">
                <?= view('components/skeleton', ['type' => 'card', 'count' => 1]) ?>
            </template>
        </div>
    </div>

    <div x-show="!loading" style="display: none;">
        <template x-if="favorites.length === 0">
            <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No favorites yet</h3>
                <p class="mt-1 text-slate-500">Go to the Network tab to find alumni to follow.</p>
                <a href="<?= base_url('directory') ?>" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">
                    Browse Network
                </a>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="alumni in favorites" :key="alumni.id">
                <a :href="`<?= base_url('alumni/profile/') ?>${alumni.id}`" class="block group h-full">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 h-full flex flex-col hover:border-primary-500 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between">
                            <div class="relative w-16 h-16 flex-shrink-0">
                                <div class="w-full h-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <template x-if="alumni.photo_url">
                                        <img :src="alumni.photo_url" :alt="alumni.name" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!alumni.photo_url">
                                        <div class="w-full h-full flex items-center justify-center bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-bold text-xl uppercase" x-text="alumni.name.charAt(0)"></div>
                                    </template>
                                </div>
                                <!-- Star icon badge -->
                                <div class="absolute bottom-0 right-0 bg-yellow-400 rounded-full p-0.5 border-2 border-white dark:border-slate-900 shadow-sm z-10">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex-grow">
                            <h3 class="font-bold text-lg text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-1" x-text="alumni.name"></h3>
                            
                            <template x-if="alumni.position || alumni.company">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mt-1 line-clamp-2">
                                    <span x-text="alumni.position"></span>
                                    <template x-if="alumni.position && alumni.company"><span> at </span></template>
                                    <span x-text="alumni.company"></span>
                                </p>
                            </template>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/60 flex flex-col gap-2">
                            <template x-if="alumni.industry">
                                <div class="flex items-center text-xs text-slate-500">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span class="truncate" x-text="alumni.industry"></span>
                                </div>
                            </template>
                            <template x-if="alumni.department || alumni.graduation_year">
                                <div class="flex items-center text-xs text-slate-500">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                    <span class="truncate">
                                        <template x-if="alumni.department"><span x-text="alumni.department"></span></template>
                                        <template x-if="alumni.department && alumni.graduation_year"><span> • </span></template>
                                        <template x-if="alumni.graduation_year"><span x-text="'Class of ' + alumni.graduation_year"></span></template>
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
</div>

<script>
function favoriteDirectory() {
    return {
        favorites: [],
        loading: true,

        async fetchFavorites() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/student/favorite/list') ?>');
                const data = await res.json();
                if(data.status === 'success') {
                    this.favorites = data.data;
                }
            } catch (err) {
                console.error(err);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<?= $this->endSection() ?>
