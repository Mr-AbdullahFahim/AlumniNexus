<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WinningStatsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'alumni_id'  => 2,
            'year_val'   => (int) date('Y'),
            'month_val'  => (int) date('m'),
            'wins_count' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Delete any existing stats for this alumni this month
        $this->db->table('monthly_winning_stats')
                 ->where('alumni_id', 2)
                 ->where('year_val', $data['year_val'])
                 ->where('month_val', $data['month_val'])
                 ->delete();

        $this->db->table('monthly_winning_stats')->insert($data);
    }
}
