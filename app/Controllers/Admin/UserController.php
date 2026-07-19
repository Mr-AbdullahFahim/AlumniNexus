<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data = [
            'title' => 'User Management',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Users', 'url' => base_url('admin/users')]
            ]
        ];

        return view('admin/users', $data);
    }

    public function list()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        $builder->select('u.id, u.name, u.email, u.status, u.created_at, r.name as role_name');
        $builder->join('roles r', 'r.id = u.role_id');

        // Filters
        $search = $this->request->getGet('search');
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.name', $search)
                ->orLike('u.email', $search)
                ->groupEnd();
        }

        if (!empty($role)) {
            $builder->where('r.name', $role);
        }

        if (!empty($status)) {
            $builder->where('u.status', $status);
        }

        $builder->orderBy('u.created_at', 'DESC');

        // Pagination
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 10);
        $offset = ($page - 1) * $limit;

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

    public function updateStatus()
    {
        $rules = [
            'user_id' => 'required|is_natural_no_zero',
            'status'  => 'required|in_list[pending,approved,rejected,suspended]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userId = $this->request->getPost('user_id');
        $status = $this->request->getPost('status');

        // Prevent admin from changing their own status
        if ($userId == $this->request->user->sub) {
            return $this->fail('You cannot change your own status.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $oldUser = $builder->where('id', $userId)->get()->getRowArray();
        $builder->where('id', $userId)->update(['status' => $status]);
        log_activity("Updated User Status to {$status}", 'users', $userId, ['status' => $oldUser['status']], ['status' => $status]);

        return $this->respond([
            'status' => 'success',
            'message' => 'User status updated successfully.'
        ]);
    }

    public function updateRole()
    {
        $rules = [
            'user_id' => 'required|is_natural_no_zero',
            'role_id' => 'required|in_list[1,2,3,4]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userId = $this->request->getPost('user_id');
        $roleId = $this->request->getPost('role_id');

        // Prevent admin from changing their own role to something else
        if ($userId == $this->request->user->sub) {
            return $this->fail('You cannot change your own role.');
        }

        $db = \Config\Database::connect();
        $oldUser = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $db->table('users')->where('id', $userId)->update(['role_id' => $roleId]);
        log_activity("Updated User Role to ID {$roleId}", 'users', $userId, ['role_id' => $oldUser['role_id']], ['role_id' => $roleId]);

        return $this->respond([
            'status' => 'success',
            'message' => 'User role updated successfully.'
        ]);
    }
}
