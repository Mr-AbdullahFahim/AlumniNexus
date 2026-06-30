<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\EmailVerificationModel;

class VerificationController extends ResourceController
{
    public function verify($token = null)
    {
        if (!$token) {
            return $this->failValidationError('Missing verification token.');
        }

        $verifModel = new EmailVerificationModel();
        $record = $verifModel->where('token', $token)->first();

        if (!$record) {
            return $this->failNotFound('Invalid verification token.');
        }

        if (strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Verification token has expired.');
        }

        $userModel = new UserModel();
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
                'message' => 'Email verified successfully. Awaiting admin approval to login.'
            ]);
        }

        return $this->failServerError('User not found.');
    }
}
