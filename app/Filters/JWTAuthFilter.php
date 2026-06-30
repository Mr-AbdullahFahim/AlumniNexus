<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JWTService;
use Exception;
use Config\Services;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $jwtService = new JWTService();
        $token = null;

        // 1. Try to get token from HttpOnly Cookie using Request object
        $token = $request->getCookie('access_token');

        // 2. Fallback to Authorization Header (For mobile/API clients)
        if (!$token) {
            $header = $request->getHeaderLine('Authorization');
            if (!empty($header) && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        $isApi = strpos($request->getPath(), 'api/') === 0;

        if (!$token) {
            if ($isApi) {
                return Services::response()
                    ->setJSON(['status' => 'error', 'message' => 'Unauthorized. Missing token.'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
            return redirect()->to('/auth/login');
        }

        try {
            $decoded = $jwtService->decodeToken($token);
            $request->user = $decoded;

            if ($decoded->status !== 'approved') {
                if ($isApi) {
                    return Services::response()
                        ->setJSON(['status' => 'error', 'message' => 'Account not approved yet.'])
                        ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
                }
                return redirect()->to('/auth/login')->with('error', 'Account pending approval.');
            }

        } catch (Exception $e) {
            if ($isApi) {
                return Services::response()
                    ->setJSON(['status' => 'error', 'message' => 'Unauthorized. Invalid or expired token.'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
            return redirect()->to('/auth/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
