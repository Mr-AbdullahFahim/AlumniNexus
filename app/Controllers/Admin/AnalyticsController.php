<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AnalyticsController extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Helper to apply common filters
     */
    private function applyFilters($builder, $tableName = null)
    {
        $request = \Config\Services::request();
        $industry = $request->getGet('industry');
        $eduStartYear = $request->getGet('edu_start_year');
        $eduEndYear = $request->getGet('edu_end_year');
        $workStartYear = $request->getGet('work_start_year');
        $workEndYear = $request->getGet('work_end_year');

        $hasProfileFilter = !empty($industry);
        $hasEduFilter = !empty($eduStartYear) || !empty($eduEndYear);
        $hasWorkFilter = !empty($workStartYear) || !empty($workEndYear);

        // If the main table is not 'users', we assume it has a 'user_id' or it is 'profiles'
        $userColumn = ($tableName === 'profiles') ? 'profiles.user_id' : (($tableName === 'users') ? 'users.id' : $tableName . '.user_id');

        if ($hasProfileFilter) {
            if ($tableName !== 'profiles') {
                $builder->join('profiles', 'profiles.user_id = ' . $userColumn, 'left');
            }
            if (!empty($industry)) {
                $builder->like('profiles.industry', $industry);
            }
        }

        if ($hasEduFilter) {
            $builder->join('degrees', 'degrees.user_id = ' . $userColumn, 'left');
            if (!empty($eduStartYear)) {
                $builder->where('YEAR(degrees.start_date) >=', $eduStartYear);
            }
            if (!empty($eduEndYear)) {
                $builder->where('YEAR(degrees.end_date) <=', $eduEndYear);
            }
        }

        if ($hasWorkFilter) {
            if ($tableName !== 'employment_history') {
                $builder->join('employment_history as eh_filter', 'eh_filter.user_id = ' . $userColumn, 'left');
            } else {
                // If it's already employment_history, just use its own columns
            }
            
            $ehTable = ($tableName === 'employment_history') ? 'employment_history' : 'eh_filter';
            
            if (!empty($workStartYear)) {
                $builder->where("YEAR({$ehTable}.start_date) >=", $workStartYear);
            }
            if (!empty($workEndYear)) {
                $builder->where("YEAR({$ehTable}.end_date) <=", $workEndYear);
            }
        }
        
        return $builder;
    }

    // 1. Top Certifications (Bar Chart)
    public function topCertifications()
    {
        $builder = $this->db->table('certifications');
        $builder->select('certifications.name as label, COUNT(certifications.id) as count');
        $builder->where('certifications.deleted_at', null);
        $this->applyFilters($builder, 'certifications');
        $builder->groupBy('certifications.name');
        $builder->orderBy('count', 'DESC');
        $builder->limit(10);
        $result = $builder->get()->getResultArray();

        return $this->respond($this->formatChartData($result, 'Certifications'));
    }

    // 2. Emerging Career Pathways (Doughnut Chart)
    public function emergingPathways()
    {
        $builder = $this->db->table('employment_history');
        $builder->select('employment_history.position as label, COUNT(employment_history.id) as count');
        $builder->where('employment_history.is_current', 1);
        $builder->where('employment_history.deleted_at', null);
        $this->applyFilters($builder, 'employment_history');
        $builder->groupBy('employment_history.position');
        $builder->orderBy('count', 'DESC');
        $builder->limit(8);
        $result = $builder->get()->getResultArray();

        return $this->respond($this->formatChartData($result, 'Current Roles'));
    }

    // 3. Industry Distribution (Pie Chart)
    public function industryDistribution()
    {
        $builder = $this->db->table('profiles');
        $builder->select('profiles.industry as label, COUNT(profiles.id) as count');
        $builder->where('profiles.industry !=', null);
        $builder->where('profiles.industry !=', '');
        $this->applyFilters($builder, 'profiles');
        $builder->groupBy('profiles.industry');
        $builder->orderBy('count', 'DESC');
        $builder->limit(8);
        $result = $builder->get()->getResultArray();

        return $this->respond($this->formatChartData($result, 'Industries'));
    }

    // 4. Certification Growth (Line Chart)
    public function certificationGrowth()
    {
        $builder = $this->db->table('certifications');
        $builder->select('DATE_FORMAT(certifications.issue_date, "%Y-%m") as label, COUNT(certifications.id) as count');
        $builder->where('certifications.issue_date !=', null);
        $builder->where('certifications.deleted_at', null);
        $this->applyFilters($builder, 'certifications');
        $builder->groupBy('label');
        $builder->orderBy('label', 'ASC');
        $builder->limit(12); // last 12 active months
        $result = $builder->get()->getResultArray();

        return $this->respond($this->formatChartData($result, 'Certifications Acquired'));
    }

    // 5. Skills vs Core Curriculum (Radar Chart)
    public function skillsCurriculum()
    {
        $builder = $this->db->table('profiles');
        $builder->select('profiles.skills');
        $builder->where('profiles.skills !=', null);
        $this->applyFilters($builder, 'profiles');
        $profiles = $builder->get()->getResultArray();

        $skillCounts = [];
        foreach ($profiles as $p) {
            $skills = json_decode($p['skills'], true);
            if (is_array($skills)) {
                foreach ($skills as $skill) {
                    $skill = trim($skill);
                    if (!empty($skill)) {
                        if (!isset($skillCounts[$skill])) {
                            $skillCounts[$skill] = 0;
                        }
                        $skillCounts[$skill]++;
                    }
                }
            }
        }

        arsort($skillCounts);
        $topSkills = array_slice($skillCounts, 0, 8, true);
        
        $result = [];
        foreach ($topSkills as $skill => $count) {
            $result[] = ['label' => $skill, 'count' => $count];
        }

        return $this->respond($this->formatChartData($result, 'Skill Frequency'));
    }

    // 6. Professional Courses (Horizontal Bar Chart)
    public function professionalCourses()
    {
        $builder = $this->db->table('professional_courses');
        $builder->select('professional_courses.course_name as label, COUNT(professional_courses.id) as count');
        $builder->where('professional_courses.deleted_at', null);
        $this->applyFilters($builder, 'professional_courses');
        $builder->groupBy('professional_courses.course_name');
        $builder->orderBy('count', 'DESC');
        $builder->limit(10);
        $result = $builder->get()->getResultArray();

        return $this->respond($this->formatChartData($result, 'Course Enrollments'));
    }

    // 7. Employment Status (Doughnut Chart)
    public function employmentStatus()
    {
        // Total Alumni
        $alumniBuilder = $this->db->table('users');
        $alumniBuilder->select('users.id');
        $alumniBuilder->where('users.role_id', 2)->where('users.status', 'approved');
        $this->applyFilters($alumniBuilder, 'users');
        $alumniCount = $alumniBuilder->countAllResults();
        
        // Alumni currently employed
        $builder = $this->db->table('employment_history');
        $builder->select('employment_history.user_id');
        $builder->where('employment_history.is_current', 1);
        $builder->where('employment_history.deleted_at', null);
        $this->applyFilters($builder, 'employment_history');
        $builder->groupBy('employment_history.user_id');
        $employedCount = $builder->countAllResults();

        $inTransition = max(0, $alumniCount - $employedCount);

        $result = [
            ['label' => 'Employed', 'count' => $employedCount],
            ['label' => 'In Transition / Seeking', 'count' => $inTransition]
        ];

        return $this->respond($this->formatChartData($result, 'Employment Status'));
    }

    // 8. User Roles (Pie Chart)
    public function userRoles()
    {
        $builder = $this->db->table('users');
        $builder->select('roles.name as label, COUNT(users.id) as count');
        $builder->join('roles', 'roles.id = users.role_id');
        $this->applyFilters($builder, 'users');
        $builder->groupBy('roles.name');
        $result = $builder->get()->getResultArray();

        return $this->respond($this->formatChartData($result, 'Platform Users'));
    }

    // --- PRESETS API ---
    public function savePreset()
    {
        $userId = $this->request->user->id ?? 1; // Assuming auth middleware sets user
        $name = $this->request->getPost('name');
        $filters = $this->request->getPost('filters');

        if (!$name || !$filters) return $this->fail('Name and filters required');

        $presetModel = new \App\Models\AdminFilterPresetModel();
        $presetModel->save([
            'user_id' => $userId,
            'name' => $name,
            'filters' => $filters
        ]);

        return $this->respondCreated(['message' => 'Preset saved']);
    }

    public function loadPresets()
    {
        $userId = $this->request->user->id ?? 1;
        $presetModel = new \App\Models\AdminFilterPresetModel();
        $presets = $presetModel->where('user_id', $userId)->findAll();

        return $this->respond(['data' => $presets]);
    }

    public function deletePreset($id)
    {
        $userId = $this->request->user->id ?? 1;
        $presetModel = new \App\Models\AdminFilterPresetModel();
        $presetModel->where('user_id', $userId)->where('id', $id)->delete();
        return $this->respondDeleted(['message' => 'Deleted successfully']);
    }

    // --- EXPORTS API ---
    public function exportCsv()
    {
        ob_start();
        $file = fopen('php://output', 'w');

        // Top Certifications
        fputcsv($file, ["--- Top Certifications ---"]);
        fputcsv($file, ["Certification Name", "Count"]);
        $builder = $this->db->table('certifications');
        $builder->select('certifications.name, COUNT(certifications.id) as count');
        $builder->where('certifications.deleted_at', null);
        $this->applyFilters($builder, 'certifications');
        $builder->groupBy('certifications.name');
        $builder->orderBy('count', 'DESC');
        foreach ($builder->get()->getResultArray() as $row) {
            fputcsv($file, $row);
        }
        fputcsv($file, []);

        // Emerging Career Pathways
        fputcsv($file, ["--- Emerging Career Pathways ---"]);
        fputcsv($file, ["Current Role", "Count"]);
        $builder = $this->db->table('employment_history');
        $builder->select('employment_history.position, COUNT(employment_history.id) as count');
        $builder->where('employment_history.is_current', 1);
        $builder->where('employment_history.deleted_at', null);
        $this->applyFilters($builder, 'employment_history');
        $builder->groupBy('employment_history.position');
        $builder->orderBy('count', 'DESC');
        foreach ($builder->get()->getResultArray() as $row) {
            fputcsv($file, $row);
        }
        fputcsv($file, []);

        // Industry Distribution
        fputcsv($file, ["--- Industry Distribution ---"]);
        fputcsv($file, ["Industry", "Count"]);
        $builder = $this->db->table('profiles');
        $builder->select('profiles.industry, COUNT(profiles.id) as count');
        $builder->where('profiles.industry !=', null);
        $builder->where('profiles.industry !=', '');
        $this->applyFilters($builder, 'profiles');
        $builder->groupBy('profiles.industry');
        $builder->orderBy('count', 'DESC');
        foreach ($builder->get()->getResultArray() as $row) {
            fputcsv($file, $row);
        }
        fputcsv($file, []);

        fclose($file);
        $csvData = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="analytics_export_' . date('Ymd_His') . '.csv"')
            ->setBody($csvData);
    }

    public function exportPdf()
    {
        // Accept base64 images from frontend to generate a PDF report
        $chartsData = $this->request->getPost('charts'); // Expecting json array of {title, imageBase64}
        if (!$chartsData) {
            return $this->fail('No charts data provided');
        }

        $charts = json_decode($chartsData, true);

        // Render HTML view for PDF
        $html = view('admin/pdf_report', ['charts' => $charts]);

        // Load DomPDF
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="Analytics_Report_' . date('Y-m-d') . '.pdf"')
            ->setBody($dompdf->output());
    }

    /**
     * Formats data into a structure suitable for Chart.js
     */
    private function formatChartData($data, $datasetLabel)
    {
        $labels = [];
        $values = [];

        foreach ($data as $row) {
            $labels[] = $row['label'];
            $values[] = $row['count'];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $datasetLabel,
                    'data' => $values
                ]
            ]
        ];
    }
}
