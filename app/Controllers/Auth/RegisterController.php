<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\EmailVerificationModel;

class RegisterController extends ResourceController
{
    public function index()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'role_id'          => 'required|integer|in_list[2,3,4]', // Assuming 1 is Admin, others are Alumni, Student, Sponsor
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $emailVerifModel = new EmailVerificationModel();

        // Check if the user already exists in the users table
        $existingUser = $userModel->where('email', $this->request->getVar('email'))->first();
        if ($existingUser) {
            // If they are verified, block registration
            if ($existingUser['email_verified_at'] !== null) {
                return $this->failResourceExists('An account with this email already exists.');
            } else {
                // Clean up any legacy unverified user records from the users table 
                // so they can use the new flow properly
                $userModel->delete($existingUser['id']);
            }
        }

        $userData = [
            'name'          => $this->request->getVar('name'),
            'email'         => $this->request->getVar('email'),
            'password_hash' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id'       => $this->request->getVar('role_id'),
            'status'        => 'approved', // Automatically approved
        ];

        // Replace any existing unverified records for this email
        $emailVerifModel->where('email', $userData['email'])->delete();

        // Generate 6-digit OTP
        $otp = (string)random_int(100000, 999999);
        $inserted = $emailVerifModel->insert([
            'email'      => $userData['email'],
            'token'      => $otp,
            'user_data'  => json_encode($userData),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
        ]);

        if ($inserted) {

            // Send Email
            $emailService = \Config\Services::email();
            $emailService->setTo($userData['email']);
            $emailService->setSubject('Verify Your Email Address - AlumniNexus');
            
            $message = view('emails/registration_otp', [
                'name' => $userData['name'],
                'otp'  => $otp
            ]);
            
            $emailService->setMessage($message);
            if (!$emailService->send()) {
                return $this->failServerError('Registration successful, but we could not send the OTP email. Please ensure email settings are correct.');
            }

            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Registration successful. Please check your email for the OTP to verify your account.'
            ]);
        }

        return $this->failServerError('Failed to create account.');
    }
}
