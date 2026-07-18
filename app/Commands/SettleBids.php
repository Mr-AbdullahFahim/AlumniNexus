<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BlindBidModel;
use App\Models\FeaturedAlumniModel;
use App\Models\MonthlyWinningStatsModel;
use App\Models\SponsorshipModel;

class SettleBids extends BaseCommand
{
    protected $group       = 'AlumniNexus';
    protected $name        = 'bid:settle';
    protected $description = 'Settles the daily blind bids and determines the winner at 6 PM.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Fetch current cycle date from settings
        $settingsQuery = $db->table('settings')->where('setting_key', 'current_cycle_date')->get();
        $cycleDateRow = $settingsQuery->getRow();
        $cycleDate = $cycleDateRow ? $cycleDateRow->setting_value : date('Y-m-d');
        
        CLI::write("Settling blind bids for cycle date: {$cycleDate}", 'green');

        $bidModel = new BlindBidModel();
        
        // Find highest bid for the cycle
        $highestBid = $bidModel->where('bid_date', $cycleDate)
                               ->where('status', 'pending')
                               ->orderBy('bid_amount', 'DESC')
                               ->orderBy('updated_at', 'ASC')
                               ->first();

        $db->transStart();

        // Calculate next cycle end time (>24h and <48h ending at 6 PM)
        $now = time();
        $next6PM = strtotime('today 18:00:00');
        if ($next6PM <= $now) {
            $next6PM = strtotime('tomorrow 18:00:00');
        }
        
        // Ensure duration is strictly > 24 hours
        if (($next6PM - $now) <= 86400) {
            $next6PM = strtotime('+1 day', $next6PM);
        }
        
        $nextCycleEndTime = date('Y-m-d H:i:s', $next6PM);
        $nextCycleDate = date('Y-m-d', $next6PM);

        if ($highestBid) {
            $winnerId = $highestBid['alumni_id'];
            $winnerBidId = $highestBid['id'];

            // 1. Mark winner
            $bidModel->update($winnerBidId, ['status' => 'won']);

            // 2. Mark losers
            $bidModel->where('bid_date', $cycleDate)
                     ->where('id !=', $winnerBidId)
                     ->set(['status' => 'lost'])
                     ->update();

            // 3. Insert into featured_alumni
            $featuredModel = new FeaturedAlumniModel();
            $featuredModel->insert([
                'alumni_id' => $winnerId,
                'bid_id' => $winnerBidId,
                'featured_date' => $nextCycleDate // featured until next cycle ends
            ]);

            // 4. Update monthly_winning_stats
            $statsModel = new MonthlyWinningStatsModel();
            $currentYear = (int) date('Y');
            $currentMonth = (int) date('m');
            
            $stat = $statsModel->where('alumni_id', $winnerId)
                               ->where('year_val', $currentYear)
                               ->where('month_val', $currentMonth)
                               ->first();
            
            if ($stat) {
                $statsModel->update($stat['id'], [
                    'wins_count' => $stat['wins_count'] + 1
                ]);
            } else {
                $statsModel->insert([
                    'alumni_id' => $winnerId,
                    'year_val' => $currentYear,
                    'month_val' => $currentMonth,
                    'wins_count' => 1
                ]);
            }

            // 5. Process Sponsorships (insert charge)
            $featuredRecord = $featuredModel->where('featured_date', $nextCycleDate)->first();
            
            $db->table('sponsor_charges')->insert([
                'sponsor_id' => $winnerId,
                'featured_alumni_id' => $featuredRecord['id'],
                'amount_charged' => $highestBid['bid_amount'],
                'status' => 'paid',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            CLI::write("Cycle settled! Winner is Alumni ID: {$winnerId} with bid ID: {$winnerBidId}", 'green');
        } else {
            // Even if no bids, we mark any lingering pending bids as lost just in case, though none exist
            CLI::write("No pending bids found for this cycle.", 'yellow');
        }

        // 6. Update Settings for the new cycle
        $db->table('settings')->where('setting_key', 'current_cycle_date')->update([
            'setting_value' => $nextCycleDate,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $db->table('settings')->where('setting_key', 'next_cycle_end_time')->update([
            'setting_value' => $nextCycleEndTime,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            CLI::write("Transaction failed during settlement.", 'red');
        } else {
            CLI::write("Next cycle advanced to: {$nextCycleDate} (Ends at {$nextCycleEndTime})", 'green');
        }
    }
}
