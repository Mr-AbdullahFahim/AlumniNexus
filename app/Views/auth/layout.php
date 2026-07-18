<!DOCTYPE html>
<html lang="en" class="antialiased h-full text-slate-900 bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AlumniNexus - Alumni Influencers Platform' ?></title>
    
    <!-- Force full page reload when Turbo tries to navigate here -->
    <meta name="turbo-visit-control" content="reload">

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
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Animated gradient overlay on hero */
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.83) 0%, rgba(29, 44, 130, 0.7) 50%, rgba(24, 88, 189, 0.6) 100%);
            background-size: 200% 200%;
            opacity: 0.7;
            animation: gradientShift 8s ease infinite;
        }

        /* Subtle floating animation for decorative elements */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .float-anim { animation: float 6s ease-in-out infinite; }
        .float-anim-delay { animation: float 6s ease-in-out 2s infinite; }

        /* Custom scrollbar for the form panel */
        .auth-panel::-webkit-scrollbar { width: 4px; }
        .auth-panel::-webkit-scrollbar-track { background: transparent; }
        .auth-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="h-full flex overflow-hidden bg-white">
    
    <!-- LEFT SIDE: Hero Image Panel (2/3 width) -->
    <div class="hidden lg:flex lg:w-2/3 relative overflow-hidden">
        <!-- Background Image -->
        <img src="<?= base_url('assets/images/auth-hero.png') ?>" 
             alt="Alumni Networking" 
             class="absolute inset-0 w-full h-full object-cover">
        
        <!-- Gradient Overlay -->
        <div class="hero-gradient absolute inset-0"></div>
        
        <!-- Content over hero -->
        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            
            <!-- Top: Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white font-bold text-xl border border-white/20">
                    A
                </div>
                <span class="text-2xl font-bold tracking-tight text-white">AlumniNexus</span>
            </div>
            
            <!-- Center: Hero Text -->
            <div class="max-w-lg">
                <h1 class="text-5xl font-extrabold text-white leading-tight mb-6">
                    Where Alumni
                    <span class="block text-primary-200">Shape the Future</span>
                </h1>
                <p class="text-lg text-blue-100/90 leading-relaxed mb-8">
                    Connect with fellow graduates, showcase your achievements, and unlock exclusive sponsorship opportunities through our innovative blind bidding platform.
                </p>
                
                <!-- Stats Row -->
                <div class="flex gap-8">
                    <div class="float-anim">
                        <div class="text-3xl font-bold text-white">500+</div>
                        <div class="text-sm text-blue-200/80 mt-1">Active Alumni</div>
                    </div>
                    <div class="float-anim-delay">
                        <div class="text-3xl font-bold text-white">$2M+</div>
                        <div class="text-sm text-blue-200/80 mt-1">Sponsorships</div>
                    </div>
                    <div class="float-anim">
                        <div class="text-3xl font-bold text-white">50+</div>
                        <div class="text-sm text-blue-200/80 mt-1">Partners</div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom: Testimonial / Trust -->
            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full bg-primary-300 border-2 border-white/30 flex items-center justify-center text-xs font-bold text-primary-900">JD</div>
                    <div class="w-10 h-10 rounded-full bg-blue-300 border-2 border-white/30 flex items-center justify-center text-xs font-bold text-blue-900">AK</div>
                    <div class="w-10 h-10 rounded-full bg-indigo-300 border-2 border-white/30 flex items-center justify-center text-xs font-bold text-indigo-900">SM</div>
                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center text-xs font-medium text-white">+47</div>
                </div>
                <p class="text-sm text-blue-100/80">Trusted by alumni from <span class="font-semibold text-white">top universities</span> worldwide</p>
            </div>
        </div>
        
        <!-- Decorative floating shapes -->
        <div class="absolute top-20 right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl float-anim"></div>
        <div class="absolute bottom-32 left-20 w-48 h-48 bg-primary-300/10 rounded-full blur-2xl float-anim-delay"></div>
    </div>

    <!-- RIGHT SIDE: Form Panel (1/3 width) -->
    <div class="w-full lg:w-1/3 flex flex-col auth-panel overflow-y-auto bg-white">
        
        <!-- Mobile-only logo header -->
        <div class="lg:hidden flex items-center gap-2 p-6 pb-0">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-500/30">
                A
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900">AlumniNexus</span>
        </div>

        <!-- Form Content (centered vertically) -->
        <div class="flex-1 flex flex-col justify-center px-8 sm:px-12 py-10">
            <?= $this->renderSection('content') ?>
        </div>
        
        <!-- Bottom branding -->
        <div class="px-8 sm:px-12 pb-6 text-center">
            <p class="text-xs text-slate-400">&copy; <?= date('Y') ?> AlumniNexus. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
