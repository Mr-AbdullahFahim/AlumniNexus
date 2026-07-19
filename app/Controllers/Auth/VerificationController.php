<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\EmailVerificationModel;

class VerificationController extends ResourceController
{
    public function verify()
    {
        helper('audit');
        $rules = [
            'email' => 'required|valid_email',
            'otp'   => 'required|exact_length[6]|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $otp = $this->request->getVar('otp');

        $verifModel = new EmailVerificationModel();
        $record = $verifModel->where('email', $email)->where('token', $otp)->first();

        if (!$record) {
            return $this->failNotFound('Invalid OTP or Email.');
        }

        if (strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('OTP has expired.');
        }

        $userModel = new UserModel();

        // If user_data exists, this is a new registration
        if (!empty($record['user_data'])) {
            $userData = json_decode($record['user_data'], true);
            $userData['email_verified_at'] = date('Y-m-d H:i:s');
            
            // Just in case it was inserted somehow in between
            if (!$userModel->where('email', $userData['email'])->first()) {
                $userModel->insert($userData);
                $newUserId = $userModel->getInsertID();
                log_activity('User Registered', 'users', $newUserId, null, null, $newUserId);
            }
            
            $verifModel->delete($record['id']);

            return $this->respond([
                'status'  => 'success',
                'message' => 'Email verified successfully. You can now log in.'
            ]);
        }

        // Fallback for any other verifications (e.g., if previously inserted into users)
        $user = $userModel->where('email', $record['email'])->first();

        if ($user) {
            if ($user['email_verified_at'] !== null) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Email is already verified.'
                ]);
            }

            $userModel->update($user['id'], [
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);

            // Delete token
            $verifModel->delete($record['id']);

            return $this->respond([
                'status'  => 'success',
                'message' => 'Email verified successfully. You can now log in.'
            ]);
        }

        return $this->failServerError('User not found or invalid request.');
    }
}
