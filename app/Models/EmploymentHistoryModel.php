<?php

namespace App\Models;

use CodeIgniter\Model;

class EmploymentHistoryModel extends Model
{
    protected $table            = 'employment_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id', 
        'company_name', 
        'position', 
        'start_date', 
        'end_date',
        'is_current',
        'description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'      => 'required|is_natural_no_zero',
        'company_name' => 'required|max_length[150]',
        'position'     => 'required|max_length[150]',
        'start_date'   => 'permit_empty|valid_date',
        'end_date'     => 'permit_empty|valid_date',
        'is_current'   => 'permit_empty|in_list[0,1]',
    ];
}
