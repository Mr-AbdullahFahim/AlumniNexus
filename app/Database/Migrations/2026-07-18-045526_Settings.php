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

        // Note: Initial values for current_cycle_date and next_cycle_end_time 
        // are no longer seeded here. They are created when the settle script runs.
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
