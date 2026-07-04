<?php

namespace App\Controllers;

use App\Libraries\JWTService;
use Exception;

class Home extends BaseController
{
    public function index()
    {
        $ctaText = 'Get Started';
        $ctaUrl = base_url('auth/login');
        $isLoggedIn = false;

        $token = $this->request->getCookie('access_token');
        if ($token) {
            try {
                $jwtService = new JWTService();
                $decoded = $jwtService->decodeToken($token);
                $isLoggedIn = true;
                $ctaText = 'Go to Dashboard';
                
                // Determine dashboard based on role
                switch ($decoded->role) {
                    case 1:
                        $ctaUrl = base_url('admin/dashboard');
                        break;
                    case 2:
                        $ctaUrl = base_url('alumni/dashboard');
                        break;
                    case 3:
                        $ctaUrl = base_url('student/dashboard');
                        break;
                    case 4:
                        $ctaUrl = base_url('sponsor/dashboard');
                        break;
                }
            } catch (Exception $e) {
                // Invalid token, default to login
            }
        }

        $data = [
            'title' => 'AlumniNexus - The Alumni Influencers Platform',
            'ctaText' => $ctaText,
            'ctaUrl' => $ctaUrl,
            'isLoggedIn' => $isLoggedIn
        ];

        return view('home', $data);
    }
}
