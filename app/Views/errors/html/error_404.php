<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - AlumniNexus</title>
    
    <!-- Prevent Dark Mode Flash (FOUC) -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: { 50: '#eff6ff', 500: '#3b82f6', 600: '#2563eb' } }
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex flex-col justify-center items-center">

    <!-- Escape Hatch for Turbo: Clear cache so back button works, then force hard reload -->
    <script>
        if (typeof window.Turbo !== 'undefined') {
            window.Turbo.cache.clear();
            window.location.reload();
        }
    </script>

    <div class="text-center px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-6 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight sm:text-5xl">404</h1>
        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Page not found</h2>
        <p class="mt-4 text-base text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
            Sorry, we couldn't find the page you're looking for. It might have been moved or doesn't exist.
        </p>
        
        <div class="mt-8 flex justify-center gap-4">
            <a href="<?= base_url() ?>" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 shadow-sm transition-colors">
                Go back home
            </a>
            <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-slate-200 dark:border-slate-700 text-base font-medium rounded-lg text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition-colors">
                Contact support
            </a>
        </div>
    </div>
</body>
</html>
