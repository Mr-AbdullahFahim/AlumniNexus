<?php

$db = new SQLite3(__DIR__ . '/writable/database/alumni_nexus.db');

$hour = (int) date('H');
if ($hour >= 18) {
    $currentCycle = date('Y-m-d', strtotime('+1 day'));
    $nextCycleEndTime = date('Y-m-d 18:00:00', strtotime('+1 day'));
} else {
    $currentCycle = date('Y-m-d');
    $nextCycleEndTime = date('Y-m-d 18:00:00');
}

$db->exec("UPDATE settings SET setting_value = '{$currentCycle}', updated_at = datetime('now') WHERE setting_key = 'current_cycle_date'");
$db->exec("UPDATE settings SET setting_value = '{$nextCycleEndTime}', updated_at = datetime('now') WHERE setting_key = 'next_cycle_end_time'");

echo "Updated settings! current_cycle_date: $currentCycle, next_cycle_end_time: $nextCycleEndTime\n";
