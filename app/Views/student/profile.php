<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<div x-data="studentProfile()" x-init="initData()">

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
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage your public information and professional history.</p>
        </div>
        <!-- Loader Indicator -->
        <div x-show="loading" style="display: none;">
            <svg class="animate-spin h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
    </div>

    <div x-show="initialLoad" class="space-y-6">
        <?= view('components/skeleton', ['type' => 'card', 'count' => 3]) ?>
    </div>

    <!-- Profile Grid -->
    <div x-show="!initialLoad" style="display: none;" class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Column: General Info -->
        <div class="xl:col-span-1 space-y-6">
            
            <!-- Basic Details Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 relative group">
                <button @click="openEditGeneral('basic')" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-slate-800 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </button>

                <div class="flex flex-col items-center text-center">
                    <div class="relative w-24 h-24 rounded-full bg-slate-200 dark:bg-slate-800 mb-4 overflow-hidden border-4 border-white dark:border-slate-900 shadow-md flex items-center justify-center group/avatar cursor-pointer" @click="$refs.photoInput.click()">
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
                    
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white" x-text="currentUser.name"></h2>
                    <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mt-1">
                        <span x-text="profile.general?.position || 'Add Position'"></span> @ <span x-text="profile.general?.company || 'Add Company'"></span>
                    </p>

                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-6 leading-relaxed" x-text="profile.general?.bio || 'Add a short bio to introduce yourself.'"></p>
                </div>
            </div>

            <!-- Academic & Professional Details Card -->
            <template x-if="profile.general?.department || profile.general?.graduation_year || profile.general?.industry">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 relative group">
                    <button @click="openEditGeneral('basic')" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-slate-800 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Details</h3>
                    <div class="w-full space-y-3">
                        <template x-if="profile.general?.department">
                            <div class="flex items-center justify-between text-sm pb-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Department</span>
                                <span class="font-medium text-slate-900 dark:text-slate-200" x-text="profile.general.department"></span>
                            </div>
                        </template>
                        <template x-if="profile.general?.graduation_year">
                            <div class="flex items-center justify-between text-sm pb-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Class of</span>
                                <span class="font-medium text-slate-900 dark:text-slate-200" x-text="profile.general.graduation_year"></span>
                            </div>
                        </template>
                        <template x-if="profile.general?.industry">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Industry</span>
                                <span class="font-medium text-slate-900 dark:text-slate-200" x-text="profile.general.industry"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Skills Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Top Skills</h3>
                    <button @click="openEditGeneral('skills')" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Edit</button>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <template x-if="profile.general?.skills?.length === 0 || !profile.general?.skills">
                        <p class="text-sm text-slate-500 italic">No skills added.</p>
                    </template>
                    <template x-for="skill in profile.general?.skills" :key="skill">
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-full text-xs font-medium" x-text="skill"></span>
                    </template>
                </div>
            </div>

            <!-- Social Links -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                 <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Social Links</h3>
                    <button @click="openEditGeneral('socials')" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Edit</button>
                </div>
                <div class="space-y-3">
                    <template x-for="(url, platform) in profile.general?.social_links">
                        <a :href="url" target="_blank" class="flex items-center gap-3 text-sm text-slate-600 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            <span class="capitalize" x-text="platform"></span>
                        </a>
                    </template>
                </div>
            </div>
            
        </div>

        <!-- Right Column: Lists -->
        <div class="xl:col-span-2 space-y-6">
            
            <!-- Employment History -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Employment History</h3>
                    <button @click="openModal('employment')" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-sm font-medium rounded-lg hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">
                        Add New
                    </button>
                </div>
                
                <div class="space-y-6">
                    <template x-for="job in profile.employment" :key="job.id">
                        <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-700 group">
                            <div class="absolute w-3 h-3 bg-slate-200 dark:bg-slate-700 rounded-full -left-[7px] top-1.5 ring-4 ring-white dark:ring-slate-900"></div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-base font-semibold text-slate-900 dark:text-white" x-text="job.position"></h4>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mt-0.5" x-text="job.company_name"></p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        <span x-text="formatDate(job.start_date)"></span> - <span x-text="job.is_current ? 'Present' : formatDate(job.end_date)"></span>
                                    </p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-3" x-text="job.description"></p>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 flex gap-2 transition-opacity">
                                    <button @click="openModal('employment', job)" class="text-slate-400 hover:text-primary-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    <button @click="deleteRecord('employment', job.id)" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="profile.employment?.length === 0">
                        <p class="text-sm text-slate-500 italic">No employment history added.</p>
                    </template>
                </div>
            </div>

            <!-- Degrees -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Education</h3>
                    <button @click="openModal('degree')" class="text-sm font-medium text-primary-600 hover:text-primary-700">Add Degree</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="degree in profile.degrees" :key="degree.id">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 group relative">
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 flex gap-2 transition-opacity">
                                <button @click="openModal('degree', degree)" class="text-slate-400 hover:text-primary-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button @click="deleteRecord('degree', degree.id)" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white" x-text="degree.degree_name"></h4>
                            <p class="text-sm text-slate-700 dark:text-slate-300 mt-1" x-text="degree.institution"></p>
                            <p class="text-xs text-slate-500 mt-2"><span x-text="formatDate(degree.start_date)"></span> - <span x-text="formatDate(degree.end_date)"></span></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Certifications -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Certifications & Licences</h3>
                    <button @click="openModal('certification')" class="text-sm font-medium text-primary-600 hover:text-primary-700">Add Certificate</button>
                </div>
                <div class="space-y-3">
                    <template x-for="cert in profile.certifications" :key="cert.id">
                        <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white" x-text="cert.name"></h4>
                                    <p class="text-xs text-slate-500 mt-0.5" x-text="cert.issuing_organization"></p>
                                </div>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 flex gap-2">
                                <button @click="deleteRecord('certification', cert.id)" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Projects -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Projects</h3>
                    <button @click="openModal('project')" class="text-sm font-medium text-primary-600 hover:text-primary-700">Add Project</button>
                </div>
                <div class="space-y-4">
                    <template x-for="project in profile.projects" :key="project.id">
                        <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 group relative">
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 flex gap-2 transition-opacity">
                                <button @click="openModal('project', project)" class="text-slate-400 hover:text-primary-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button @click="deleteRecord('project', project.id)" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white" x-text="project.title"></h4>
                            <p class="text-sm text-slate-700 dark:text-slate-300 mt-2" x-text="project.description"></p>
                            <template x-if="project.link">
                                <a :href="project.link" target="_blank" class="text-xs text-primary-600 hover:text-primary-700 mt-2 inline-block">View Project &rarr;</a>
                            </template>
                        </div>
                    </template>
                    <template x-if="profile.projects?.length === 0 || !profile.projects">
                        <p class="text-sm text-slate-500 italic">No projects added.</p>
                    </template>
                </div>
            </div>

            <!-- Achievements -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Achievements</h3>
                    <button @click="openModal('achievement')" class="text-sm font-medium text-primary-600 hover:text-primary-700">Add Achievement</button>
                </div>
                <div class="space-y-4">
                    <template x-for="achievement in profile.achievements" :key="achievement.id">
                        <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 group relative">
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 flex gap-2 transition-opacity">
                                <button @click="openModal('achievement', achievement)" class="text-slate-400 hover:text-primary-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button @click="deleteRecord('achievement', achievement.id)" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                </div>
                                <div class="flex-grow">
                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white" x-text="achievement.title"></h4>
                                    <p class="text-xs text-slate-500 mt-1" x-text="achievement.date_earned ? formatDate(achievement.date_earned) : ''"></p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-2" x-text="achievement.description"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="profile.achievements?.length === 0 || !profile.achievements">
                        <p class="text-sm text-slate-500 italic">No achievements added.</p>
                    </template>
                </div>
            </div>
            
        </div>
    </div>


    <!-- ================= MODALS ================= -->

    <!-- General Info Edit Modal -->
    <div x-show="modals.general" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="modals.general = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Edit Profile</h3>
                    <button @click="modals.general = false" class="text-slate-400 hover:text-slate-500"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="p-6">
                    <form @submit.prevent="saveGeneral" class="space-y-4">
                        
                        <template x-if="activeGeneralTab === 'basic'">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Company</label>
                                        <input type="text" x-model="formData.general.company" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="e.g. Google">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Position</label>
                                        <input type="text" x-model="formData.general.position" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="e.g. Software Engineer">
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Industry</label>
                                        <input type="text" x-model="formData.general.industry" placeholder="e.g. Technology" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Department</label>
                                        <input type="text" x-model="formData.general.department" placeholder="e.g. Computer Science" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Graduation Year</label>
                                        <input type="number" x-model="formData.general.graduation_year" placeholder="e.g. 2024" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bio</label>
                                    <textarea x-model="formData.general.bio" rows="4" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="Tell us a little about yourself..."></textarea>
                                </div>
                            </div>
                        </template>

                        <template x-if="activeGeneralTab === 'skills'">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Skills (comma separated)</label>
                                <input type="text" x-model="formData.skillsInput" placeholder="e.g. PHP, Laravel, Tailwind CSS" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                            </div>
                        </template>

                        <template x-if="activeGeneralTab === 'socials'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">LinkedIn URL</label>
                                    <input type="url" x-model="formData.socials.linkedin" placeholder="https://linkedin.com/in/username" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">GitHub URL</label>
                                    <input type="url" x-model="formData.socials.github" placeholder="https://github.com/username" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Twitter / X URL</label>
                                    <input type="url" x-model="formData.socials.twitter" placeholder="https://twitter.com/username" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Personal Website</label>
                                    <input type="url" x-model="formData.socials.website" placeholder="https://yourdomain.com" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>
                        </template>

                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="modals.general = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Generic Relation Modal -->
    <div x-show="modals.relation" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="modals.relation = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="activeModalTitle"></h3>
                    <button @click="modals.relation = false" class="text-slate-400 hover:text-slate-500"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="p-6">
                    <form @submit.prevent="saveRelation" class="space-y-4">
                        
                        <!-- Employment Fields -->
                        <template x-if="activeRelationType === 'employment'">
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Company</label><input type="text" x-model="formData.relation.company_name" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. Microsoft"></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Position</label><input type="text" x-model="formData.relation.position" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. Product Manager"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Start Date</label><input type="date" x-model="formData.relation.start_date" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="YYYY-MM-DD"></div>
                                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">End Date</label><input type="date" x-model="formData.relation.end_date" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white disabled:opacity-50 disabled:bg-slate-200 dark:disabled:bg-slate-900 transition-colors" :disabled="!!formData.relation.is_current" placeholder="YYYY-MM-DD"></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="is_current" x-model="formData.relation.is_current" class="rounded text-primary-600 focus:ring-primary-500">
                                    <label for="is_current" class="text-sm text-slate-600 dark:text-slate-400">I currently work here</label>
                                </div>
                            </div>
                        </template>

                        <!-- Degree Fields -->
                        <template x-if="activeRelationType === 'degree'">
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Institution</label><input type="text" x-model="formData.relation.institution" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. Stanford University"></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Degree Name (e.g. B.Sc in Computer Science)</label><input type="text" x-model="formData.relation.degree_name" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. B.Sc in Computer Science"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Start Date</label><input type="date" x-model="formData.relation.start_date" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="YYYY-MM-DD"></div>
                                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">End Date</label><input type="date" x-model="formData.relation.end_date" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="YYYY-MM-DD"></div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Certification Fields -->
                        <template x-if="activeRelationType === 'certification'">
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Certificate Name</label><input type="text" x-model="formData.relation.name" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. AWS Certified Solutions Architect"></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Issuing Organization</label><input type="text" x-model="formData.relation.issuing_organization" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. Amazon Web Services"></div>
                            </div>
                        </template>

                        <!-- Project Fields -->
                        <template x-if="activeRelationType === 'project'">
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project Title</label><input type="text" x-model="formData.relation.title" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. E-Commerce Website"></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label><textarea x-model="formData.relation.description" required rows="3" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="Provide a brief description..."></textarea></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project URL (Optional)</label><input type="url" x-model="formData.relation.link" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="https://example.com/project"></div>
                            </div>
                        </template>

                        <!-- Achievement Fields -->
                        <template x-if="activeRelationType === 'achievement'">
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Achievement Title</label><input type="text" x-model="formData.relation.title" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="e.g. E-Commerce Website"></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Date Earned (Optional)</label><input type="date" x-model="formData.relation.date_earned" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="YYYY-MM-DD"></div>
                                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description (Optional)</label><textarea x-model="formData.relation.description" rows="3" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white" placeholder="Provide a brief description..."></textarea></div>
                            </div>
                        </template>

                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="modals.relation = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">Save</button>
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
const currentUser = <?= json_encode($user ?? ['name' => 'Alumni']) ?>;

