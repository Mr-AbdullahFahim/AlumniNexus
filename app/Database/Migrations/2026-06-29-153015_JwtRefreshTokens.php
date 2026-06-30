<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JwtRefreshTokens extends Migration
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
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'is_revoked' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jwt_refresh_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('jwt_refresh_tokens');
    }
}
