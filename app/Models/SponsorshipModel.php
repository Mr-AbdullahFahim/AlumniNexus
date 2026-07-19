<?php

namespace App\Models;

use CodeIgniter\Model;

class SponsorshipModel extends Model
{
    protected $table            = 'sponsorships';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['sponsor_id', 'alumni_id', 'amount', 'status', 'cycle_date'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Get the total sponsorship amount for an alumni in a specific cycle.
     * The cycle date shifts at 18:00 (6 PM).
     *
     * @param int $alumniId
     * @param string $cycleDate (Y-m-d)
     * @return float
     */
    public function getTotalForCycle(int $alumniId, ?string $cycleDate): float
    {
        if (!$cycleDate) return 0.0;
        
        $result = $this->selectSum('amount')
                       ->where('alumni_id', $alumniId)
                       ->where('cycle_date', $cycleDate)
                       ->first();

        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Get the total sponsorship amount for an alumni by a specific sponsor in a specific cycle.
     *
     * @param int $sponsorId
     * @param int $alumniId
     * @param string $cycleDate (Y-m-d)
     * @return float
     */
    public function getSponsorTotalForCycle(int $sponsorId, int $alumniId, ?string $cycleDate): float
    {
        if (!$cycleDate) return 0.0;
        
        $result = $this->selectSum('amount')
                       ->where('sponsor_id', $sponsorId)
                       ->where('alumni_id', $alumniId)
                       ->where('cycle_date', $cycleDate)
                       ->first();

        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Get the individual sponsorships for an alumni in a specific cycle.
     *
     * @param int $alumniId
     * @param string $cycleDate (Y-m-d)
     * @return array
     */
    public function getSponsorshipsForCycle(int $alumniId, ?string $cycleDate): array
    {
        if (!$cycleDate) return [];
        
        return $this->select('sponsorships.amount, users.name as sponsor_name')
                    ->join('users', 'users.id = sponsorships.sponsor_id')
                    ->where('sponsorships.alumni_id', $alumniId)
                    ->where('sponsorships.cycle_date', $cycleDate)
                    ->findAll();
    }

    /**
     * Get the sponsorship history for a specific sponsor grouped by cycle.
     *
     * @param int $sponsorId
     * @return array
     */
    public function getSponsorHistory(int $sponsorId): array
    {
        $sponsorships = $this->select('sponsorships.*, users.name as alumni_name, profiles.photo_url')
                             ->join('users', 'users.id = sponsorships.alumni_id')
                             ->join('profiles', 'profiles.user_id = sponsorships.alumni_id', 'left')
                             ->where('sponsorships.sponsor_id', $sponsorId)
                             ->orderBy('sponsorships.cycle_date', 'DESC')
                             ->orderBy('sponsorships.created_at', 'DESC')
                             ->findAll();

        $db = \Config\Database::connect();
        $cycles = [];
        $todayCycle = (new \App\Models\BlindBidModel())->getCurrentCycleDate();

        foreach ($sponsorships as $s) {
            $cycleDate = $s['cycle_date'];
            
            if (!isset($cycles[$cycleDate])) {
                $cycles[$cycleDate] = [
                    'cycle_date' => $cycleDate,
                    'total_amount' => 0,
                    'successful_amount' => 0,
                    'failed_amount' => 0,
                    'pending_amount' => 0,
                    'sponsorships' => []
                ];
            }
            
            $bid = $db->table('blind_bids')
                      ->where('alumni_id', $s['alumni_id'])
                      ->where('bid_date', $cycleDate)
                      ->get()
                      ->getRowArray();
                      
            $status = 'pending';
            if ($bid) {
                $status = $bid['status'];
            } else {
                if ($cycleDate < $todayCycle) {
                    $status = 'lost';
                }
            }
            
            $s['bid_status'] = $status;
            
            $alumniId = $s['alumni_id'];
            if (!isset($cycles[$cycleDate]['sponsorships'][$alumniId])) {
                $cycles[$cycleDate]['sponsorships'][$alumniId] = $s;
            } else {
                $cycles[$cycleDate]['sponsorships'][$alumniId]['amount'] += (float)$s['amount'];
            }
            
            $amount = (float)$s['amount'];
            $cycles[$cycleDate]['total_amount'] += $amount;
            
            if ($status === 'won') {
                $cycles[$cycleDate]['successful_amount'] += $amount;
            } elseif ($status === 'pending') {
                $cycles[$cycleDate]['pending_amount'] += $amount;
            } else {
                $cycles[$cycleDate]['failed_amount'] += $amount;
            }
        }
        
        krsort($cycles);
        
        // Reset the keys for the nested sponsorships arrays
        foreach ($cycles as &$cycle) {
            $cycle['sponsorships'] = array_values($cycle['sponsorships']);
        }
        
        return array_values($cycles);
    }

    // Validation
    protected $validationRules      = [
        'sponsor_id' => 'required|is_natural_no_zero',
        'alumni_id'  => 'required|is_natural_no_zero',
        'amount'     => 'required|decimal|greater_than_equal_to[1.00]',
        'status'     => 'required|in_list[active,cancelled]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
