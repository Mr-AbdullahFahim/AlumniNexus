<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class ActivityController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data = [
            'title' => 'Activity Logs',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Activity Logs', 'url' => base_url('admin/activities')]
            ]
        ];

        return view('admin/activities', $data);
    }

    public function list()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('audit_logs a');
        $builder->select('a.*, u.name as user_name, u.email as user_email, r.name as role_name');
        $builder->join('users u', 'u.id = a.user_id', 'left');
        $builder->join('roles r', 'r.id = u.role_id', 'left');

        // Filters
        $search = $this->request->getGet('search');
        $action = $this->request->getGet('action');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.name', $search)
                ->orLike('u.email', $search)
                ->orLike('a.action', $search)
                ->groupEnd();
        }

        if (!empty($action)) {
            $builder->like('a.action', $action);
        }

        $builder->orderBy('a.created_at', 'DESC');

        // Pagination
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 20);
        $offset = ($page - 1) * $limit;

        $countBuilder = clone $builder;
        $totalRows = $countBuilder->countAllResults();

        $builder->limit($limit, $offset);
        $results = $builder->get()->getResultArray();

        // Decode JSON fields for easy frontend use
        foreach ($results as &$row) {
            $row['old_values'] = json_decode($row['old_values'] ?? 'null', true);
            $row['new_values'] = json_decode($row['new_values'] ?? 'null', true);
        }

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
}
