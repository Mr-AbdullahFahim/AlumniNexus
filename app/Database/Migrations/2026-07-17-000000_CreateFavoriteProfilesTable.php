<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFavoriteProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'alumni_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('alumni_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // Prevent duplicate favorites
        $this->forge->addUniqueKey(['student_id', 'alumni_id']);
        
        $this->forge->createTable('favorite_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('favorite_profiles');
    }
}
