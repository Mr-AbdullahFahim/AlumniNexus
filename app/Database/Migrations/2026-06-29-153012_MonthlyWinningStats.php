<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MonthlyWinningStats extends Migration
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
            'year_val' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'month_val' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'wins_count' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('alumni_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['alumni_id', 'year_val', 'month_val']);
        $this->forge->createTable('monthly_winning_stats');
    }

    public function down()
    {
        $this->forge->dropTable('monthly_winning_stats');
    }
}
