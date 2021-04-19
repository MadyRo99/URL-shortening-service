<?php

require_once 'config/Database.php';
require_once 'config/Connection.php';

$database = new Database();
$connection = $database->getConnection();

if ($connection == NULL) {
    $database->createDatabase();
}

require_once(__DIR__ . '/router/router.php');

// GET Routes

get('/URL-shortening-service', function() {
    require_once('resources/views/index.php');
});

get('/URL-shortening-service/activeUrls', function() {
    require_once('resources/views/activeUrls.php');
});

get('/URL-shortening-service/process/getActiveUrlsProcess', function() {
    require_once('process/getActiveUrlsProcess.php');
});

get('/URL-shortening-service/:short_url/invalid', function() {
    require_once('resources/views/invalid.php');
});

get('/URL-shortening-service/:short_url/expired', function() {
    require_once('resources/views/expired.php');
});

get('/URL-shortening-service/:short_url', function($short_url) {
    require_once('process/redirectToUrl.php');
});

// POST Routes

post('/URL-shortening-service/process/shortenUrlProcess', function() {
    require_once('process/shortenUrlProcess.php');
});