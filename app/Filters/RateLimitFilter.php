<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // Use IP address as the identifier. Default limit is 60 requests per minute.
        // We can pass limit in arguments: ['throttle:60,1'] = 60 requests per 1 minute
        $limit = 60;
        $seconds = 60;

        if (is_array($arguments) && count($arguments) >= 2) {
            $limit = (int)$arguments[0];
            $seconds = (int)$arguments[1];
        }

        // Restrict by IP address + URI. Hash it to avoid reserved characters in cache key (like / or :)
        $key = md5($request->getIPAddress() . '|' . $request->getUri()->getPath());

        if ($throttler->check($key, $limit, $seconds) === false) {
            return Services::response()
                ->setJSON(['status' => 'error', 'message' => 'Too many requests. Please try again later.'])
                ->setStatusCode(429); // HTTP_TOO_MANY_REQUESTS
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
