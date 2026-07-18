<?php

namespace App\Controllers\Sponsor;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SponsorshipModel;
use App\Models\BlindBidModel;

class DashboardController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $data = [
            'title' => 'Sponsor Dashboard',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Sponsor Dashboard', 'url' => base_url('sponsor/dashboard')]
            ]
        ];

        return view('sponsor/dashboard', $data);
    }

    public function getStats()
    {
        $sponsorId = $this->request->user->sub;

        $sponsorshipModel = new SponsorshipModel();
        $bidModel = new BlindBidModel();
        
        $cycles = $sponsorshipModel->getSponsorHistory($sponsorId);
        $currentCycleDate = $bidModel->getCurrentCycleDate();

        $db = \Config\Database::connect();
        $settingsQuery = $db->table('settings')->where('setting_key', 'next_cycle_end_time')->get();
        $nextEndTimeRow = $settingsQuery->getRow();
        $hour = (int) date('H');
        $fallbackTime = ($hour >= 18) ? date('Y-m-d 18:00:00', strtotime('+1 day')) : date('Y-m-d 18:00:00');
        $nextEndTime = $nextEndTimeRow ? $nextEndTimeRow->setting_value : $fallbackTime;

        $data = [
            'cycles' => $cycles,
            'current_cycle_date' => $currentCycleDate,
            'server_time' => date('c'),
            'next_cycle_end_time' => $nextEndTime
        ];

        return $this->respond($data);
    }

    public function getGlobalHistory()
    {
        $db = \Config\Database::connect();
        $bidModel = new BlindBidModel();
        $sponsorshipModel = new SponsorshipModel();
        
        $currentCycleDate = $bidModel->getCurrentCycleDate();
        
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
            
            $history[] = [
                'cycle_date' => $w['bid_date'],
                'winner_id' => (int)$w['alumni_id'],
                'winner_name' => $w['alumni_name'],
                'winning_bid' => (float)$w['bid_amount'],
                'sponsors' => $sponsors
            ];
        }
        
        return $this->respond(['history' => $history]);
    }
}
