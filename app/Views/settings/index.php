<?= $this->extend('layouts/settings') ?>

<?= $this->section('title') ?>Account Settings<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Account Settings</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Manage your email address, password, and account deletion.</p>
    </div>

    <!-- Email Settings Section -->
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg mb-8 overflow-hidden" x-data="emailSettings()">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">Email Address</h3>
        </div>
        <div class="p-6">
            <div x-show="step === 1">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Current Email: <strong><?= esc($user['email']) ?></strong></p>
                <button @click="initiateChange" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium disabled:opacity-50">
                    Change Email Address
                </button>
            </div>

            <!-- Message Alert -->
            <div x-show="message" x-transition class="mt-4 p-4 rounded-md text-sm" :class="status === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'" x-text="message" style="display: none;"></div>

            <!-- Step 2: Verify Current Email -->
            <div x-show="step === 2" class="mt-4" style="display: none;">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">An OTP has been sent to your current email. Enter it below to proceed.</p>
                <div class="flex space-x-4">
                    <input type="text" x-model="currentOtp" placeholder="6-digit OTP" maxlength="6" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm">
                    <button @click="verifyCurrent" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium whitespace-nowrap disabled:opacity-50">Verify OTP</button>
                </div>
            </div>

            <!-- Step 3: Enter New Email -->
            <div x-show="step === 3" class="mt-4" style="display: none;">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Enter your new email address.</p>
                <div class="flex space-x-4">
                    <input type="email" x-model="newEmail" placeholder="New Email Address" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm">
                    <button @click="initiateNew" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium whitespace-nowrap disabled:opacity-50">Send OTP</button>
                </div>
            </div>

            <!-- Step 4: Verify New Email -->
            <div x-show="step === 4" class="mt-4" style="display: none;">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">An OTP has been sent to your new email. Enter it below to complete the change.</p>
                <div class="flex space-x-4">
                    <input type="text" x-model="newOtp" placeholder="6-digit OTP" maxlength="6" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm">
                    <button @click="verifyNew" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium whitespace-nowrap disabled:opacity-50">Confirm Change</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Settings Section -->
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg mb-8 overflow-hidden" x-data="passwordSettings()">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">Change Password</h3>
        </div>
        <div class="p-6">
            <div x-show="step === 1">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">To change your password, we first need to verify it's you by sending an OTP to your email.</p>
                <button @click="initiate" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium disabled:opacity-50">
                    Initiate Password Change
                </button>
            </div>
            
            <div x-show="message" x-transition class="mt-4 p-4 rounded-md text-sm" :class="status === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'" x-text="message" style="display: none;"></div>

            <form @submit.prevent="verifyOtp" class="space-y-4 max-w-md mt-4" x-show="step === 2" style="display: none;">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">6-Digit OTP from Email</label>
                    <input type="text" x-model="formData.otp" required maxlength="6" placeholder="e.g. 123456" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 tracking-[0.25em] font-mono text-center">
                </div>
                <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium disabled:opacity-50">
                    Verify OTP
                </button>
            </form>

            <form @submit.prevent="submit" class="space-y-4 max-w-md mt-4" x-show="step === 3" style="display: none;">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">New Password</label>
                    <input type="password" x-model="formData.new_password" required minlength="8" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Confirm New Password</label>
                    <input type="password" x-model="formData.confirm_password" required minlength="8" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="••••••••">
                </div>
                <button type="submit" :disabled="loading" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm font-medium disabled:opacity-50">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- Danger Zone Section -->
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden border border-red-200 dark:border-red-900" x-data="deleteAccount()">
        <div class="px-6 py-5 border-b border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20">
            <h3 class="text-lg font-medium text-red-800 dark:text-red-400">Danger Zone</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
            
            <div x-show="message" x-transition class="mb-4 p-4 rounded-md text-sm bg-red-50 text-red-700" x-text="message" style="display: none;"></div>

            <div x-show="!confirming">
                <button @click="confirming = true" class="px-4 py-2 border border-red-300 text-red-700 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 text-sm font-medium transition-colors">
                    Delete Account
                </button>
            </div>

            <div x-show="confirming" style="display: none;" class="space-y-4 max-w-md p-4 border border-red-200 dark:border-red-800 rounded-md bg-red-50/50 dark:bg-red-900/10">
                <p class="text-sm font-medium text-red-800 dark:text-red-400">Are you absolutely sure? Please enter your password to confirm.</p>
                <input type="password" x-model="password" placeholder="Enter your password" class="w-full px-4 py-2 border border-red-300 dark:border-red-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-red-500 focus:border-red-500">
                <div class="flex space-x-3">
                    <button @click="submit" :disabled="loading" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium disabled:opacity-50">
                        Yes, delete my account
                    </button>
                    <button @click="confirming = false; password = ''" class="px-4 py-2 border border-slate-300 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function emailSettings() {
    return {
        step: 1,
        loading: false,
        message: '',
        status: '',
        currentOtp: '',
        newEmail: '',
        newOtp: '',

        async initiateChange() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/email/initiate') ?>', { method: 'POST' });
                const data = await res.json();
                if (res.ok) {
                    this.step = 2;
                    this.status = 'success';
                    this.message = data.message;
                } else {
                    this.status = 'error';
                    this.message = data.message || 'Error sending OTP.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        },

        async verifyCurrent() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/email/verify-current') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ otp: this.currentOtp })
                });
                const data = await res.json();
                if (res.ok) {
                    this.step = 3;
                    this.status = 'success';
                    this.message = '';
                } else {
                    this.status = 'error';
                    this.message = data.message || 'Invalid OTP.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        },

        async initiateNew() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/email/initiate-new') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ new_email: this.newEmail })
                });
                const data = await res.json();
                if (res.ok) {
                    this.step = 4;
                    this.status = 'success';
                    this.message = data.message;
                } else {
                    this.status = 'error';
                    this.message = data.message || Object.values(data.messages || {}).join(' ') || 'Error.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        },

        async verifyNew() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/email/verify-new') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ new_email: this.newEmail, otp: this.newOtp })
                });
                const data = await res.json();
                if (res.ok) {
                    this.step = 1;
                    this.status = 'success';
                    this.message = 'Email successfully updated! Reloading...';
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.status = 'error';
                    this.message = data.message || 'Invalid OTP.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        }
    }
}

