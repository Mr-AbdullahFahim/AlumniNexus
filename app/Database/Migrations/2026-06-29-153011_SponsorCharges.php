<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SponsorCharges extends Migration
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
            'featured_alumni_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'amount_charged' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed'],
                'default'    => 'pending',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sponsor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('featured_alumni_id', 'featured_alumni', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sponsor_charges');
    }

    public function down()
    {
        $this->forge->dropTable('sponsor_charges');
    }
}
