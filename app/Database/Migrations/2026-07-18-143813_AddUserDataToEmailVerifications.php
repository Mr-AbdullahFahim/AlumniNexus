<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserDataToEmailVerifications extends Migration
{
    public function up()
    {
        $this->forge->addColumn('email_verifications', [
            'user_data' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'token'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('email_verifications', 'user_data');
    }
}
