<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div class="glass-panel rounded-2xl p-8 shadow-glass transition-all duration-300 hover:shadow-xl"
     x-data="registerForm()">
    
    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Create an account</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Join AlumniNexus today.</p>
    </div>

    <!-- Error/Success Alerts -->
    <div x-show="message" x-transition.opacity
         :class="status === 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200'"
         class="mb-6 p-4 rounded-lg border text-sm font-medium"
         x-text="message" style="display: none;">
    </div>

    <form @submit.prevent="submit" class="space-y-5" x-show="status !== 'success'">
        
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Full Name</label>
            <input type="text" id="name" x-model="formData.name" required
                   class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
            <input type="email" id="email" x-model="formData.email" required
                   class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                <input type="password" id="password" x-model="formData.password" required minlength="8"
                       class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
            </div>
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Confirm Password</label>
                <input type="password" id="confirm_password" x-model="formData.confirm_password" required minlength="8"
                       class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white placeholder-slate-400">
            </div>
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">I am registering as</label>
            <select id="role" x-model="formData.role_id" required
                    class="w-full px-4 py-2.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-colors text-slate-900 dark:text-white">
                <option value="" disabled>Select your role...</option>
                <option value="2">Alumni</option>
                <option value="3">Student</option>
                <option value="4">Sponsor</option>
            </select>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!loading">Create account</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Processing...
            </span>
        </button>
    </form>

    <div class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
        Already have an account? 
        <a href="<?= base_url('auth/login') ?>" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">Sign in</a>
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
                    this.message = data.message || 'Registration successful!';
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
