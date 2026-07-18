<?= $this->extend('emails/layout') ?>

<?= $this->section('content') ?>
    <h1>Confirm Your Request</h1>
    <p>Hello,</p>
    <p>We received a request to change the email address associated with your AlumniNexus account.</p>
    <p>To confirm this is you, please use the following One-Time Password (OTP) on the settings page:</p>
    
    <div style="text-align: center;">
        <div class="otp-code"><?= esc($otp) ?></div>
    </div>
    
    <p>This code will expire in 1 hour.</p>
    <p>If you did not request to change your email, please ignore this message or contact support if you have concerns.</p>
    
    <p>Thanks,<br>The AlumniNexus Team</p>
<?= $this->endSection() ?>
