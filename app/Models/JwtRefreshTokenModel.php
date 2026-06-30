<?php

namespace App\Models;

use CodeIgniter\Model;

class JwtRefreshTokenModel extends Model
{
    protected $table            = 'jwt_refresh_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'user_id', 'token', 'expires_at', 'is_revoked'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
