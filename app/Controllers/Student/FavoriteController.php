<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FavoriteProfileModel;
use App\Models\UserModel;
use App\Models\ProfileModel;

class FavoriteController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data = [
            'title' => 'Favorite Profiles',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Favorite Profiles', 'url' => base_url('student/favorites')]
            ]
        ];

        return view('student/favorites', $data);
    }

    public function list()
    {
        $studentId = $this->request->user->sub;
        
        $db = \Config\Database::connect();
        $builder = $db->table('favorite_profiles fp');
        $builder->select('u.id, u.name, p.photo_url, p.company, p.position, p.department, p.graduation_year, p.industry, fp.created_at as favorited_at');
        $builder->join('users u', 'u.id = fp.alumni_id');
        $builder->join('profiles p', 'p.user_id = u.id', 'left');
        $builder->where('fp.student_id', $studentId);
        $builder->where('u.role_id', 2);
        $builder->where('u.status', 'approved');
        $builder->orderBy('fp.created_at', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        return $this->respond([
            'status' => 'success',
            'data' => $results
        ]);
    }

    public function toggle()
    {
        $studentId = $this->request->user->sub;
        $alumniId = $this->request->getVar('alumni_id');

        if (!$alumniId) {
            return $this->failValidationErrors('Alumni ID is required.');
        }

        // Verify the target is an alumni
        $userModel = new UserModel();
        $alumni = $userModel->where('id', $alumniId)->where('role_id', 2)->first();
        if (!$alumni) {
            return $this->failNotFound('Alumni not found.');
        }

        $favModel = new FavoriteProfileModel();
        
        $existing = $favModel->where('student_id', $studentId)
                             ->where('alumni_id', $alumniId)
                             ->first();
                             
        if ($existing) {
            // Unfavorite
            $favModel->where('student_id', $studentId)
                     ->where('alumni_id', $alumniId)
                     ->delete();
            return $this->respond(['status' => 'success', 'message' => 'Profile removed from favorites.', 'is_favorite' => false]);
        } else {
            // Favorite
            $favModel->insert([
                'student_id' => $studentId,
                'alumni_id' => $alumniId
            ]);
            return $this->respond(['status' => 'success', 'message' => 'Profile added to favorites.', 'is_favorite' => true]);
        }
    }
}
