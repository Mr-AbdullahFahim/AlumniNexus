<?php

namespace App\Controllers\Sponsor;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\SponsorshipModel;

class ProfileController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Dashboard', 'url' => base_url('sponsor/dashboard')],
                ['name' => 'My Profile', 'url' => base_url('sponsor/profile')]
            ]
        ];

        return view('sponsor/profile', $data);
    }

    public function getData()
    {
        $userId = $this->request->user->sub;

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $profileModel = new ProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();
        
        if (!$profile) {
            $profileModel->insert(['user_id' => $userId]);
            $profile = $profileModel->where('user_id', $userId)->first();
        }

        $sponsorshipModel = new SponsorshipModel();
        $history = $sponsorshipModel->getSponsorHistory($userId);

        $data = [
            'user' => [
                'name' => $user['name'],
                'email' => $user['email']
            ],
            'general' => $profile,
            'history' => $history
        ];

        return $this->respond($data);
    }

    public function updateGeneral()
    {
        $userId = $this->request->user->sub;
        
        $data = $this->request->getJSON(true);
        
        if (isset($data['name']) && !empty(trim($data['name']))) {
            $userModel = new UserModel();
            $userModel->update($userId, ['name' => trim($data['name'])]);
        }
        
        return $this->respond(['status' => 'success', 'message' => 'Profile updated successfully']);
    }

    public function uploadPhoto()
    {
        $userId = $this->request->user->sub;
        $file = $this->request->getFile('photo');
        
        if (!$file || !$file->isValid()) {
            return $this->failValidationErrors('Invalid file uploaded.');
        }

        if (!$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles', $newName);
            
            $photoUrl = base_url('uploads/profiles/' . $newName);
            
            $profileModel = new ProfileModel();
            $profile = $profileModel->where('user_id', $userId)->first();
            
            if ($profile) {
                if (!empty($profile['photo_url'])) {
                    $oldFilename = basename(parse_url($profile['photo_url'], PHP_URL_PATH));
                    $oldFilePath = FCPATH . 'uploads/profiles/' . $oldFilename;
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $profileModel->update($profile['id'], ['photo_url' => $photoUrl]);
            } else {
                $profileModel->insert(['user_id' => $userId, 'photo_url' => $photoUrl]);
            }
            
            return $this->respond(['status' => 'success', 'photo_url' => $photoUrl, 'message' => 'Photo uploaded successfully']);
        }
        
        return $this->failServerError('Could not move uploaded file.');
    }
}
