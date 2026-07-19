<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="space-y-6" x-data="analyticsDashboard()">
    
    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Analytics Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time insights and graduate outcomes intelligence.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="exportCSV" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-50 transition">
                Export CSV
            </button>
            <button @click="generatePDFReport" :disabled="isGeneratingPDF" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center">
                <svg x-show="isGeneratingPDF" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="isGeneratingPDF ? 'Generating...' : 'Generate PDF Report'"></span>
            </button>
        </div>
    </div>

    <?= view_cell('\App\Cells\FeaturedAlumniCell::render', ['compact' => true, 'rounded' => true]) ?>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Industry</label>
                    <input type="text" x-model="filters.industry" placeholder="e.g. IT, Healthcare" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Edu Start Year</label>
                    <input type="number" x-model="filters.edu_start_year" placeholder="YYYY" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Edu End Year</label>
                    <input type="number" x-model="filters.edu_end_year" placeholder="YYYY" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Work Start Year</label>
                    <input type="number" x-model="filters.work_start_year" placeholder="YYYY" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Work End Year</label>
                    <input type="number" x-model="filters.work_end_year" placeholder="YYYY" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="applyFilters" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                    Apply Filters
                </button>
            </div>
        </div>
        
        <!-- Presets -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <select x-model="selectedPreset" @change="applyPreset" class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-64">
                    <option value="">-- Load Saved Preset --</option>
                    <template x-for="preset in presets" :key="preset.id">
                        <option :value="preset.id" x-text="preset.name"></option>
                    </template>
                </select>
                <button @click="deletePreset" x-show="selectedPreset" class="text-red-500 hover:text-red-700 text-sm font-medium px-2" title="Delete Preset">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" x-model="newPresetName" placeholder="Preset Name" class="w-full md:w-48 text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button @click="savePreset" :disabled="!newPresetName" class="bg-emerald-600 disabled:opacity-50 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition whitespace-nowrap">
                    Save Preset
                </button>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- 1. Top Certifications (Bar Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    Skills Gap Detection: Top Certifications
                </h3>
                <button @click="downloadChart('topCertificationsChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="topCertificationsChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Top certifications independently acquired by alumni.</p>
        </div>

        <!-- 2. Emerging Career Pathways (Doughnut Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    Emerging Career Pathways
                </h3>
                <button @click="downloadChart('emergingPathwaysChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="emergingPathwaysChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Current roles held by alumni indicating new pathways.</p>
        </div>

        <!-- 3. Industry Distribution (Pie Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span>
                    Industry Distribution
                </h3>
                <button @click="downloadChart('industryDistributionChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="industryDistributionChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Industries where graduates are currently employed.</p>
        </div>

        <!-- 4. Certification Growth (Line Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    Industry Demand Tracking
                </h3>
                <button @click="downloadChart('certificationGrowthChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="certificationGrowthChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Growth in post-graduate certification acquisition.</p>
        </div>

        <!-- 5. Skills vs Core Curriculum (Radar Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                    Core Curriculum Gap Analysis
                </h3>
                <button @click="downloadChart('skillsCurriculumChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="skillsCurriculumChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Most frequently cited technical skills by alumni.</p>
        </div>

        <!-- 6. Professional Courses (Horizontal Bar Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                    Professional Development Trends
                </h3>
                <button @click="downloadChart('professionalCoursesChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="professionalCoursesChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Most popular courses completed post-graduation.</p>
        </div>

        <!-- 7. Employment Status (Doughnut Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 mr-2"></span>
                    Employment Status Overview
                </h3>
                <button @click="downloadChart('employmentStatusChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="employmentStatusChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Ratio of currently employed alumni vs in-transition.</p>
        </div>

        <!-- 8. User Roles (Pie Chart) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 group relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-slate-500 mr-2"></span>
                    Platform Adoption
                </h3>
                <button @click="downloadChart('userRolesChart')" class="text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition" title="Download Chart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="userRolesChart"></canvas>
            </div>
            <p class="text-xs text-slate-500 mt-3 text-center">Distribution of active users across roles.</p>
        </div>

    </div>
</div>

<script>
    const setupAnalyticsDashboard = () => {
        if (window.Alpine.data.hasOwnProperty('analyticsDashboard')) return; // Avoid re-registering
        
        Alpine.data('analyticsDashboard', () => ({
            charts: {},
            filters: {
                industry: '',
                edu_start_year: '',
                edu_end_year: '',
                work_start_year: '',
                work_end_year: ''
            },
            presets: [],
            selectedPreset: '',
            newPresetName: '',
            isGeneratingPDF: false,
            
            init() {
            // Chart global defaults
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';
            Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15, 23, 42, 0.9)';
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 8;
            
            // Common colors
            const palette = [
                'rgba(59, 130, 246, 0.8)', // blue-500
                'rgba(16, 185, 129, 0.8)', // emerald-500
                'rgba(245, 158, 11, 0.8)', // amber-500
                'rgba(239, 68, 68, 0.8)',  // red-500
                'rgba(139, 92, 246, 0.8)', // purple-500
                'rgba(14, 165, 233, 0.8)', // sky-500
                'rgba(244, 63, 94, 0.8)',  // rose-500
                'rgba(99, 102, 241, 0.8)'  // indigo-500
            ];
            
            this.palette = palette;
            
            this.loadAllCharts();
            this.fetchPresets();
        },
        
        loadAllCharts() {
            this.loadChart('topCertificationsChart', 'api/admin/analytics/top-certifications', 'bar', this.palette[3]);
            this.loadChart('emergingPathwaysChart', 'api/admin/analytics/emerging-pathways', 'doughnut', this.palette);
            this.loadChart('industryDistributionChart', 'api/admin/analytics/industry-distribution', 'pie', this.palette);
            this.loadChart('certificationGrowthChart', 'api/admin/analytics/certification-growth', 'line', this.palette[1], { fill: true, tension: 0.4 });
            this.loadChart('skillsCurriculumChart', 'api/admin/analytics/skills-curriculum', 'radar', this.palette[2], { fill: true, backgroundColor: 'rgba(245, 158, 11, 0.2)' });
            this.loadChart('professionalCoursesChart', 'api/admin/analytics/professional-courses', 'bar', this.palette[7], { indexAxis: 'y' });
            this.loadChart('employmentStatusChart', 'api/admin/analytics/employment-status', 'doughnut', [this.palette[1], this.palette[3]]);
            this.loadChart('userRolesChart', 'api/admin/analytics/user-roles', 'pie', [this.palette[0], this.palette[2], this.palette[4], this.palette[5]]);
        },
        
        loadChart(canvasId, endpoint, type, colors, extraOptions = {}) {
            const queryParams = new URLSearchParams(this.filters).toString();
            const url = `<?= rtrim(base_url(), '/') ?>/${endpoint}?${queryParams}`;
            
            fetch(url, {
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById(canvasId).getContext('2d');
                
                // Configure dataset
                let dataset = data.datasets[0];
                dataset.backgroundColor = colors;
                dataset.borderWidth = (type === 'line' || type === 'radar') ? 2 : 0;
                dataset.borderColor = (type === 'line' || type === 'radar') ? colors : 'transparent';
                
                // Apply extras
                Object.assign(dataset, extraOptions);

                // Configure options based on type
                let options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: (type === 'pie' || type === 'doughnut'),
                            position: 'right',
                            labels: { boxWidth: 12, usePointStyle: true }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                };

                // Add scales for bar/line charts
                if (type === 'bar' || type === 'line') {
                    options.scales = {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(241, 245, 249, 1)' } // slate-100
                        },
                        x: { 
                            grid: { display: false }
                        }
                    };
                }
                
                // For horizontal bar
                if (extraOptions.indexAxis === 'y') {
                    options.indexAxis = 'y';
                    options.scales = {
                        x: { beginAtZero: true, grid: { color: 'rgba(241, 245, 249, 1)' } },
                        y: { grid: { display: false } }
                    };
                }

                // Render
                if (this.charts[canvasId]) {
                    this.charts[canvasId].destroy();
                }
                
                this.charts[canvasId] = new Chart(ctx, {
                    type: type,
                    data: data,
                    options: options
                });
            });
        },
        
        applyFilters() {
            this.loadAllCharts();
        },
        
        fetchPresets() {
            fetch(`<?= rtrim(base_url(), '/') ?>/api/admin/analytics/presets`, { credentials: 'same-origin' })
                .then(res => res.json())
                .then(data => {
                    this.presets = data.data || [];
                });
        },
        
        savePreset() {
            if (!this.newPresetName) return;
            
            const formData = new FormData();
            formData.append('name', this.newPresetName);
            formData.append('filters', JSON.stringify(this.filters));
            
            fetch(`<?= rtrim(base_url(), '/') ?>/api/admin/analytics/presets`, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                this.newPresetName = '';
                this.fetchPresets();
            });
        },
        
        applyPreset() {
            if (!this.selectedPreset) return;
            const preset = this.presets.find(p => p.id == this.selectedPreset);
            if (preset && preset.filters) {
                try {
                    this.filters = JSON.parse(preset.filters);
                    this.applyFilters();
                } catch(e) {}
            }
        },
        
        deletePreset() {
            if (!this.selectedPreset || !confirm('Delete this preset?')) return;
            fetch(`<?= rtrim(base_url(), '/') ?>/api/admin/analytics/presets/${this.selectedPreset}`, {
                method: 'DELETE',
                credentials: 'same-origin'
            }).then(() => {
                this.selectedPreset = '';
                this.fetchPresets();
            });
        },
        
        exportCSV() {
            const queryParams = new URLSearchParams(this.filters).toString();
            window.location.href = `<?= rtrim(base_url(), '/') ?>/api/admin/analytics/export-csv?${queryParams}`;
        },
        
        generatePDFReport() {
            this.isGeneratingPDF = true;
            
            // Gather all charts as base64 images
            const chartsData = [];
            const canvasIds = [
                { id: 'topCertificationsChart', title: 'Top Certifications' },
                { id: 'emergingPathwaysChart', title: 'Emerging Career Pathways' },
                { id: 'industryDistributionChart', title: 'Industry Distribution' },
                { id: 'certificationGrowthChart', title: 'Certification Growth' },
                { id: 'skillsCurriculumChart', title: 'Skills vs Core Curriculum' },
                { id: 'professionalCoursesChart', title: 'Professional Courses' },
                { id: 'employmentStatusChart', title: 'Employment Status' },
                { id: 'userRolesChart', title: 'User Roles' }
            ];
            
            canvasIds.forEach(chartInfo => {
                const chartInstance = this.charts[chartInfo.id];
                if (chartInstance) {
                    chartsData.push({
                        title: chartInfo.title,
                        imageBase64: chartInstance.toBase64Image('image/jpeg', 0.8) // Use JPEG to reduce size
                    });
                }
            });
            
            const formData = new FormData();
            formData.append('charts', JSON.stringify(chartsData));
            
            fetch(`<?= rtrim(base_url(), '/') ?>/api/admin/analytics/export-pdf`, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Server returned ' + response.status);
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Analytics_Report_${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                this.isGeneratingPDF = false;
            })
            .catch(error => {
                this.isGeneratingPDF = false;
                alert('Failed to generate report: ' + error.message);
            });
        },
        
        downloadChart(canvasId) {
            const chart = this.charts[canvasId];
            if (!chart) return;
            const a = document.createElement('a');
            a.href = chart.toBase64Image();
            a.download = `${canvasId}.png`;
            a.click();
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
        setupAnalyticsDashboard();
    } else {
        document.addEventListener('alpine:init', setupAnalyticsDashboard);
    }
</script>
<?= $this->endSection() ?>
