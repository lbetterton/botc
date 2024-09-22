<?php
header('Content-Type: application/json');

// Memcached setup
$memcached = new Memcached();
$memcached->addServer('localhost', 11211);

// Key for storing data in Memcached
$cacheKey = 'settings';

// Try to get existing data from Memcached
$data = $memcached->get($cacheKey);

if (!$data) {
    // If no data in Memcached, set default values
    $data = [
        "time" => 300,
        "timer_running" => false,
        "reset_timer" => false,
        "day" => "Day 1",
        "background_image" => "url"
    ];
}

// Read the incoming form data
$field = $_POST['field'] ?? null;
$value = $_POST['value'] ?? null;

if ($field && isset($data[$field])) {
    if ($field === 'timer_running') {
        $value = $value === 'true' ? true : false;
    }
    if ($field === 'reset_timer') {
        $value = $value === 'true' ? true : false;
    }
    $data[$field] = $value;

    // Store the updated data in Memcached
    if ($memcached->set($cacheKey, $data)) {
        echo json_encode(["message" => "Settings saved successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error saving settings"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid field"]);
}
?>