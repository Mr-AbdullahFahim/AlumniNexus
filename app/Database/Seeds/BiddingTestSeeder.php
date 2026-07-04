<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BiddingTestSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Clean up previous runs
        $db->table('users')->where('email', 'bidder@alumninexus.com')->delete();
        $db->table('users')->where('email', 'testsponsor@alumninexus.com')->delete();
        
        // 1. Create a new Alumni user
        $alumniData = [
            'name'       => 'Test Alumni Bidder',
            'email'             => 'bidder@alumninexus.com',
            'password_hash'     => password_hash('#String123', PASSWORD_DEFAULT),
            'role_id'           => 2, // Alumni
            'status'            => 'approved',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $db->table('users')->insert($alumniData);
        $alumniId = $db->insertID();
        
        // 2. Create their Alumni Profile
        $profileData = [
            'user_id'          => $alumniId,
            'company'          => 'Tech Innovations Inc',
            'position'         => 'Senior Developer',
            'bio'              => 'I am testing the new blind bidding module!',
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];
        $db->table('profiles')->insert($profileData);

        // 3. Create a Sponsor user
        $sponsorData = [
            'name'       => 'Test Sponsor',
            'email'             => 'testsponsor@alumninexus.com',
            'password_hash'     => password_hash('#String123', PASSWORD_DEFAULT),
            'role_id'           => 4, // Sponsor is 4 according to UserSeeder
            'status'            => 'approved',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('users')->insert($sponsorData);
        $sponsorId = $db->insertID();

        // 4. Create a Sponsorship for the Alumni so they have funds to bid
        $sponsorshipData = [
            'sponsor_id' => $sponsorId,
            'alumni_id'  => $alumniId,
            'amount'     => 500.00,
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('sponsorships')->insert($sponsorshipData);

        echo "Seeded Test Alumni Bidder (ID: {$alumniId}) with $500 in sponsorships.\n";
        echo "Email: bidder@alumninexus.com\n";
        echo "Password: #String123\n";
    }
}
