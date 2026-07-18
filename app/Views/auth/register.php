<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div x-data="registerForm()">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create an account</h1>
        <p class="text-sm text-slate-500 mt-2">Join AlumniNexus and start your journey today.</p>
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

    <form @submit.prevent="submit" class="space-y-4" x-show="status !== 'success'">
        
        <div>
            <label for="reg-name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
            <input type="text" id="reg-name" x-model="formData.name" required placeholder="e.g. John Doe"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
        </div>

        <div>
            <label for="reg-email" class="block text-sm font-medium text-slate-700 mb-1.5">Email address</label>
            <input type="email" id="reg-email" x-model="formData.email" required placeholder="e.g. john@example.com"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="reg-password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                <input type="password" id="reg-password" x-model="formData.password" required minlength="8" placeholder="Min. 8 characters"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
            </div>
            <div>
                <label for="reg-confirm-password" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm</label>
                <input type="password" id="reg-confirm-password" x-model="formData.confirm_password" required minlength="8" placeholder="Re-enter password"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 text-sm">
            </div>
        </div>

        <div>
            <label for="reg-role" class="block text-sm font-medium text-slate-700 mb-1.5">I am registering as</label>
            <select id="reg-role" x-model="formData.role_id" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 text-sm">
                <option value="" disabled>Select your role...</option>
                <option value="2">Alumni</option>
                <option value="3">Student</option>
                <option value="4">Sponsor</option>
            </select>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg hover:shadow-primary-500/25">
            <span x-show="!loading">Create account</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Processing...
            </span>
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
        <div class="relative flex justify-center text-xs"><span class="bg-white px-3 text-slate-400 uppercase tracking-wider">Already a member?</span></div>
    </div>

    <div class="text-center">
        <a href="<?= base_url('auth/login') ?>" class="inline-flex items-center justify-center w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all">
            Sign in instead
        </a>
    </div>
</div>

<script>
function registerForm() {
    return {
        formData: {
            name: '',
            email: '',
            password: '',
            confirm_password: '',
            role_id: ''
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
            this.loading = true;
            this.message = '';
            try {
                const response = await fetch('<?= base_url('api/auth/register') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                
                this.status = data.status || 'error';
                
                if (response.ok) {
                    this.message = data.message || 'Registration successful! Redirecting to verification...';
                    setTimeout(() => window.location.href = '<?= base_url('auth/verify-email') ?>?email=' + encodeURIComponent(this.formData.email), 1500);
                } else {
                    let err = data.message;
                    if(data.messages) {
                        err = Object.values(data.messages).join('<br>');
                    }
                    this.message = err || 'Registration failed.';
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
