<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSkillsAndSocialsToProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('profiles', [
            'skills' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'social_links' => [
                'type' => 'JSON',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('profiles', 'skills');
        $this->forge->dropColumn('profiles', 'social_links');
    }
}
