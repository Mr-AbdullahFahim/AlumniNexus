<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    protected function redirectIfLoggedIn()
    {
        $token = $this->request->getCookie('access_token');
        if ($token) {
            try {
                $jwtService = new \App\Libraries\JWTService();
                $jwtService->decodeToken($token);
                return redirect()->to('/');
            } catch (\Exception $e) {
                // Invalid token, continue to auth page
            }
        }
        return null;
    }

    public function login()
    {
        if ($redirect = $this->redirectIfLoggedIn()) return $redirect;
        return view('auth/login');
    }

    public function register()
    {
        if ($redirect = $this->redirectIfLoggedIn()) return $redirect;
        return view('auth/register');
    }

    public function forgotPassword()
    {
        if ($redirect = $this->redirectIfLoggedIn()) return $redirect;
        return view('auth/forgot_password');
    }

    public function resetPassword()
    {
        if ($redirect = $this->redirectIfLoggedIn()) return $redirect;
        return view('auth/reset_password');
    }
}
