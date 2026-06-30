<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ApprovalRequests extends Migration
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
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
            ],
            'reviewed_by' => [
                'type' => 'INT', 'unsigned' => true, 'null' => true,
            ],
            'comments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('approval_requests');
    }

    public function down()
    {
        $this->forge->dropTable('approval_requests');
    }
}
