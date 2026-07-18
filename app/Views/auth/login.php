<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div x-data="loginForm()">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome back</h1>
        <p class="text-sm text-slate-500 mt-2">Enter your credentials to access your account</p>
    </div>

    <!-- Error/Success Alerts -->
    <div x-show="message" x-transition.opacity
         :class="status === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'"
         class="mb-6 p-4 rounded-xl border text-sm font-medium flex items-start gap-3"
         style="display: none;">
        <svg x-show="status === 'success'" class="w-5 h-5 mt-0.5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <svg x-show="status !== 'success'" class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span x-text="message"></span>
    </div>

    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label for="login-email" class="block text-sm font-medium text-slate-700 mb-1.5">Email address</label>
            <input type="email" id="login-email" x-model="formData.email" required placeholder="e.g. john@example.com"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
        </div>

        <div>
            <label for="login-password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
            <input type="password" id="login-password" x-model="formData.password" required placeholder="Enter your password"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
            <div class="mt-2 text-right">
                <span class="text-xs text-slate-500">Forgot password? <a href="<?= base_url('auth/forgot-password') ?>" class="font-medium text-primary-600 hover:text-primary-700 transition-colors">Reset password</a></span>
            </div>
        </div>

        <div class="flex items-center">
            <input id="remember" type="checkbox" x-model="formData.remember" class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
            <label for="remember" class="ml-2 block text-sm text-slate-600">Remember me for 30 days</label>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg hover:shadow-primary-500/25">
            <span x-show="!loading">Sign in</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Signing in...
            </span>
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
        <div class="relative flex justify-center text-xs"><span class="bg-white px-3 text-slate-400 uppercase tracking-wider">New here?</span></div>
    </div>

    <div class="text-center">
        <a href="<?= base_url('auth/register') ?>" class="inline-flex items-center justify-center w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all">
            Create an account
        </a>
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
