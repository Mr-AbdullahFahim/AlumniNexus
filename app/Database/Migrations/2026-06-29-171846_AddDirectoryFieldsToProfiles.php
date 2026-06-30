<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDirectoryFieldsToProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('profiles', [
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'graduation_year' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
            ],
            'industry' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('profiles', ['department', 'graduation_year', 'industry']);
    }
}
