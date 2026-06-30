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
        // In a real application, we would fetch this data from Models (BlindBids, Sponsorships, etc.)
        // based on the currently authenticated user's ID.
        // For now, we return mock structured data to populate the UI and charts.

        $data = [
            'featured_alumni' => [
                'is_featured' => true,
                'message' => "Congratulations! You are today's Featured Alumni."
            ],
            'widgets' => [
                'current_bid' => [
                    'amount' => '$450.00',
                    'trend' => 'up',
                    'trend_value' => '+12.5%'
                ],
                'sponsorships' => [
                    'active_sponsors' => 12,
                    'total_amount' => '$1,250.00'
                ],
                'winning_status' => 'Leading',
                'remaining_wins' => 2 // Monthly limit is typically 3
            ],
            'charts' => [
                'bid_history' => [
                    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    'data' => [120, 190, 300, 250, 400, 380, 450]
                ],
                'sponsorship_distribution' => [
                    'labels' => ['Corporate', 'Individual', 'Academic', 'Other'],
                    'data' => [45, 25, 20, 10]
                ]
            ],
            'recent_activity' => [
                [
                    'id' => 1,
                    'type' => 'bid',
                    'description' => 'You placed a blind bid of $450.',
                    'time' => '2 hours ago'
                ],
                [
                    'id' => 2,
                    'type' => 'sponsor',
                    'description' => 'TechCorp sponsored you for $500.',
                    'time' => '1 day ago'
                ],
                [
                    'id' => 3,
                    'type' => 'win',
                    'description' => 'You won Featured Alumni status.',
                    'time' => '3 days ago'
                ]
            ]
        ];

        // Simulate network delay for loading effect
        usleep(500000); 

        return $this->respond($data);
    }
}
