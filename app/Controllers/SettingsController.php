<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\EmailVerificationModel;

class SettingsController extends ResourceController
{
    public function index()
    {
        // Get user data
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        $profileModel = new \App\Models\ProfileModel();
        $profile = $profileModel->where('user_id', $this->request->user->sub)->first();

        return view('settings/index', ['user' => $user, 'profile' => $profile]);
    }

    private function generateOtpAndSendEmail($emailAddress, $subject, $viewName, $userName = 'User')
    {
        $verifModel = new EmailVerificationModel();
        
        // Cleanup old tokens
        $verifModel->where('email', $emailAddress)->delete();

        $otp = (string)random_int(100000, 999999);
        $verifModel->insert([
            'email'      => $emailAddress,
            'token'      => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
        ]);

        $emailService = \Config\Services::email();
        $emailService->setTo($emailAddress);
        $emailService->setSubject($subject);
        $message = view($viewName, ['otp' => $otp, 'name' => $userName]);
        $emailService->setMessage($message);
        return $emailService->send();
    }

    // Step 1: Initiate changing email (Send OTP to current email)
    public function initiateEmailChange()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        if ($this->generateOtpAndSendEmail($user['email'], 'Confirm Your Request - AlumniNexus', 'emails/email_change_otp', $user['name'])) {
            return $this->respond(['status' => 'success', 'message' => 'OTP sent to your current email.']);
        }

        return $this->failServerError('Failed to send email.');
    }

    // Step 2: Verify current email OTP
    public function verifyCurrentEmail()
    {
        $rules = [
            'otp' => 'required|exact_length[6]|numeric'
        ];
        if (!$this->validate($rules)) return $this->failValidationErrors($this->validator->getErrors());

        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        $verifModel = new EmailVerificationModel();
        $record = $verifModel->where('email', $user['email'])->where('token', $this->request->getVar('otp'))->first();

        if (!$record || strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Invalid or expired OTP.');
        }

        // OTP is valid. Delete it.
        $verifModel->delete($record['id']);

        return $this->respond(['status' => 'success', 'message' => 'Current email verified.']);
    }

    // Step 3: Initiate new email verification
    public function initiateNewEmail()
    {
        $rules = [
            'new_email' => 'required|valid_email|is_unique[users.email]'
        ];
        if (!$this->validate($rules)) return $this->failValidationErrors($this->validator->getErrors());

        $newEmail = $this->request->getVar('new_email');
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        if ($this->generateOtpAndSendEmail($newEmail, 'Verify New Email - AlumniNexus', 'emails/registration_otp', $user['name'])) {
            return $this->respond(['status' => 'success', 'message' => 'OTP sent to your new email.']);
        }
        return $this->failServerError('Failed to send email.');
    }

    // Step 4: Verify new email OTP and update DB
    public function verifyNewEmail()
    {
        $rules = [
            'new_email' => 'required|valid_email|is_unique[users.email]',
            'otp'       => 'required|exact_length[6]|numeric'
        ];
        if (!$this->validate($rules)) return $this->failValidationErrors($this->validator->getErrors());

        $newEmail = $this->request->getVar('new_email');
        $otp = $this->request->getVar('otp');

        $verifModel = new EmailVerificationModel();
        $record = $verifModel->where('email', $newEmail)->where('token', $otp)->first();

        if (!$record || strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Invalid or expired OTP.');
        }

        // Valid! Update the user's email.
        $userModel = new UserModel();
        $userModel->update($this->request->user->sub, ['email' => $newEmail]);
        
        $verifModel->delete($record['id']);

        return $this->respond(['status' => 'success', 'message' => 'Email updated successfully.']);
    }

    public function initiatePasswordChange()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        if ($this->generateOtpAndSendEmail($user['email'], 'Confirm Password Change - AlumniNexus', 'emails/email_change_otp', $user['name'])) {
            return $this->respond(['status' => 'success', 'message' => 'OTP sent to your email to confirm password change.']);
        }
        return $this->failServerError('Failed to send email.');
    }

    public function verifyPasswordOtp()
    {
        $rules = [
            'otp' => 'required|exact_length[6]|numeric'
        ];
        if (!$this->validate($rules)) return $this->failValidationErrors($this->validator->getErrors());

        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        $verifModel = new EmailVerificationModel();
        $record = $verifModel->where('email', $user['email'])->where('token', $this->request->getVar('otp'))->first();

        if (!$record || strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Invalid or expired OTP.');
        }

        // Return success so frontend knows the OTP is valid and can proceed to ask for the new password.
        return $this->respond(['status' => 'success', 'message' => 'OTP verified. Please enter your new password.']);
    }

    public function updatePassword()
    {
        $rules = [
            'new_password'     => 'required|min_length[8]|strong_password',
            'confirm_password' => 'required|matches[new_password]',
            'otp'              => 'required|exact_length[6]|numeric'
        ];
        if (!$this->validate($rules)) return $this->failValidationErrors($this->validator->getErrors());

        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        $verifModel = new EmailVerificationModel();
        $record = $verifModel->where('email', $user['email'])->where('token', $this->request->getVar('otp'))->first();

        if (!$record || strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Invalid or expired OTP.');
        }

        $userModel->update($user['id'], [
            'password_hash' => password_hash($this->request->getVar('new_password'), PASSWORD_BCRYPT, ['cost' => 12])
        ]);

        $verifModel->delete($record['id']);

        return $this->respond(['status' => 'success', 'message' => 'Password updated successfully.']);
    }

    public function deleteAccount()
    {
        $rules = ['password' => 'required'];
        if (!$this->validate($rules)) return $this->failValidationErrors($this->validator->getErrors());

        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        if (!password_verify($this->request->getVar('password'), $user['password_hash'])) {
            return $this->failUnauthorized('Password is incorrect.');
        }

        // Hard delete the user
        $userModel->delete($user['id']);

        return $this->respondDeleted(['status' => 'success', 'message' => 'Account deleted permanently.']);
    }

    public function updatePhoto()
    {
        $userId = $this->request->user->sub;
        $file = $this->request->getFile('photo');
        
        if (!$file || !$file->isValid()) {
            return $this->failValidationErrors('Invalid or missing file.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/profiles', $newName);
        
        $photoUrl = base_url('uploads/profiles/' . $newName);
        
        $profileModel = new \App\Models\ProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();
        
        if ($profile) {
            // Optionally delete the old file from server here
            $profileModel->update($profile['id'], ['photo_url' => $photoUrl]);
        } else {
            $profileModel->insert([
                'user_id' => $userId,
                'photo_url' => $photoUrl
            ]);
        }
        
        return $this->respond(['status' => 'success', 'message' => 'Photo updated successfully.', 'photo_url' => $photoUrl]);
    }
}
