<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Admin Analytics Dashboard',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Analytics Dashboard', 'url' => base_url('admin/dashboard')]
            ]
        ];

        return view('admin/dashboard', $data);
    }
}
