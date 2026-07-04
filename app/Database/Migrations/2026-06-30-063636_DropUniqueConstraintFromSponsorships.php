<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUniqueConstraintFromSponsorships extends Migration
{
    public function up()
    {
        // Drop foreign keys first to allow dropping the index
        $this->db->query('ALTER TABLE `sponsorships` DROP FOREIGN KEY `sponsorships_sponsor_id_foreign`');
        
        // Drop the unique index
        $this->db->query('ALTER TABLE `sponsorships` DROP INDEX `sponsor_id_alumni_id_deleted_at`');
        
        // Re-add the foreign key
        $this->db->query('ALTER TABLE `sponsorships` ADD CONSTRAINT `sponsorships_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `sponsorships` DROP FOREIGN KEY `sponsorships_sponsor_id_foreign`');
        $this->db->query('ALTER TABLE `sponsorships` ADD UNIQUE INDEX `sponsor_id_alumni_id_deleted_at` (`sponsor_id`, `alumni_id`, `deleted_at`)');
        $this->db->query('ALTER TABLE `sponsorships` ADD CONSTRAINT `sponsorships_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
