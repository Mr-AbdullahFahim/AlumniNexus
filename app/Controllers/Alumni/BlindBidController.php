<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BlindBidModel;
use App\Models\SponsorshipModel;
use App\Models\MonthlyWinningStatsModel;

class BlindBidController extends BaseController
{
    use ResponseTrait;

    public function submit()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role != 2) {
            return $this->failForbidden('Only Alumni can place bids.');
        }

        $alumniId = $user->sub;

        $rules = [
            'amount' => 'required|decimal|greater_than_equal_to[1.00]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $json = $this->request->getJSON();
        $amount = (float) ($json->amount ?? $this->request->getVar('amount'));

        // Check Monthly Winning Limit
        $winStatsModel = new MonthlyWinningStatsModel();
        if ($winStatsModel->hasReachedMonthlyLimit($alumniId)) {
            return $this->failForbidden('You have reached the monthly winning quota of 3 wins. You cannot place bids for the rest of this month.');
        }

        $bidModel = new BlindBidModel();
        $cycleDate = $bidModel->getCurrentCycleDate();

        // Check Total Sponsorships for the cycle
        $sponsorshipModel = new SponsorshipModel();
        $totalSponsorships = $sponsorshipModel->getTotalForCycle($alumniId, $cycleDate);

        if ($amount > $totalSponsorships) {
            return $this->failValidationErrors([
                'amount' => 'Your bid amount ($' . number_format($amount, 2) . ') cannot exceed your total active sponsorships for this cycle ($' . number_format($totalSponsorships, 2) . ').'
            ]);
        }

        // Check if bid already exists for this cycle
        $existingBid = $bidModel->where('alumni_id', $alumniId)
                                ->where('bid_date', $cycleDate)
                                ->first();

        if ($existingBid) {
            if ($existingBid['status'] !== 'pending') {
                return $this->failForbidden('This bidding cycle has already been settled. Please wait for the next cycle to start at 6 PM.');
            }

            // Ensure new bid is strictly greater than the existing bid
            if ($amount <= (float) $existingBid['bid_amount']) {
                return $this->failValidationErrors([
                    'amount' => 'Your new bid must be greater than your current bid of $' . number_format($existingBid['bid_amount'], 2) . '.'
                ]);
            }

            // Increase/Update bid
            $bidModel->update($existingBid['id'], [
                'bid_amount' => $amount,
                // Status remains pending
            ]);
            return $this->respond([
                'status' => 'success',
                'message' => 'Bid increased successfully for the current cycle.'
            ]);
        } else {
            // Place new bid
            $bidModel->insert([
                'alumni_id' => $alumniId,
                'bid_date' => $cycleDate,
                'bid_amount' => $amount,
                'status' => 'pending'
            ]);
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Bid placed successfully for the current cycle.'
            ]);
        }
    }

    public function history()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role != 2) {
            return $this->failForbidden('Only Alumni can view bid history.');
        }

        $alumniId = $user->sub;
        $bidModel = new BlindBidModel();
        
        $bids = $bidModel->where('alumni_id', $alumniId)
                         ->orderBy('bid_date', 'DESC')
                         ->orderBy('created_at', 'DESC')
                         ->findAll();

        return $this->respond($bids);
    }
}
