<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div x-data="resetPasswordForm()">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Set new password</h1>
        <p class="text-sm text-slate-500 mt-2">Please enter your new password below.</p>
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

    <form @submit.prevent="submit" class="space-y-5" x-show="status !== 'success'">
        
        <!-- We retrieve email and token from query string via alpine init -->
        <div x-init="
            const urlParams = new URLSearchParams(window.location.search);
            formData.email = urlParams.get('email');
            formData.token = urlParams.get('token');
        "></div>

        <div>
            <label for="reset-password" class="block text-sm font-medium text-slate-700 mb-1.5">New Password</label>
            <input type="password" id="reset-password" x-model="formData.password" required minlength="8" placeholder="Min. 8 characters"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
        </div>

        <div>
            <label for="reset-confirm-password" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password</label>
            <input type="password" id="reset-confirm-password" x-model="formData.confirm_password" required minlength="8" placeholder="Re-enter your new password"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg hover:shadow-primary-500/25">
            <span x-show="!loading">Reset Password</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Saving...
            </span>
        </button>
    </form>
    
    <div class="mt-8" x-show="status === 'success'">
        <a href="<?= base_url('auth/login') ?>" class="inline-flex items-center justify-center w-full py-3 px-4 rounded-xl bg-primary-600 text-sm font-semibold text-white hover:bg-primary-700 transition-all gap-2">
            Sign in to your account
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>
</div>

<script>
function resetPasswordForm() {
    return {
        formData: {
            email: '',
            token: '',
            password: '',
            confirm_password: ''
        },
        loading: false,
        message: '',
        status: '',
        async submit() {
            if(this.formData.password !== this.formData.confirm_password) {
                this.status = 'error';
                this.message = 'Passwords do not match.';
                return;
            }
            if(!this.formData.token || !this.formData.email) {
                this.status = 'error';
                this.message = 'Invalid reset link. Missing token or email.';
                return;
            }

            this.loading = true;
            this.message = '';
            try {
                const response = await fetch('<?= base_url('api/auth/reset-password') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                
                this.status = data.status || 'error';
                
                if (response.ok) {
                    this.message = data.message || 'Password reset successfully.';
                } else {
                    let err = data.message;
                    if(data.messages) {
                        err = Object.values(data.messages).join('<br>');
                    }
                    this.message = err || 'Password reset failed.';
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
