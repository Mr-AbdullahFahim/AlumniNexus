<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DummyAlumniSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('#String123', PASSWORD_DEFAULT);
        $now = Time::now()->toDateTimeString();

        $departments = ['Computer Science', 'Business Administration', 'Mechanical Engineering', 'Law', 'Medicine'];
        $industries = ['Technology', 'Finance', 'Manufacturing', 'Legal', 'Healthcare', 'Education'];
        
        $users = [];
        $profiles = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $email = "dummy.alumni{$i}@example.com";
            
            $users[] = [
                'role_id'           => 2, // Alumni
                'name'              => "Alumni Member {$i}",
                'email'             => $email,
                'password_hash'     => $password,
                'status'            => 'approved',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        $this->db->table('users')->ignore(true)->insertBatch($users);

        // Fetch inserted users to get their IDs
        $insertedUsers = $this->db->table('users')
            ->where('role_id', 2)
            ->like('email', 'dummy.alumni')
            ->get()->getResultArray();

        foreach ($insertedUsers as $index => $user) {
            $profiles[] = [
                'user_id' => $user['id'],
                'bio' => "Hello, I am Alumni Member {$index} and this is my dummy bio.",
                'company' => "Company " . rand(1, 10),
                'position' => "Position " . rand(1, 5),
                'department' => $departments[array_rand($departments)],
                'industry' => $industries[array_rand($industries)],
                'graduation_year' => rand(2010, 2026),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->db->table('profiles')->ignore(true)->insertBatch($profiles);
    }
}
