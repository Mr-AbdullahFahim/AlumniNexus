<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\ProfileModel;

class DirectoryController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data = [
            'title' => 'Alumni Directory',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Dashboard', 'url' => base_url('dashboard')],
                ['name' => 'Alumni Directory', 'url' => base_url('alumni/directory')]
            ]
        ];

        return view('alumni/directory', $data);
    }

    public function apiList()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        $builder->select('u.id, u.name, p.photo_url, p.company, p.position, p.department, p.graduation_year, p.industry');
        $builder->join('profiles p', 'p.user_id = u.id', 'left');
        
        // Only fetch approved alumni (role 2)
        $builder->where('u.role_id', 2);
        $builder->where('u.status', 'approved');

        // Exclude the currently logged-in user from the directory
        if (isset($this->request->user->sub)) {
            $builder->where('u.id !=', $this->request->user->sub);
        }

        // Filters
        $search = $this->request->getGet('search');
        $department = $this->request->getGet('department');
        $graduationYear = $this->request->getGet('graduation_year');
        $industry = $this->request->getGet('industry');
        
        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.name', $search)
                ->orLike('p.company', $search)
                ->orLike('p.position', $search)
                ->groupEnd();
        }

        if (!empty($department)) {
            $builder->where('p.department', $department);
        }

        if (!empty($graduationYear)) {
            $builder->where('p.graduation_year', $graduationYear);
        }

        if (!empty($industry)) {
            $builder->where('p.industry', $industry);
        }

        // Sorting
        $sortBy = $this->request->getGet('sort_by') ?? 'newest';
        if ($sortBy === 'a-z') {
            $builder->orderBy('u.name', 'ASC');
        } elseif ($sortBy === 'z-a') {
            $builder->orderBy('u.name', 'DESC');
        } else { // newest
            $builder->orderBy('u.created_at', 'DESC');
        }

        // Pagination
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 12);
        $offset = ($page - 1) * $limit;

        // Clone builder for total count before applying limit
        $countBuilder = clone $builder;
        $totalRows = $countBuilder->countAllResults();

        $builder->limit($limit, $offset);
        $results = $builder->get()->getResultArray();

        return $this->respond([
            'status' => 'success',
            'data' => $results,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total_rows' => $totalRows,
                'total_pages' => ceil($totalRows / $limit)
            ]
        ]);
    }

    public function publicProfile($id)
    {
        $userModel = new UserModel();
        $user = $userModel->where('role_id', 2)
                          ->where('status', 'approved')
                          ->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $profileModel = new ProfileModel();
        $profile = $profileModel->where('user_id', $id)->first();

        // Decode JSON fields and handle view count
        if ($profile) {
            $profile['skills'] = $profile['skills'] ? json_decode($profile['skills'], true) : [];
            $profile['social_links'] = $profile['social_links'] ? json_decode($profile['social_links'], true) : [];
            
            // Increment view count if viewer is not the profile owner and hasn't viewed recently
            $viewerId = $this->request->user->sub ?? 'anon';
            if ($viewerId != $id) {
                $session = session();
                
                // Use a session key specific to this viewer
                $sessionKey = 'viewed_profiles_' . $viewerId;
                $viewedProfiles = $session->get($sessionKey) ?? [];
                
                $lastViewed = $viewedProfiles[$id] ?? 0;
                $now = time();
                
                // If it's been more than 24 hours (86400 seconds) since last view
                if (($now - $lastViewed) > 86400) {
                    $db = \Config\Database::connect();
                    $db->table('profiles')->where('id', $profile['id'])->increment('view_count');
                    $profile['view_count']++; // Update local variable for the view
                    
                    $viewedProfiles[$id] = $now;
                    $session->set($sessionKey, $viewedProfiles);
                }
            }
        }

        $currentCycle = (new \App\Models\BlindBidModel())->getCurrentCycleDate();
        $sponsorTotalThisCycle = 0;
        
        if (isset($this->request->user) && $this->request->user->role == 4) {
            $sponsorTotalThisCycle = (new \App\Models\SponsorshipModel())->getSponsorTotalForCycle($this->request->user->sub, $id, $currentCycle);
        }

        $isFavorited = false;
        if (isset($this->request->user) && $this->request->user->role == 3) {
            $favModel = new \App\Models\FavoriteProfileModel();
            $existing = $favModel->where('student_id', $this->request->user->sub)
                                 ->where('alumni_id', $id)
                                 ->first();
            if ($existing) {
                $isFavorited = true;
            }
        }
        
        $featuredModel = new \App\Models\FeaturedAlumniModel();
        $isFeatured = $featuredModel->where('alumni_id', $id)
                                    ->where('featured_date', $currentCycle)
                                    ->countAllResults() > 0;

        $data = [
            'title' => $user['name'] . ' - Profile',
            'user' => $user,
            'general' => $profile,
            'degrees' => (new \App\Models\DegreeModel())->where('user_id', $id)->findAll(),
            'employment' => (new \App\Models\EmploymentHistoryModel())->where('user_id', $id)->orderBy('is_current', 'DESC')->orderBy('end_date', 'DESC')->findAll(),
            'certifications' => (new \App\Models\CertificationModel())->where('user_id', $id)->findAll(),
            'licences' => (new \App\Models\LicenceModel())->where('user_id', $id)->findAll(),
            'courses' => (new \App\Models\ProfessionalCourseModel())->where('user_id', $id)->findAll(),
            'projects' => (new \App\Models\ProjectModel())->where('user_id', $id)->findAll(),
            'achievements' => (new \App\Models\AchievementModel())->where('user_id', $id)->findAll(),
            'hasReachedMonthlyWinLimit' => (new \App\Models\MonthlyWinningStatsModel())->hasReachedMonthlyLimit($id),
            'viewer' => $this->request->user ?? null,
            'currentCycle' => $currentCycle,
            'sponsorTotalThisCycle' => $sponsorTotalThisCycle,
            'isFavorited' => $isFavorited,
            'isFeatured' => $isFeatured,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Alumni Directory', 'url' => base_url('alumni/directory')],
                ['name' => $user['name'], 'url' => current_url()]
            ]
        ];

        return view('alumni/public_profile', $data);
    }
}
