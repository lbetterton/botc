<?php
header('Content-Type: application/json');

// Memcached setup
$memcached = new Memcached();
$memcached->addServer('localhost', 11211);

// Key for the settings
$cacheKey = 'settings';

// Attempt to delete the key from Memcached
if ($memcached->delete($cacheKey)) {
    echo json_encode(["message" => "Settings key flushed successfully."]);
} else {
    echo json_encode(["message" => "Error flushing settings key or key does not exist."]);
}
?>
