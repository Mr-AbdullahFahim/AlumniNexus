<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>
<div x-data="verifyForm()">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verify Your Email</h1>
        <p class="text-sm text-slate-500 mt-2" x-text="`We've sent a 6-digit code to ${formData.email}. Enter it below.`">We've sent a 6-digit code to your email. Enter it below.</p>
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
            <label for="verify-otp" class="block text-sm font-medium text-slate-700 mb-1.5">6-Digit OTP</label>
            <input type="text" id="verify-otp" x-model="formData.otp" required maxlength="6" pattern="\d{6}" placeholder="Enter 6-digit code"
                   class="w-full px-4 py-4 text-center tracking-[0.5em] text-2xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all text-slate-900 placeholder-slate-400 placeholder:text-sm placeholder:tracking-normal placeholder:font-normal">
        </div>

        <button type="submit" :disabled="loading"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg hover:shadow-primary-500/25">
            <span x-show="!loading">Verify OTP</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Verifying...
            </span>
        </button>
    </form>

    <div class="mt-8">
        <a href="<?= base_url('auth/login') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Login
        </a>
    </div>
</div>

<script>
function verifyForm() {
    return {
        formData: {
            email: new URLSearchParams(window.location.search).get('email') || '',
            otp: ''
        },
        loading: false,
        message: '',
        status: '',
        async submit() {
            this.loading = true;
            this.message = '';
            try {
                const response = await fetch('<?= base_url('api/auth/verify-email') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                
                this.status = data.status || 'error';
                
                if (response.ok) {
                    this.message = 'Email verified successfully! Redirecting...';
                    setTimeout(() => window.location.href = '<?= base_url('auth/login') ?>', 2000);
                } else {
                    this.message = data.message || Object.values(data.messages || {}).join(' ') || 'Verification failed.';
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
