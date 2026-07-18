<?php

namespace App\Models;

use CodeIgniter\Model;

class BlindBidModel extends Model
{
    protected $table            = 'blind_bids';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'alumni_id',
        'bid_date',
        'bid_amount',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Determines the current bidding cycle date based on the 6 PM cutoff.
     */
    public function getCurrentCycleDate(): string
    {
        $db = \Config\Database::connect();
        $query = $db->table('settings')->where('setting_key', 'current_cycle_date')->get();
        $row = $query->getRow();
        
        if ($row) {
            return $row->setting_value;
        }

        // Fallback
        return date('Y-m-d');
    }

    /**
     * Get the highest bid for a specific date (used by settlement job).
     */
    public function getHighestBidForDate(string $date)
    {
        return $this->where('bid_date', $date)
                    ->orderBy('bid_amount', 'DESC')
                    ->orderBy('updated_at', 'ASC')
                    ->first();
    }
}
