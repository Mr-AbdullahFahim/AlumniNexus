<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DummyAlumniSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('#String123', PASSWORD_DEFAULT);
        
        $departments = ['Computer Science', 'Business Administration', 'Mechanical Engineering', 'Law', 'Medicine', 'Design'];
        $industries = ['Technology', 'Finance', 'Manufacturing', 'Legal', 'Healthcare', 'Education', 'Consulting', 'E-commerce', 'Media & Entertainment'];
        
        $skillsPool = ["Python", "AWS", "Machine Learning", "Project Management", "Digital Marketing", "React", "Data Analysis", "Cybersecurity", "DevOps", "Agile", "SQL", "Cloud Native", "Generative AI"];
        $certificationsPool = ["AWS Solutions Architect", "PMP", "CISSP", "Google Cloud Professional", "CompTIA Security+", "Scrum Master (CSM)", "CPA", "CFA Level 1", "Azure Fundamentals"];
        $rolesPool = ["AI Engineer", "Data Scientist", "Cloud Architect", "Product Manager", "Software Engineer", "Marketing Director", "Financial Analyst", "Cybersecurity Analyst", "UX Designer", "DevOps Engineer"];
        $coursesPool = ["Machine Learning by Stanford", "CS50 Introduction to Computer Science", "Google Data Analytics", "Meta Front-End Developer", "IBM Data Science", "Agile with Atlassian Jira"];

        $firstNames = ['James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda', 'David', 'Elizabeth', 'William', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Charles', 'Karen'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];

        $users = [];
        for ($i = 1; $i <= 50; $i++) {
            $fName = $firstNames[array_rand($firstNames)];
            $lName = $lastNames[array_rand($lastNames)];
            
            $now = Time::now()->subDays(rand(1, 300))->toDateTimeString(); // random join dates
            
            $users[] = [
                'role_id'           => 2, // Alumni
                'name'              => "$fName $lName",
                'email'             => strtolower($fName) . "." . strtolower($lName) . $i . "@example.com",
                'password_hash'     => $password,
                'status'            => 'approved',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        $this->db->table('users')->ignore(true)->insertBatch($users);

        $insertedUsers = $this->db->table('users')
            ->where('role_id', 2)
            ->like('email', '@example.com')
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        $profiles = [];
        $certifications = [];
        $employment = [];
        $courses = [];

        foreach ($insertedUsers as $user) {
            $userId = $user['id'];
            $now = Time::now()->toDateTimeString();

            // Select 2-5 random skills
            $userSkills = [];
            $numSkills = rand(2, 5);
            $poolKeys = (array)array_rand($skillsPool, $numSkills);
            foreach ($poolKeys as $key) {
                $userSkills[] = $skillsPool[$key];
            }

            // Profile
            $profiles[] = [
                'user_id' => $userId,
                'bio' => "Experienced professional passionate about continuous learning and growth.",
                'company' => "Company " . rand(1, 50),
                'position' => $rolesPool[array_rand($rolesPool)],
                'department' => $departments[array_rand($departments)],
                'industry' => $industries[array_rand($industries)],
                'graduation_year' => rand(2010, 2025),
                'skills' => json_encode($userSkills),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Certifications (1-3)
            $numCerts = rand(1, 3);
            $certKeys = (array)array_rand($certificationsPool, $numCerts);
            foreach ($certKeys as $key) {
                $issueDate = Time::now()->subMonths(rand(1, 12))->toDateTimeString();
                $certifications[] = [
                    'user_id' => $userId,
                    'name' => $certificationsPool[$key],
                    'issuing_organization' => 'Certification Body',
                    'issue_date' => $issueDate,
                    'credential_id' => 'CERT-' . rand(1000, 9999),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Employment History (1-2)
            // Determine if currently employed based on 80% chance
            $isEmployed = (rand(1, 100) <= 80);
            if ($isEmployed) {
                $employment[] = [
                    'user_id' => $userId,
                    'company_name' => "Company " . rand(1, 50),
                    'position' => $rolesPool[array_rand($rolesPool)],
                    'is_current' => 1,
                    'start_date' => Time::now()->subMonths(rand(1, 24))->toDateTimeString(),
                    'end_date' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Past employment
            $employment[] = [
                'user_id' => $userId,
                'company_name' => "Company " . rand(51, 100),
                'position' => $rolesPool[array_rand($rolesPool)],
                'is_current' => 0,
                'start_date' => Time::now()->subMonths(rand(25, 48))->toDateTimeString(),
                'end_date' => Time::now()->subMonths(rand(1, 24))->toDateTimeString(),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Professional Courses (0-2)
            $numCourses = rand(0, 2);
            if ($numCourses > 0) {
                $courseKeys = (array)array_rand($coursesPool, $numCourses);
                foreach ($courseKeys as $key) {
                    $courses[] = [
                        'user_id' => $userId,
                        'course_name' => $coursesPool[$key],
                        'institution' => 'Online Platform',
                        'completion_date' => Time::now()->subMonths(rand(1, 12))->toDateTimeString(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($profiles)) {
            $this->db->table('profiles')->ignore(true)->insertBatch($profiles);
        }
        
        if (!empty($certifications)) {
            $this->db->table('certifications')->ignore(true)->insertBatch($certifications);
        }
        
        if (!empty($employment)) {
            $this->db->table('employment_history')->ignore(true)->insertBatch($employment);
        }
        
        if (!empty($courses)) {
            $this->db->table('professional_courses')->ignore(true)->insertBatch($courses);
        }
    }
}
