<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificationModel extends Model
{
    protected $table            = 'certifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id', 
        'name', 
        'issuing_organization', 
        'issue_date', 
        'expiration_date',
        'credential_id',
        'credential_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'              => 'required|is_natural_no_zero',
        'name'                 => 'required|max_length[255]',
        'issuing_organization' => 'required|max_length[255]',
        'credential_url'       => 'permit_empty|valid_url_strict',
    ];
}
