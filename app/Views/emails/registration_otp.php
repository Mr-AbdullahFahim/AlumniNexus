<?= $this->extend('emails/layout') ?>

<?= $this->section('content') ?>
    <h1>Verify Your Email Address</h1>
    <p>Hello <?= esc($name) ?>,</p>
    <p>Thank you for registering with AlumniNexus! To complete your registration, please enter the following One-Time Password (OTP) on the verification page:</p>
    
    <div style="text-align: center;">
        <div class="otp-code"><?= esc($otp) ?></div>
    </div>
    
    <p>This code will expire in 24 hours.</p>
    <p>If you did not create an account, no further action is required.</p>
    
    <p>Thanks,<br>The AlumniNexus Team</p>
<?= $this->endSection() ?>
