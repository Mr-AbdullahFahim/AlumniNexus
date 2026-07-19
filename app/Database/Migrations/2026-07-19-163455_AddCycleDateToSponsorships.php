<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCycleDateToSponsorships extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sponsorships', [
            'cycle_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sponsorships', 'cycle_date');
    }
}
