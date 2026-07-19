<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class DesktopOnlyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $agent = $request->getUserAgent();
        if ($agent->isMobile()) {
            $isApi = strpos($request->getPath(), 'api/') === 0;
            if ($isApi) {
                return Services::response()
                    ->setJSON(['status' => 'error', 'message' => 'This section is only accessible from a desktop device.'])
                    ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
            }
            $html = '<!DOCTYPE html>
<html>
<head>
    <title>Access Restricted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <script>
        alert("The Analytics Dashboard, Users, and Activity Logs sections are only accessible from a desktop device.");
        window.location.href = "' . base_url('/') . '";
    </script>
</body>
</html>';
            return Services::response()->setBody($html);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
