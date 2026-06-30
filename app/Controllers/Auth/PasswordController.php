<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\PasswordResetModel;

class PasswordController extends ResourceController
{
    public function forgot()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $userModel = new UserModel();
        
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $resetModel = new PasswordResetModel();
            $token = bin2hex(random_bytes(32));

            // Remove existing reset tokens for this email
            $resetModel->where('email', $email)->delete();

            $resetModel->insert([
                'email'      => $email,
                'token'      => $token,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ]);

            // In a real application, you would send an email here with a link like:
            // base_url('/auth/reset-password?token=' . $token . '&email=' . urlencode($email))
        }

        // Always return success even if email not found to prevent user enumeration
        return $this->respond([
            'status'  => 'success',
            'message' => 'If your email is registered, you will receive a password reset link shortly.'
        ]);
    }

    public function reset()
    {
        $rules = [
            'email'            => 'required|valid_email',
            'token'            => 'required',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');
        $password = $this->request->getVar('password');

        $resetModel = new PasswordResetModel();
        $record = $resetModel->where('email', $email)->where('token', $token)->first();

        if (!$record || strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Invalid or expired reset token.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $userModel->update($user['id'], [
                'password_hash' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            
            // Delete the token
            $resetModel->delete($record['id']);

            return $this->respond([
                'status'  => 'success',
                'message' => 'Password reset successfully. You can now login.'
            ]);
        }

        return $this->failServerError('Unable to reset password.');
    }
}
