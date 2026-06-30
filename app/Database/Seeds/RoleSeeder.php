<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Alumni'],
            ['id' => 3, 'name' => 'Student'],
            ['id' => 4, 'name' => 'Sponsor'],
        ];

        // Ensure we ignore duplicates if seeded again
        $this->db->table('roles')->ignore(true)->insertBatch($data);
    }
}
