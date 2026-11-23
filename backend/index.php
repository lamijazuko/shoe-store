<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/rest/routes/_helpers.php';

Flight::map('notFound', function () {
    api_halt(404, 'Endpoint not found.');
});

Flight::map('error', function ($error) {
    api_halt(500, 'Internal server error.');
});

require_once __DIR__ . '/rest/routes/api.php';

Flight::start();
