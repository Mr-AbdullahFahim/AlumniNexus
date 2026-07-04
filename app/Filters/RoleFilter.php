<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RoleFilter implements FilterInterface
{
    /**
     * @param RequestInterface $request
     * @param array|null       $arguments e.g. ['2'] or ['2', '4'] (representing allowed role IDs)
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // $request->user is populated by JWTAuthFilter
        $user = $request->user ?? null;
        
        $isApi = strpos($request->getPath(), 'api/') === 0;

        if (!$user || !isset($user->role)) {
            if ($isApi) {
                return Services::response()
                    ->setJSON(['status' => 'error', 'message' => 'Unauthorized. Missing role information.'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
            return redirect()->to('/auth/login')->with('error', 'Unauthorized access.');
        }

        // If no arguments were passed, assume unrestricted (though filter shouldn't really be used then)
        if (empty($arguments)) {
            return;
        }

        // Check if the user's role is in the allowed roles list
        if (!in_array((string)$user->role, $arguments, true)) {
            if ($isApi) {
                return Services::response()
                    ->setJSON(['status' => 'error', 'message' => 'Forbidden. Insufficient permissions.'])
                    ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
            }
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