function studentProfile() {
    return {
        initialLoad: true,
        loading: false,
        currentUser: currentUser,
        profile: {
            general: {},
            degrees: [],
            employment: [],
            certifications: [],
            projects: [],
            achievements: []
        },
        modals: {
            general: false,
            relation: false,
            crop: false
        },
        toast: { show: false, message: '', type: 'success' },
        
        cropperInstance: null,
        
        activeRelationType: '', // 'employment', 'degree', 'certification'
        activeModalTitle: '',
        activeGeneralTab: 'basic',
        
        formData: {
            general: {},
            skillsInput: '',
            socials: {},
            relation: {}
        },

        async initData() {
            try {
                const res = await fetch('<?= base_url('api/student/profile/data') ?>');
                if (!res.ok) throw new Error('Failed to load profile');
                const data = await res.json();
                
                // Fix boolean fields returning as string "0" or "1" from MySQL
                if (data.employment) {
                    data.employment = data.employment.map(job => ({
                        ...job,
                        is_current: job.is_current == '1' || job.is_current === true || job.is_current === 1
                    }));
                }

                this.profile = data;
            } catch (e) {
                this.showToast(e.message, 'error');
            } finally {
                this.initialLoad = false;
            }
        },

        openEditGeneral(tab = 'basic') {
            this.activeGeneralTab = tab;
            this.formData.general = { ...this.profile.general };
            this.formData.skillsInput = (this.profile.general.skills || []).join(', ');
            this.formData.socials = { ...this.profile.general.social_links };
            this.modals.general = true;
        },

        handlePhotoSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById('cropImage');
                img.src = e.target.result;
                this.modals.crop = true;

                if (this.cropperInstance) {
                    this.cropperInstance.destroy();
                }

                this.$nextTick(() => {
                    this.cropperInstance = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                });
            };
            reader.readAsDataURL(file);
            event.target.value = ''; // Reset input
        },

        async uploadCroppedPhoto() {
            if (!this.cropperInstance) return;

            this.modals.crop = false;
            this.loading = true;

            this.cropperInstance.getCroppedCanvas({
                width: 500,
                height: 500
            }).toBlob(async (blob) => {
                const formData = new FormData();
                formData.append('photo', blob, 'profile.jpg');

                try {
                    const res = await fetch('<?= base_url('api/student/profile/upload-photo') ?>', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Upload failed');
                    
                    this.profile.general.photo_url = data.photo_url;
                    this.showToast('Profile photo updated successfully!');
                } catch (e) {
                    this.showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                    if (this.cropperInstance) {
                        this.cropperInstance.destroy();
                        this.cropperInstance = null;
                    }
                }
            }, 'image/jpeg', 0.9);
        },

        async saveGeneral() {
            this.loading = true;
            const skillsArr = this.formData.skillsInput.split(',').map(s => s.trim()).filter(s => s);
            // Clean up socials object to remove empty strings
            const cleanSocials = {};
            Object.keys(this.formData.socials).forEach(k => {
                if(this.formData.socials[k]) cleanSocials[k] = this.formData.socials[k];
            });

            const payload = { 
                ...this.formData.general, 
                skills: skillsArr,
                social_links: cleanSocials
            };

            try {
                const res = await fetch('<?= base_url('api/student/profile/general') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if(!res.ok) throw new Error('Update failed');
                
                this.profile.general = { ...payload };
                this.modals.general = false;
                this.showToast('Profile updated successfully!');
            } catch(e) {
                this.showToast(e.message, 'error');
            } finally {
                this.loading = false;
            }
        },

        openModal(type, record = null) {
            this.activeRelationType = type;
            this.activeModalTitle = record ? `Edit ${type}` : `Add ${type}`;
            this.formData.relation = record ? { ...record } : {};
            
            // Explicitly set false if undefined for new records
            if (type === 'employment' && this.formData.relation.is_current === undefined) {
                this.formData.relation.is_current = false;
            }

            this.modals.relation = true;
        },

        async saveRelation() {
            this.loading = true;
            const endpoint = `<?= base_url('api/student/profile/') ?>${this.activeRelationType}`;
            
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formData.relation)
                });
                
                if(!res.ok) throw new Error('Save failed');
                await this.initData(); // Refresh all data to keep synced
                
                this.modals.relation = false;
                this.showToast('Saved successfully!');
            } catch(e) {
                this.showToast(e.message, 'error');
            } finally {
                this.loading = false;
            }
        },

        async deleteRecord(type, id) {
            if(!confirm('Are you sure you want to delete this?')) return;
            this.loading = true;
            try {
                const res = await fetch(`<?= base_url('api/student/profile/') ?>${type}/${id}`, { method: 'DELETE' });
                if(!res.ok) throw new Error('Deletion failed');
                await this.initData();
                this.showToast('Deleted successfully!');
            } catch(e) {
                this.showToast(e.message, 'error');
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            if(!dateString) return '';
            return new Date(dateString).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        },

        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => this.toast.show = false, 3000);
        }
    }
}
</script>
<?= $this->endSection() ?>
