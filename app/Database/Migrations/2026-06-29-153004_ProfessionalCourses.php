<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProfessionalCourses extends Migration
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
            'course_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'institution' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'completion_date' => [
                'type' => 'DATE',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('professional_courses');
    }

    public function down()
    {
        $this->forge->dropTable('professional_courses');
    }
}
