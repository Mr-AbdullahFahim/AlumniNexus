<!DOCTYPE html>
<html lang="en" class="antialiased scroll-smooth" x-data="{ darkMode: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 relative overflow-x-hidden flex flex-col min-h-screen">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-primary-500/30">
                        A
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">AlumniNexus</span>
                </div>
                <div class="flex items-center gap-4">
                    <?php if (!$isLoggedIn): ?>
                        <a href="<?= base_url('auth/login') ?>" class="text-slate-600 hover:text-slate-900 font-medium transition-colors hidden sm:block">Log in</a>
                    <?php endif; ?>
                    <a href="<?= esc($ctaUrl) ?>" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5 duration-200">
                        <?= esc($ctaText) ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden flex-1 flex flex-col justify-center">
        <!-- Background Blobs -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-indigo-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 border border-primary-100 text-primary-600 text-sm font-medium mb-8">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                </span>
                The Ultimate Alumni Influencer Platform
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-tight">
                Connect. Sponsor. <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-600">Elevate Your Network.</span>
            </h1>
            
            <p class="mt-4 text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                Empowering students, alumni, and sponsors to collaborate seamlessly. Build your profile, receive sponsorships, and win featured spots in daily competitive bidding cycles.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="<?= esc($ctaUrl) ?>" class="w-full sm:w-auto bg-primary-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30 transform hover:-translate-y-1">
                    <?= esc($ctaText) ?>
                </a>
                <a href="#how-it-works" class="w-full sm:w-auto bg-white text-slate-700 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-slate-50 border border-slate-200 transition-all shadow-sm transform hover:-translate-y-1">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="how-it-works" class="py-24 bg-white relative border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">How AlumniNexus Works</h2>
                <p class="mt-4 text-lg text-slate-600">A complete ecosystem for students, alumni, and sponsors.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-primary-50 rounded-2xl flex items-center justify-center mb-6 transform group-hover:-translate-y-2 transition-all duration-300 shadow-sm border border-primary-100">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">1. Students & Alumni</h3>
                    <p class="text-slate-600 leading-relaxed">Join the network, build your professional profile, showcase your achievements, and connect with peers and mentors across the globe.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 transform group-hover:-translate-y-2 transition-all duration-300 shadow-sm border border-indigo-100">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">2. Corporate Sponsors</h3>
                    <p class="text-slate-600 leading-relaxed">Browse top talent in the directory and offer direct financial sponsorships to increase their bidding power in the network.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-purple-50 rounded-2xl flex items-center justify-center mb-6 transform group-hover:-translate-y-2 transition-all duration-300 shadow-sm border border-purple-100">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">3. Engage & Win</h3>
                    <p class="text-slate-600 leading-relaxed">Use accumulated sponsorships to place blind bids in daily competitive cycles. Winners gain prime featured spots and maximum visibility.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Frequently Asked Questions</h2>
            </div>
            
            <div class="space-y-4" x-data="{ active: null }">
                <!-- FAQ Item 1 -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-semibold text-slate-900">What is AlumniNexus?</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <div class="px-6 pb-5 text-slate-600 leading-relaxed">
                            AlumniNexus is a premium networking platform that bridges the gap between students, successful alumni, and corporate sponsors. It allows users to build profiles, connect with peers, and participate in a unique gamified sponsorship and bidding system for ultimate visibility.
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-semibold text-slate-900">How do sponsorships work?</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <div class="px-6 pb-5 text-slate-600 leading-relaxed">
                            Sponsors can browse the directory and offer virtual funds to promising students and alumni. These funds act as bidding power, allowing the sponsored individuals to participate in daily competitive cycles without spending their own money.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-semibold text-slate-900">What are the daily bidding cycles?</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 3" x-collapse x-cloak>
                        <div class="px-6 pb-5 text-slate-600 leading-relaxed">
                            Every day, users can place blind bids using their accumulated sponsorship funds. At 6:00 PM, the cycle closes and the highest bidders are rewarded with the prestigious "Featured Alumni" spot, granting them massive visibility across the entire network for the next 24 hours.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
                        A
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">AlumniNexus</span>
                </div>
                <div class="flex gap-6 text-sm text-slate-500">
                    <a href="#" class="hover:text-slate-900 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Contact</a>
                </div>
            </div>
            <div class="mt-8 text-center md:text-left text-sm text-slate-400">
                &copy; <?= date('Y') ?> AlumniNexus. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
