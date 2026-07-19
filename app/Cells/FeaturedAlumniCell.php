<?php

namespace App\Cells;

class FeaturedAlumniCell
{
    public function render($compact = false, $rounded = false)
    {
        $db = \Config\Database::connect();
        $settingsQuery = $db->table('settings')->where('setting_key', 'current_cycle_date')->get();
        $cycleDateRow = $settingsQuery->getRow();
        $currentCycleDate = $cycleDateRow ? $cycleDateRow->setting_value : date('Y-m-d');
        
        $featured = $db->table('featured_alumni')
                       ->select('featured_alumni.*, users.name, profiles.photo_url, profiles.company, profiles.position, profiles.industry, profiles.graduation_year')
                       ->join('users', 'users.id = featured_alumni.alumni_id')
                       ->join('profiles', 'profiles.user_id = featured_alumni.alumni_id', 'left')
                       ->where('featured_date', $currentCycleDate)
                       ->get()
                       ->getRowArray();

        if (!$featured) {
            return '';
        }
        
        return view('cells/featured_alumni', ['alumni' => $featured, 'compact' => $compact, 'rounded' => $rounded]);
    }
}
