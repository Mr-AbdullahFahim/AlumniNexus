<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddViewCountToProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('profiles', [
            'view_count' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0,
                'after' => 'industry',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('profiles', 'view_count');
    }
}
