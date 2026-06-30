<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class ProfileController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($this->request->user->sub);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Alumni Dashboard', 'url' => base_url('alumni/dashboard')],
                ['name' => 'My Profile', 'url' => base_url('alumni/profile')]
            ]
        ];

        return view('alumni/profile', $data);
    }

    public function getData()
    {
        $userId = $this->request->user->sub; // Provided by JWTAuthFilter

        $profileModel = new \App\Models\ProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();
        
        if (!$profile) {
            // Create a default profile if none exists
            $profileModel->insert(['user_id' => $userId]);
            $profile = $profileModel->where('user_id', $userId)->first();
        }

        // Decode JSON fields
        $profile['skills'] = $profile['skills'] ? json_decode($profile['skills'], true) : [];
        $profile['social_links'] = $profile['social_links'] ? json_decode($profile['social_links'], true) : [];

        $data = [
            'general' => $profile,
            'degrees' => (new \App\Models\DegreeModel())->where('user_id', $userId)->findAll(),
            'employment' => (new \App\Models\EmploymentHistoryModel())->where('user_id', $userId)->findAll(),
            'certifications' => (new \App\Models\CertificationModel())->where('user_id', $userId)->findAll(),
            'licences' => (new \App\Models\LicenceModel())->where('user_id', $userId)->findAll(),
            'courses' => (new \App\Models\ProfessionalCourseModel())->where('user_id', $userId)->findAll(),
            'projects' => (new \App\Models\ProjectModel())->where('user_id', $userId)->findAll(),
            'achievements' => (new \App\Models\AchievementModel())->where('user_id', $userId)->findAll(),
        ];

        return $this->respond($data);
    }

    public function updateGeneral()
    {
        $userId = $this->request->user->sub;
        $profileModel = new \App\Models\ProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        $data = $this->request->getJSON(true);
        
        // Encode JSON arrays
        if (isset($data['skills'])) $data['skills'] = json_encode($data['skills']);
        if (isset($data['social_links'])) $data['social_links'] = json_encode($data['social_links']);
        
        $profileModel->update($profile['id'], $data);
        
        return $this->respond(['status' => 'success', 'message' => 'Profile updated successfully']);
    }

    public function uploadPhoto()
    {
        $userId = $this->request->user->sub;
        $file = $this->request->getFile('photo');
        
        if (!$file || !$file->isValid()) {
            return $this->failValidationErrors('Invalid file uploaded.');
        }

        // Validate type and size
        if (!$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Move to public/uploads/profiles
            $file->move(FCPATH . 'uploads/profiles', $newName);
            
            $photoUrl = base_url('uploads/profiles/' . $newName);
            
            $profileModel = new \App\Models\ProfileModel();
            $profile = $profileModel->where('user_id', $userId)->first();
            
            if ($profile) {
                // Delete existing photo if it exists
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

    // Generic save method for relation models
    private function saveRelation($modelName)
    {
        $userId = $this->request->user->sub;
        $data = $this->request->getJSON(true);
        $data['user_id'] = $userId;
        
        // Data casting and cleanup
        if (isset($data['is_current'])) {
            $data['is_current'] = $data['is_current'] ? 1 : 0;
        }

        foreach (['start_date', 'end_date'] as $dateField) {
            if (isset($data[$dateField]) && trim($data[$dateField]) === '') {
                $data[$dateField] = null;
            }
        }

        $modelClass = "\\App\\Models\\$modelName";
        $model = new $modelClass();

        if (isset($data['id']) && !empty($data['id'])) {
            // Update
            $record = $model->find($data['id']);
            if (!$record || $record['user_id'] != $userId) return $this->failForbidden();
            $model->update($data['id'], $data);
        } else {
            // Create
            $model->insert($data);
        }

        if ($model->errors()) {
            return $this->failValidationErrors($model->errors());
        }

        return $this->respond(['status' => 'success', 'message' => 'Saved successfully']);
    }

    private function deleteRelation($modelName, $id)
    {
        $userId = $this->request->user->sub;
        $modelClass = "\\App\\Models\\$modelName";
        $model = new $modelClass();
        
        $record = $model->find($id);
        if (!$record || $record['user_id'] != $userId) return $this->failForbidden();
        
        $model->delete($id);
        return $this->respondDeleted(['status' => 'success', 'message' => 'Deleted successfully']);
    }

    // Explicit Endpoints mapped by Routes
    public function saveDegree() { return $this->saveRelation('DegreeModel'); }
    public function deleteDegree($id) { return $this->deleteRelation('DegreeModel', $id); }

    public function saveEmployment() { return $this->saveRelation('EmploymentHistoryModel'); }
    public function deleteEmployment($id) { return $this->deleteRelation('EmploymentHistoryModel', $id); }

    public function saveCertification() { return $this->saveRelation('CertificationModel'); }
    public function deleteCertification($id) { return $this->deleteRelation('CertificationModel', $id); }

    public function saveLicence() { return $this->saveRelation('LicenceModel'); }
    public function deleteLicence($id) { return $this->deleteRelation('LicenceModel', $id); }

    public function saveCourse() { return $this->saveRelation('ProfessionalCourseModel'); }
    public function deleteCourse($id) { return $this->deleteRelation('ProfessionalCourseModel', $id); }

    public function saveProject() { return $this->saveRelation('ProjectModel'); }
    public function deleteProject($id) { return $this->deleteRelation('ProjectModel', $id); }

    public function saveAchievement() { return $this->saveRelation('AchievementModel'); }
    public function deleteAchievement($id) { return $this->deleteRelation('AchievementModel', $id); }
}
