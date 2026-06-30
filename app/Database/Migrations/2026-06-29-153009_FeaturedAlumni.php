<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FeaturedAlumni extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'unsigned' => true, 'auto_increment' => true,
            ],
            'alumni_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'bid_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'featured_date' => [
                'type' => 'DATE',
                'unique' => true, // Only one featured alumni per day
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('alumni_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('bid_id', 'blind_bids', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('featured_alumni');
    }

    public function down()
    {
        $this->forge->dropTable('featured_alumni');
    }
}
