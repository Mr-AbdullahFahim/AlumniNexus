<?= $this->extend('emails/layout') ?>

<?= $this->section('content') ?>
    <h1>Reset Your Password</h1>
    <p>Hello,</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <a href="<?= esc($reset_link) ?>" class="btn">Reset Password</a>
            </td>
        </tr>
    </table>
    
    <p style="margin-top: 20px;">This password reset link will expire in 1 hour.</p>
    <p>If you did not request a password reset, no further action is required.</p>
    
    <p>Thanks,<br>The AlumniNexus Team</p>
<?= $this->endSection() ?>
