<?php

namespace App\Models;

use CodeIgniter\Model;

class MonthlyWinningStatsModel extends Model
{
    protected $table            = 'monthly_winning_stats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'alumni_id',
        'year_val',
        'month_val',
        'wins_count',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Helper method to check if an alumni has reached the monthly winning limit.
     * Limit is defined as 3 wins in the current month.
     *
     * @param int $alumniId
     * @return bool True if they have 3 or more wins this month, false otherwise.
     */
    public function hasReachedMonthlyLimit(int $alumniId): bool
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        $record = $this->where('alumni_id', $alumniId)
                       ->where('year_val', $currentYear)
                       ->where('month_val', $currentMonth)
                       ->first();

        if ($record && (int) $record['wins_count'] >= 3) {
            return true;
        }

        return false;
    }
}
