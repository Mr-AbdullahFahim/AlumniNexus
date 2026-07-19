<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SponsorshipModel;
use App\Models\UserModel;

class SponsorController extends BaseController
{
    use ResponseTrait;

    public function submit()
    {
        if (!isset($this->request->user->sub)) {
            return $this->failUnauthorized('You must be logged in to sponsor an alumni.');
        }
        $viewerId = $this->request->user->sub;

        $userModel = new UserModel();
        $viewer = $userModel->find($viewerId);
        
        // Check if the user has the Sponsor role (role_id = 4)
        if (!$viewer || $viewer['role_id'] != 4) {
            return $this->failForbidden('Only sponsors can perform this action.');
        }

        $rules = [
            'alumni_id' => 'required|is_natural_no_zero',
            'amount'    => 'required|decimal|greater_than_equal_to[1.00]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $json = $this->request->getJSON();
        $alumniId = $json->alumni_id ?? $this->request->getVar('alumni_id');
        $amount = $json->amount ?? $this->request->getVar('amount');

        $sponsorshipModel = new SponsorshipModel();
        
        // Check if the alumni has reached their monthly win limit
        $winStatsModel = new \App\Models\MonthlyWinningStatsModel();
        if ($winStatsModel->hasReachedMonthlyLimit($alumniId)) {
            return $this->failForbidden('This alumni has reached the monthly winning quota and cannot receive further sponsorships this month.');
        }

        // Note: We now allow multiple sponsorships per alumni from the same sponsor.

        $bidModel = new \App\Models\BlindBidModel();
        $currentCycle = $bidModel->getCurrentCycleDate();
        if (!$currentCycle) {
            return $this->failForbidden('The bidding cycle has not been initialized yet. Please try again later.');
        }

        $data = [
            'sponsor_id' => $viewerId,
            'alumni_id'  => $alumniId,
            'amount'     => $amount,
            'status'     => 'active',
            'cycle_date' => $currentCycle
        ];

        if ($sponsorshipModel->insert($data)) {
            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Sponsorship submitted successfully.',
                'data'    => $data
            ]);
        }

        return $this->failServerError('Failed to submit sponsorship. Please try again.');
    }

    public function history($alumniId)
    {
        $viewerId = $this->request->user->sub ?? null;
        if (!$viewerId) {
            return $this->failUnauthorized('You must be logged in to view sponsorship history.');
        }

        $sponsorshipModel = new SponsorshipModel();
        
        // Fetch only sponsorships from the logged-in user to this specific alumni
        $sponsorships = $sponsorshipModel->select('sponsorships.*, users.name as sponsor_name')
                                         ->join('users', 'users.id = sponsorships.sponsor_id')
                                         ->where('sponsorships.alumni_id', $alumniId)
                                         ->where('sponsorships.sponsor_id', $viewerId)
                                         ->orderBy('sponsorships.created_at', 'DESC')
                                         ->findAll();

        // Group by Bidding Cycle Date
        $grouped = [];
        foreach ($sponsorships as $s) {
            $cycleDate = $s['cycle_date'];

            if (!isset($grouped[$cycleDate])) {
                $grouped[$cycleDate] = [
                    'id' => $s['id'], // Using first ID for unique key
                    'sponsor_name' => $s['sponsor_name'],
                    'date' => $cycleDate,
                    'created_at' => $s['created_at'],
                    'amount' => 0.0,
                    'status' => $s['status']
                ];
            }
            $grouped[$cycleDate]['amount'] += (float)$s['amount'];
        }

        // Format numbers back to string for consistency
        // Also format date nicely for the UI
        foreach ($grouped as &$g) {
            $g['amount'] = number_format($g['amount'], 2, '.', '');
            $g['display_date'] = date('M d, Y', strtotime($g['date'])) . ' Cycle';
        }

        return $this->respond([
            'status' => 'success',
            'data'   => array_values($grouped)
        ]);
    }
}
