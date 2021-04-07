<?php

require_once 'classes/ShortenUrl.php';

$database = new Database();
$connection = $database->getConnection();

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST["long_url"]) ) {
    $long_url = strtolower(stripslashes(trim($_POST["long_url"])));
    $pattern = '/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';

    if (preg_match($pattern, $long_url)) {
        $url = new ShortenUrl($connection);
        $result = $url->shortenUrl($long_url);
        echo json_encode($result);
    } else {
        echo json_encode([
            'success' => false,
            'info'    => 'The URL is not valid.',
            'data'    => null
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'info'    => 'The URL is empty.',
        'data'    => null
    ]);
}
