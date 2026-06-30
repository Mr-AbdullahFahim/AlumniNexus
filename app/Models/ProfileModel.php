<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table            = 'profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id', 
        'photo_url', 
        'bio', 
        'company', 
        'position', 
        'skills', 
        'social_links',
        'department',
        'graduation_year',
        'industry'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'         => 'required|is_natural_no_zero',
        'photo_url'       => 'permit_empty|valid_url',
        'bio'             => 'permit_empty',
        'company'         => 'permit_empty|max_length[150]',
        'position'        => 'permit_empty|max_length[150]',
        'department'      => 'permit_empty|max_length[150]',
        'graduation_year' => 'permit_empty|is_natural_no_zero',
        'industry'        => 'permit_empty|max_length[150]',
    ];
}
