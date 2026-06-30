<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div class="glass-panel rounded-2xl p-8 shadow-glass transition-all duration-300 hover:shadow-xl"
     x-data="forgotPasswordForm()">
    
    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Reset Password</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Enter your email and we'll send you a link to reset your password.</p>
    </div>

    <!-- Error/Success Alerts -->
    <div x-show="message" x-transition.opacity
         :class="status === 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200'"
         class="mb-6 p-4 rounded-lg border text-sm font-medium"
         x-text="message" style="display: none;">
    </div>

    <form @submit.prevent="submit" class="space-y-5" x-show="status !== 'success'">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
            <input type="email" id="email" x-model="email" required
                   class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!loading">Send Reset Link</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Sending...
            </span>
        </button>
    </form>

    <div class="mt-8 text-center text-sm">
        <a href="<?= base_url('auth/login') ?>" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors flex items-center justify-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to login
        </a>
    </div>
</div>

<script>
function forgotPasswordForm() {
    return {
        email: '',
        loading: false,
        message: '',
        status: '',
        async submit() {
            this.loading = true;
            this.message = '';
            try {
                const response = await fetch('<?= base_url('api/auth/forgot-password') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: this.email })
                });
                const data = await response.json();
                
                this.status = data.status || 'error';
                
                if (response.ok) {
                    this.message = data.message || 'If your email is registered, you will receive a password reset link shortly.';
                } else {
                    this.message = data.message || Object.values(data.messages || {}).join(' ') || 'Failed to send reset link.';
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
