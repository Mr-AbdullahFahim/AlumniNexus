<!DOCTYPE html>
<html lang="en" class="antialiased h-full" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - AlumniNexus' ?></title>

    <!-- SPA-like Navigation without full page reloads -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
    <?= csrf_meta() ?>

    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    },
                    boxShadow: {
                        'glass': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06), inset 0 1px 0 rgba(255, 255, 255, 0.1)',
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSRF global fetch interceptor -->
    <script>
        const originalFetch = window.fetch;
        window.fetch = async function() {
            let [resource, config] = arguments;
            if (!config) config = {};
            
            if (config.method && ['POST', 'PUT', 'DELETE', 'PATCH'].includes(config.method.toUpperCase())) {
                const match = document.cookie.match(new RegExp('(^| )csrf_cookie_name=([^;]+)'));
                let token = match ? match[2] : null;
                if (!token) {
                    const meta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
                    if (meta) token = meta.getAttribute('content');
                }
                if (token) {
                    config.headers = {
                        ...config.headers,
                        'X-CSRF-TOKEN': token
                    };
                }
            }
            return originalFetch(resource, config);
        };
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex overflow-hidden">
    
    <!-- Sidebar Component -->
    <?= view('components/sidebar') ?>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar Component -->
        <?= view('components/topbar') ?>
        
        <!-- Main Scrollable Area -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none flex flex-col">
            <div class="py-6 flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    
                    <!-- Dynamic View Content -->
                    <?= $this->renderSection('content') ?>

                </div>
            </div>
            
            <!-- Footer Component -->
            <?= view('components/footer') ?>
        </main>
    </div>

</body>
</html>
