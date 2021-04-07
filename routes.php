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

get('/', function() {
    require_once('resources/views/index.php');
});

get('/activeUrls', function() {
    require_once('resources/views/activeUrls.php');
});

get('/process/getActiveUrlsProcess', function() {
    require_once('process/getActiveUrlsProcess.php');
});

get('/:short_url/invalid', function() {
    require_once('resources/views/invalid.php');
});

get('/:short_url/expired', function() {
    require_once('resources/views/expired.php');
});

get('/:short_url', function($short_url) {
    require_once('process/redirectToUrl.php');
});

// POST Routes

post('/process/shortenUrlProcess', function() {
    require_once('process/shortenUrlProcess.php');
});