<?php

namespace App\Models;

use CodeIgniter\Model;

class DegreeModel extends Model
{
    protected $table            = 'degrees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id', 
        'institution', 
        'degree_name', 
        'start_date', 
        'end_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'        => 'required|is_natural_no_zero',
        'institution'    => 'required|max_length[150]',
        'degree_name'    => 'required|max_length[150]',
        'start_date'     => 'permit_empty|valid_date',
        'end_date'       => 'permit_empty|valid_date',
    ];
}
