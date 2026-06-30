<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmploymentHistory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'unsigned' => true, 'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'company_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'position' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'is_current' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employment_history');
    }

    public function down()
    {
        $this->forge->dropTable('employment_history');
    }
}
