<?php
header('Content-Type: application/json');

// Memcached setup
$memcached = new Memcached();
$memcached->addServer('localhost', 11211);

// Key for retrieving data from Memcached
$cacheKey = 'settings';

// Try to get existing data from Memcached
$data = $memcached->get($cacheKey);

if ($data) {
    echo json_encode($data);
} else {
    // If no data found, return a default structure
    echo json_encode([
        "time" => 300,
        "timer_running" => false,
        "reset_timer" => false,
        "day" => "Day 1",
        "background_image" => "url"
    ]);
}
?>
