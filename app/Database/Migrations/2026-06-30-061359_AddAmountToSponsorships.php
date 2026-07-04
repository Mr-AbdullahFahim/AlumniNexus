<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAmountToSponsorships extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sponsorships', [
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sponsorships', 'amount');
    }
}
