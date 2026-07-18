<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<div x-data="sponsorProfile()" x-init="initData()">

    <!-- Toast Notification -->
    <div x-show="toast.show" x-transition.opacity style="display: none;" 
         class="fixed bottom-4 right-4 z-50 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700'">
        <span x-text="toast.message"></span>
    </div>

    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Profile</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage your sponsor information.</p>
        </div>
        <!-- Loader Indicator -->
        <div x-show="loading" style="display: none;">
            <svg class="animate-spin h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
    </div>

    <div x-show="initialLoad" class="space-y-6">
        <?= view('components/skeleton', ['type' => 'card', 'count' => 2]) ?>
    </div>

    <!-- Profile Layout -->
    <div x-show="!initialLoad" style="display: none;" class="space-y-8">
        
        <!-- Top Section: General Info (Horizontal Layout) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 sm:p-8 relative group flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
            <button @click="openEditGeneral()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-slate-800 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </button>

            <!-- Avatar -->
            <div class="relative w-28 h-28 sm:w-36 sm:h-36 rounded-full bg-slate-200 dark:bg-slate-800 flex-shrink-0 overflow-hidden border-4 border-white dark:border-slate-900 shadow-lg flex items-center justify-center group/avatar cursor-pointer" @click="$refs.photoInput.click()">
                <template x-if="profile.general?.photo_url">
                    <img :src="profile.general.photo_url" class="w-full h-full object-cover">
                </template>
                <template x-if="!profile.general?.photo_url">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </template>
                
                <!-- Upload Overlay -->
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover/avatar:opacity-100 flex flex-col items-center justify-center transition-opacity">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-[10px] text-white font-medium mt-1">Upload</span>
                </div>
            </div>
            <input type="file" x-ref="photoInput" @change="handlePhotoSelect" class="hidden" accept="image/*">
            
            <!-- Details -->
            <div class="text-center sm:text-left flex-grow">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white" x-text="profile.user?.name"></h2>
                <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Sponsor
                </div>
                <div class="flex items-center justify-center sm:justify-start gap-2 mt-3 text-slate-500 dark:text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="text-sm" x-text="profile.user?.email"></span>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Recent Activities -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Recent Sponsorship Activities
                </h3>
            </div>
            
            <div class="space-y-6">
                <template x-for="cycle in profile.history" :key="cycle.cycle_date">
                    <div class="p-5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-200 dark:border-slate-700/50">
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Cycle: <span x-text="cycle.cycle_date"></span>
                            </h4>
                            <div class="text-right">
                                <span class="block text-sm font-medium text-slate-500 dark:text-slate-400">Total Sponsored</span>
                                <span class="block text-xl font-bold text-slate-900 dark:text-white">$<span x-text="cycle.total_amount.toFixed(2)"></span></span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="sponsorship in cycle.sponsorships" :key="sponsorship.id">
                                <div class="flex justify-between items-center p-3 rounded-lg bg-white dark:bg-slate-900 shadow-sm border border-slate-100 dark:border-slate-800">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-500 to-primary-600 text-white flex items-center justify-center font-bold text-lg shadow-inner" x-text="sponsorship.alumni_name.charAt(0)"></div>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-base" x-text="sponsorship.alumni_name"></p>
                                            <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span x-text="new Date(sponsorship.created_at).toLocaleString()"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        <span class="font-mono font-bold text-primary-600 dark:text-primary-400 text-lg" x-text="'$' + parseFloat(sponsorship.amount).toFixed(2)"></span>
                                        <span class="block text-[10px] font-bold uppercase mt-1 px-2.5 py-0.5 rounded-full" 
                                              :class="{
                                                  'bg-yellow-100 text-yellow-700': sponsorship.bid_status === 'pending',
                                                  'bg-emerald-100 text-emerald-700': sponsorship.bid_status === 'won',
                                                  'bg-red-100 text-red-700': sponsorship.bid_status === 'lost'
                                              }" 
                                              x-text="sponsorship.bid_status"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <template x-if="profile.history?.length === 0">
                    <div class="py-12 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4M16 8l4 4-4 4"></path></svg>
                        <p class="text-slate-500 font-medium">No recent sponsorships yet.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>


    <!-- ================= MODALS ================= -->

    <!-- General Info Edit Modal -->
    <div x-show="modals.general" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="modals.general = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Edit Profile</h3>
                    <button @click="modals.general = false" class="text-slate-400 hover:text-slate-500"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="p-6">
                    <form @submit.prevent="saveGeneral" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
                            <input type="text" x-model="formData.name" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="e.g. Acme Corporation">
                        </div>
                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="modals.general = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Crop Modal -->
    <div x-show="modals.crop" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="modals.crop = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Crop Profile Photo</h3>
                    <button @click="modals.crop = false" class="text-slate-400 hover:text-slate-500"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="p-6">
                    <div class="w-full h-64 sm:h-96 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden flex items-center justify-center">
                        <img id="cropImage" src="" class="max-w-full max-h-full block">
                    </div>
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" @click="modals.crop = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50">Cancel</button>
                        <button type="button" @click="uploadCroppedPhoto" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">Apply & Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function sponsorProfile() {
    return {
        initialLoad: true,
        loading: false,
        profile: {
            user: { name: '', email: '' },
            general: {},
            history: []
        },
        modals: {
            general: false,
            crop: false
        },
        toast: { show: false, message: '', type: 'success' },
        
        cropperInstance: null,
        formData: {
            name: ''
        },

        async initData() {
            try {
                const res = await fetch('<?= base_url('api/sponsor/profile/data') ?>');
                const data = await res.json();
                this.profile = data;
            } catch (err) {
                this.showToast('Failed to load profile data.', 'error');
            } finally {
                this.initialLoad = false;
            }
        },

        showToast(msg, type = 'success') {
            this.toast = { show: true, message: msg, type: type };
            setTimeout(() => this.toast.show = false, 3000);
        },

        openEditGeneral() {
            this.formData.name = this.profile.user.name;
            this.modals.general = true;
        },

        async saveGeneral() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/sponsor/profile/general') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: this.formData.name })
                });
                if (res.ok) {
                    this.profile.user.name = this.formData.name;
                    this.modals.general = false;
                    this.showToast('Profile updated successfully');
                    
                    // Trigger custom event so topbar Alpine component can update if needed
                    window.dispatchEvent(new CustomEvent('profile-updated'));
                } else {
                    this.showToast('Failed to update profile.', 'error');
                }
            } catch (err) {
                this.showToast('Network error.', 'error');
            } finally {
                this.loading = false;
            }
        },

        handlePhotoSelect(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const image = document.getElementById('cropImage');
                image.src = e.target.result;
                
                this.modals.crop = true;
                
                if (this.cropperInstance) this.cropperInstance.destroy();
                
                setTimeout(() => {
                    this.cropperInstance = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                }, 100);
            };
            reader.readAsDataURL(file);
            e.target.value = ''; // reset
        },

        uploadCroppedPhoto() {
            if (!this.cropperInstance) return;
            
            this.loading = true;
            this.cropperInstance.getCroppedCanvas({ width: 500, height: 500 }).toBlob(async (blob) => {
                const formData = new FormData();
                formData.append('photo', blob, 'profile.jpg');
                
                try {
                    const res = await fetch('<?= base_url('api/sponsor/profile/upload-photo') ?>', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await res.json();
                    if (res.ok && data.status === 'success') {
                        this.profile.general.photo_url = data.photo_url;
                        this.modals.crop = false;
                        this.showToast('Photo uploaded successfully');
                        
                        // Force image reload by adding timestamp
                        const img = new Image();
                        img.src = data.photo_url + '?t=' + new Date().getTime();
                        
                        // Trigger custom event so topbar Alpine component can update
                        window.dispatchEvent(new CustomEvent('profile-updated'));
                    } else {
                        this.showToast(data.message || 'Upload failed.', 'error');
                    }
                } catch (err) {
                    this.showToast('Network error.', 'error');
                } finally {
                    this.loading = false;
                }
            }, 'image/jpeg', 0.8);
        }
    }
}
</script>

<?= $this->endSection() ?>
