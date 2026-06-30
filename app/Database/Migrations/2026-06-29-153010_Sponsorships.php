<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sponsorships extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'unsigned' => true, 'auto_increment' => true,
            ],
            'sponsor_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'alumni_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'cancelled'],
                'default'    => 'active',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sponsor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('alumni_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // A sponsor can only have one active sponsorship for a specific alumni
        $this->forge->addUniqueKey(['sponsor_id', 'alumni_id', 'deleted_at']);
        $this->forge->createTable('sponsorships');
    }

    public function down()
    {
        $this->forge->dropTable('sponsorships');
    }
}
