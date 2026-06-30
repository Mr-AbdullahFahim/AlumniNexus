<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BlindBids extends Migration
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
            'bid_date' => [
                'type' => 'DATE',
            ],
            'bid_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'won', 'lost', 'cancelled'],
                'default'    => 'pending',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('alumni_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['alumni_id', 'bid_date']); // One bid per day per alumni
        $this->forge->createTable('blind_bids');
    }

    public function down()
    {
        $this->forge->dropTable('blind_bids');
    }
}
