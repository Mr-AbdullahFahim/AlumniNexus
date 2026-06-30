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
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'role_id'          => 'required|integer|in_list[2,3,4]', // Assuming 1 is Admin, others are Alumni, Student, Sponsor
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $emailVerifModel = new EmailVerificationModel();

        $userData = [
            'name'          => $this->request->getVar('name'),
            'email'         => $this->request->getVar('email'),
            'password_hash' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id'       => $this->request->getVar('role_id'),
            'status'        => 'pending', // Requires admin approval
        ];

        $userId = $userModel->insert($userData);

        if ($userId) {
            // Generate email verification token
            $token = bin2hex(random_bytes(32));
            $emailVerifModel->insert([
                'email'      => $userData['email'],
                'token'      => $token,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
            ]);

            // In a real app, send email here. For now we just return success.
            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Registration successful. Please check your email to verify your account.'
            ]);
        }

        return $this->failServerError('Failed to create account.');
    }
}
