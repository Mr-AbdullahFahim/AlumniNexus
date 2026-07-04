<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class DashboardController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data = [
            'title' => 'Alumni Dashboard',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Alumni Dashboard', 'url' => base_url('alumni/dashboard')]
            ]
        ];

        return view('alumni/dashboard', $data);
    }

    public function getStats()
    {
        $alumniId = $this->request->user->sub;

        $bidModel = new \App\Models\BlindBidModel();
        $sponsorshipModel = new \App\Models\SponsorshipModel();
        $featuredModel = new \App\Models\FeaturedAlumniModel();
        $winStatsModel = new \App\Models\MonthlyWinningStatsModel();

        $cycleDate = $bidModel->getCurrentCycleDate();

        // 1. Featured Alumni (Today)
        $today = date('Y-m-d');
        $isFeatured = $featuredModel->where('alumni_id', $alumniId)
                                    ->where('featured_date', $today)
                                    ->countAllResults() > 0;

        // 2. Build Cycles List
        $cycles = [];
        
        // Current Active Cycle
        $currentBid = $bidModel->where('alumni_id', $alumniId)->where('bid_date', $cycleDate)->first();
        $currentSponsorships = $sponsorshipModel->getSponsorshipsForCycle($alumniId, $cycleDate);
        $currentTotal = array_sum(array_column($currentSponsorships, 'amount'));
        
        $cycles[] = [
            'id' => 'current',
            'date' => date('M d, Y', strtotime($cycleDate)),
            'raw_date' => $cycleDate,
            'is_active' => true,
            'status' => $currentBid ? $currentBid['status'] : 'pending',
            'bid_amount' => $currentBid ? (float)$currentBid['bid_amount'] : 0,
            'total_sponsorships' => $currentTotal,
            'sponsors' => $currentSponsorships,
            'remaining_winnings' => 0
        ];

        // 3. Remaining Wins
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $stat = $winStatsModel->where('alumni_id', $alumniId)
                              ->where('year_val', $currentYear)
                              ->where('month_val', $currentMonth)
                              ->first();
        $winsCount = $stat ? (int)$stat['wins_count'] : 0;
        $remainingWins = max(0, 3 - $winsCount);

        // 4. Historical Cycles
        $db = \Config\Database::connect();
        
        $bids = $db->table('blind_bids')
                   ->select('bid_date')
                   ->where('alumni_id', $alumniId)
                   ->where('bid_date !=', $cycleDate)
                   ->get()
                   ->getResultArray();
        
        $sponsorships = $db->table('sponsorships')
                           ->select('created_at')
                           ->where('alumni_id', $alumniId)
                           ->get()
                           ->getResultArray();
                           
        $historicalDates = [];
        foreach ($bids as $b) {
            $historicalDates[] = $b['bid_date'];
        }
        foreach ($sponsorships as $s) {
            $createdAt = strtotime($s['created_at']);
            $hour = (int) date('H', $createdAt);
            $cDate = ($hour >= 18) ? date('Y-m-d', strtotime('+1 day', $createdAt)) : date('Y-m-d', $createdAt);
            if ($cDate !== $cycleDate) {
                $historicalDates[] = $cDate;
            }
        }
        
        $historicalDates = array_unique($historicalDates);
        rsort($historicalDates); // Sort descending
                             
        foreach ($historicalDates as $hDate) {
            $bid = $bidModel->where('alumni_id', $alumniId)->where('bid_date', $hDate)->first();
            $pastSponsorships = $sponsorshipModel->getSponsorshipsForCycle($alumniId, $hDate);
            $pastTotal = array_sum(array_column($pastSponsorships, 'amount'));
            
            $status = $bid ? $bid['status'] : 'lost';
            $bidAmount = $bid ? (float)$bid['bid_amount'] : 0;
            $remaining = ($status === 'won') ? max(0, $pastTotal - $bidAmount) : 0;
            
            $cycles[] = [
                'id' => $bid ? $bid['id'] : 'no_bid_'.$hDate,
                'date' => date('M d, Y', strtotime($hDate)),
                'raw_date' => $hDate,
                'is_active' => false,
                'status' => $status,
                'bid_amount' => $bidAmount,
                'total_sponsorships' => $pastTotal,
                'sponsors' => $pastSponsorships,
                'remaining_winnings' => $remaining
            ];
        }

        $data = [
            'alumni_id' => $alumniId,
            'featured_alumni' => [
                'is_featured' => $isFeatured,
                'message' => "Congratulations! You are today's Featured Alumni."
            ],
            'cycles' => $cycles,
            'remaining_wins' => $remainingWins,
            'quota_reached' => $winsCount >= 3,
            'server_time' => date('c')
        ];

        return $this->respond($data);
    }

    public function getGlobalHistory()
    {
        $db = \Config\Database::connect();
        $bidModel = new \App\Models\BlindBidModel();
        $sponsorshipModel = new \App\Models\SponsorshipModel();
        
        $currentCycleDate = $bidModel->getCurrentCycleDate();
        $currentUserId = $this->request->user->id ?? session()->get('user_id');
        
        $winners = $db->table('blind_bids')
                      ->select('blind_bids.*, users.name as alumni_name')
                      ->join('users', 'users.id = blind_bids.alumni_id')
                      ->where('blind_bids.status', 'won')
                      ->where('blind_bids.bid_date <', $currentCycleDate)
                      ->orderBy('blind_bids.bid_date', 'DESC')
                      ->get()
                      ->getResultArray();
                      
        $history = [];
        foreach ($winners as $w) {
            $sponsors = $sponsorshipModel->getSponsorshipsForCycle($w['alumni_id'], $w['bid_date']);
            
            $currentUserBid = $db->table('blind_bids')
                                 ->where('alumni_id', $currentUserId)
                                 ->where('bid_date', $w['bid_date'])
                                 ->get()
                                 ->getRowArray();
            $myBid = $currentUserBid ? (float)$currentUserBid['bid_amount'] : null;

            $history[] = [
                'cycle_date' => $w['bid_date'],
                'winner_id' => (int)$w['alumni_id'],
                'winner_name' => $w['alumni_name'],
                'winning_bid' => (float)$w['bid_amount'],
                'sponsors' => $sponsors,
                'my_bid' => $myBid,
                'is_me' => ((int)$w['alumni_id'] === (int)$currentUserId)
            ];
        }
        
        return $this->respond(['history' => $history]);
    }
}
