<?php

namespace App\Controllers\Student;

use App\Controllers\Alumni\ProfileController as AlumniProfileController;
use App\Models\UserModel;

class ProfileController extends AlumniProfileController
{
    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Favorite Profiles', 'url' => base_url('student/favorites')],
                ['name' => 'My Profile', 'url' => base_url('student/profile')]
            ]
        ];

        return view('student/profile', $data);
    }
}
