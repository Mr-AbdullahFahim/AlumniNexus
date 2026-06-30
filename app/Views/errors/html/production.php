<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Server Error - AlumniNexus</title>
    
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

    <div class="text-center px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mb-6 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight sm:text-5xl">500</h1>
        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Internal Server Error</h2>
        <p class="mt-4 text-base text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
            Something went wrong on our end. We're working on fixing it. Please try again later.
        </p>
        
        <div class="mt-8 flex justify-center gap-4">
            <a href="<?= base_url() ?>" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 shadow-sm transition-colors">
                Go back home
            </a>
        </div>
    </div>

</body>
</html>
