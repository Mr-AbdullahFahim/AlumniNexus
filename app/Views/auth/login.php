<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div class="glass-panel rounded-2xl p-8 shadow-glass transition-all duration-300 hover:shadow-xl"
     x-data="loginForm()">
    
    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Welcome back</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Enter your credentials to access your account</p>
    </div>

    <!-- Error/Success Alerts -->
    <div x-show="message" x-transition.opacity
         :class="status === 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200'"
         class="mb-6 p-4 rounded-lg border text-sm font-medium"
         x-text="message" style="display: none;">
    </div>

    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
            <input type="email" id="email" x-model="formData.email" required
                   class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                <a href="<?= base_url('auth/forgot-password') ?>" class="text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Forgot password?</a>
            </div>
            <input type="password" id="password" x-model="formData.password" required
                   class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
        </div>

        <div class="flex items-center">
            <input id="remember" type="checkbox" x-model="formData.remember" class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
            <label for="remember" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">Remember me for 30 days</label>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!loading">Sign in</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Signing in...
            </span>
        </button>
    </form>

    <div class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
        Don't have an account? 
        <a href="<?= base_url('auth/register') ?>" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">Sign up</a>
    </div>
</div>

<script>
function loginForm() {
    return {
        formData: {
            email: '',
            password: '',
            remember: false
        },
        loading: false,
        message: '',
        status: '',
        async submit() {
            this.loading = true;
            this.message = '';
            try {
                const response = await fetch('<?= base_url('api/auth/login') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                
                this.status = data.status || 'error';
                
                if (response.ok) {
                    this.message = 'Login successful! Redirecting...';
                    let redirectUrl = '<?= base_url('/') ?>';
                    
                    // Redirect based on role ID
                    if (data.user && data.user.role == 1) redirectUrl = '<?= base_url('admin/dashboard') ?>';
                    if (data.user && data.user.role == 2) redirectUrl = '<?= base_url('alumni/dashboard') ?>';
                    if (data.user && data.user.role == 3) redirectUrl = '<?= base_url('student/favorites') ?>';
                    if (data.user && data.user.role == 4) redirectUrl = '<?= base_url('sponsor/dashboard') ?>';

                    setTimeout(() => window.location.href = redirectUrl, 1000);
                } else {
                    this.message = data.message || Object.values(data.messages || {}).join(' ') || 'Login failed.';
                }
            } catch (error) {
                this.status = 'error';
                this.message = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
