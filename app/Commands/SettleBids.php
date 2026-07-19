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
        
        $bidModel = new BlindBidModel();

        $db->transStart();

        if ($cycleDateRow) {
            $cycleDate = $cycleDateRow->setting_value;
            CLI::write("Ongoing cycle found: {$cycleDate}. Settling now...", 'green');

            // Find highest bid for the cycle
            $highestBid = $bidModel->where('bid_date', $cycleDate)
                                   ->where('status', 'pending')
                                   ->orderBy('bid_amount', 'DESC')
                                   ->orderBy('updated_at', 'ASC')
                                   ->first();

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
                    // Featured until the NEXT cycle ends. We'll set this to nextCycleDate later.
                ]);
                $featuredId = $featuredModel->insertID();

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
                $db->table('sponsor_charges')->insert([
                    'sponsor_id' => $winnerId,
                    'featured_alumni_id' => $featuredId,
                    'amount_charged' => $highestBid['bid_amount'],
                    'status' => 'paid',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                CLI::write("Cycle settled! Winner is Alumni ID: {$winnerId} with bid ID: {$winnerBidId}", 'green');
            } else {
                CLI::write("No pending bids found for this cycle.", 'yellow');
            }
        } else {
            CLI::write("No ongoing cycle found. Initializing new cycle...", 'yellow');
        }

        // 6. Calculate next cycle end time
        if ($cycleDateRow) {
            // If there was an ongoing cycle, the next cycle is exactly 1 day after it.
            $nextCycleDate = date('Y-m-d', strtotime('+1 day', strtotime($cycleDateRow->setting_value)));
            $nextCycleEndTime = $nextCycleDate . ' 18:00:00';
        } else {
            // If no ongoing cycle, calculate the very next 6 PM from right now.
            $now = time();
            $hour = (int) date('H', $now);

            if ($hour >= 18) {
                $next6PM = strtotime('tomorrow 18:00:00');
            } else {
                $next6PM = strtotime('today 18:00:00');
            }
            $nextCycleDate = date('Y-m-d', $next6PM);
            $nextCycleEndTime = date('Y-m-d H:i:s', $next6PM);
        }



        // Update the featured date for the winner we just created (if any)
        if (isset($featuredId) && $featuredId) {
            $db->table('featured_alumni')->where('id', $featuredId)->update([
                'featured_date' => $nextCycleDate
            ]);
        }

        // 7. Update Settings for the new cycle
        if ($cycleDateRow) {
            $db->table('settings')->where('setting_key', 'current_cycle_date')->update([
                'setting_value' => $nextCycleDate,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $db->table('settings')->insert([
                'setting_key' => 'current_cycle_date',
                'setting_value' => $nextCycleDate,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        $nextEndRow = $db->table('settings')->where('setting_key', 'next_cycle_end_time')->get()->getRow();
        if ($nextEndRow) {
            $db->table('settings')->where('setting_key', 'next_cycle_end_time')->update([
                'setting_value' => $nextCycleEndTime,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $db->table('settings')->insert([
                'setting_key' => 'next_cycle_end_time',
                'setting_value' => $nextCycleEndTime,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            CLI::write("Transaction failed during settlement.", 'red');
        } else {
            CLI::write("New cycle started for: {$nextCycleDate} (Ends at {$nextCycleEndTime})", 'green');
        }
    }
}
