<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Settings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'setting_value' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('setting_key', true);
        $this->forge->createTable('settings');

        // Seed initial values
        $db = \Config\Database::connect();
        
        $hour = (int) date('H');
        if ($hour >= 18) {
            $currentCycle = date('Y-m-d', strtotime('+1 day'));
            $nextCycleEndTime = date('Y-m-d 18:00:00', strtotime('+1 day'));
        } else {
            $currentCycle = date('Y-m-d');
            $nextCycleEndTime = date('Y-m-d 18:00:00');
        }

        $data = [
            [
                'setting_key' => 'current_cycle_date',
                'setting_value' => $currentCycle,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'next_cycle_end_time',
                'setting_value' => $nextCycleEndTime,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        
        $db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
