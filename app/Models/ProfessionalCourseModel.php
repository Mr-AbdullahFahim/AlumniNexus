<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfessionalCourseModel extends Model
{
    protected $table            = 'professional_courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id', 
        'name', 
        'institution', 
        'start_date', 
        'end_date',
        'description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'     => 'required|is_natural_no_zero',
        'name'        => 'required|max_length[255]',
        'institution' => 'required|max_length[255]',
        'start_date'  => 'permit_empty|valid_date',
        'end_date'    => 'permit_empty|valid_date',
    ];
}
