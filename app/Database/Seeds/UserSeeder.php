<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('#String123', PASSWORD_DEFAULT);
        $now = Time::now()->toDateTimeString();

        $data = [
            [
                'role_id'           => 1,
                'name'              => 'System Admin',
                'email'             => 'admin@alumninexus.com',
                'password_hash'     => $password,
                'status'            => 'approved',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'role_id'           => 2,
                'name'              => 'John Alumni',
                'email'             => 'alumni@example.com',
                'password_hash'     => $password,
                'status'            => 'approved',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'role_id'           => 3,
                'name'              => 'Jane Student',
                'email'             => 'student@example.com',
                'password_hash'     => $password,
                'status'            => 'approved',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'role_id'           => 4,
                'name'              => 'TechCorp Sponsor',
                'email'             => 'sponsor@example.com',
                'password_hash'     => $password,
                'status'            => 'approved',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        // Insert using ignore(true) to avoid errors if run multiple times
        $this->db->table('users')->ignore(true)->insertBatch($data);
    }
}
