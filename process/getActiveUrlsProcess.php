<?php

require_once 'classes/Url.php';

$database = new Database();
$connection = $database->getConnection();

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url = new Url($connection);
    $result = $url->getAvailableUrls();

    if ($result['success']) {
        echo json_encode($result);
    } else {
        echo json_encode([
            'success' => false,
            'info'    => 'An error has occurred while retrieving the available URLs.',
            'data'    => null
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'info'    => 'An error has occurred while retrieving the available URLs.',
        'data'    => null
    ]);
}
