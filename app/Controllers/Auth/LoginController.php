<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\JwtRefreshTokenModel;
use App\Libraries\JWTService;
use CodeIgniter\Cookie\Cookie;

class LoginController extends ResourceController
{
    public function index()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return $this->failUnauthorized('Invalid email or password.');
        }

        if (empty($user['email_verified_at'])) {
            return $this->failUnauthorized('Please verify your email address before logging in.');
        }

        if ($user['status'] !== 'approved') {
            return $this->failForbidden('Your account is pending admin approval or has been rejected/banned.');
        }

        $jwtService = new JWTService();
        $token = $jwtService->generateToken($user);

        // Handle Remember Me (e.g. longer refresh token)
        $rememberMe = $this->request->getVar('remember') === 'on' || $this->request->getVar('remember') === true;
        
        // Generate Refresh Token
        $refreshToken = bin2hex(random_bytes(64));
        $refreshModel = new JwtRefreshTokenModel();
        
        $refreshModel->insert([
            'user_id'    => $user['id'],
            'token'      => $refreshToken,
            'expires_at' => date('Y-m-d H:i:s', strtotime($rememberMe ? '+30 days' : '+1 day'))
        ]);

        // Set HttpOnly Cookie for JWT
        $cookie = new Cookie(
            'access_token',
            $token,
            [
                'max-age'  => 900, // 15 mins matching TTL
                'path'     => '/',
                'domain'   => '',
                'secure'   => false, // set to true in production if HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
        $this->response->setCookie($cookie);

        return $this->respond([
            'status'        => 'success',
            'message'       => 'Login successful',
            'refresh_token' => $refreshToken, // Send refresh token in body
            'user'          => [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role_id']
            ]
        ]);
    }

    public function logout()
    {
        // Token must be present via AuthFilter
        // We delete the HttpOnly cookie
        $this->response->deleteCookie('access_token');
        
        return $this->respond([
            'status'  => 'success',
            'message' => 'Logged out successfully.'
        ]);
    }

    public function refreshToken()
    {
        $token = $this->request->getVar('refresh_token');
        if (!$token) {
            return $this->failUnauthorized('Refresh token required.');
        }

        $refreshModel = new JwtRefreshTokenModel();
        $record = $refreshModel->where('token', $token)->where('is_revoked', false)->first();

        if (!$record || strtotime($record['expires_at']) < time()) {
            return $this->failUnauthorized('Invalid or expired refresh token.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($record['user_id']);

        if (!$user || $user['status'] !== 'approved') {
            return $this->failForbidden('User not active or approved.');
        }

        $jwtService = new JWTService();
        $newToken = $jwtService->generateToken($user);

        // Set new HttpOnly Cookie
        $cookie = new Cookie(
            'access_token',
            $newToken,
            ['max-age' => 900, 'path' => '/', 'secure' => false, 'httponly' => true, 'samesite' => 'Lax']
        );
        $this->response->setCookie($cookie);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Token refreshed successfully.'
        ]);
    }
}