function passwordSettings() {
    return {
        step: 1,
        formData: {
            new_password: '',
            confirm_password: '',
            otp: ''
        },
        loading: false,
        message: '',
        status: '',

        async initiate() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/password/initiate') ?>', { method: 'POST' });
                const data = await res.json();
                if (res.ok) {
                    this.step = 2;
                    this.status = 'success';
                    this.message = data.message;
                } else {
                    this.status = 'error';
                    this.message = data.message || 'Error sending OTP.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        },

        async verifyOtp() {
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/password/verify-otp') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ otp: this.formData.otp })
                });
                const data = await res.json();
                if (res.ok) {
                    this.step = 3;
                    this.status = 'success';
                    this.message = data.message;
                } else {
                    this.status = 'error';
                    this.message = data.message || 'Invalid OTP.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        },
        
        async submit() {
            if (this.formData.new_password !== this.formData.confirm_password) {
                this.status = 'error';
                this.message = 'New passwords do not match.';
                return;
            }

            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/password') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formData)
                });
                const data = await res.json();
                this.status = data.status || 'error';
                
                if (res.ok) {
                    this.message = data.message;
                    this.step = 1;
                    this.formData = { current_password: '', new_password: '', confirm_password: '', otp: '' };
                } else {
                    this.message = data.message || Object.values(data.messages || {}).join(' ') || 'Error.';
                }
            } catch (e) {
                this.status = 'error';
                this.message = 'Network error.';
            }
            this.loading = false;
        }
    }
}

function deleteAccount() {
    return {
        confirming: false,
        password: '',
        loading: false,
        message: '',
        
        async submit() {
            if (!this.password) return;
            this.loading = true;
            try {
                const res = await fetch('<?= base_url('api/settings/account') ?>', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ password: this.password })
                });
                const data = await res.json();
                if (res.ok) {
                    this.message = 'Account deleted. Redirecting...';
                    setTimeout(() => window.location.href = '<?= base_url('auth/login') ?>', 1500);
                } else {
                    this.message = data.message || 'Error.';
                }
            } catch (e) {
                this.message = 'Network error.';
            }
            this.loading = false;
        }
    }
}
</script>
<?= $this->endSection() ?>
