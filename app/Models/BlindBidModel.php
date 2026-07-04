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
        $currentHour = (int) date('H');
        if ($currentHour >= 18) {
            return date('Y-m-d', strtotime('+1 day'));
        }
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
