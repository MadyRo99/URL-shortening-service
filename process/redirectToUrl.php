<?php

require_once 'classes/RedirectUrl.php';

$database = new Database();
$connection = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($short_url) ) {
    $short_url = strtolower(stripslashes(trim($short_url)));

    $url = new RedirectUrl($connection);
    $result = $url->redirectToUrl($short_url);

    if ($result['success']) {
        if (is_null($result['data'])) {
            header('Location: /' . $short_url . "/expired");
            return;
        } else if (strpos($result['data']['long_url'],'https://') === false && strpos($result['data']['long_url'],'http://') === false) {
            $result['data']['long_url'] = 'https://' . $result['data']['long_url'];
        }
        header("Location:" . $result['data']['long_url']);
        return;
    } else {
        header('Location: /' . $short_url . "/invalid");
        return;
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'info'    => 'The URL is empty.',
        'data'    => null
    ]);
}
