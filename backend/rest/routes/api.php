<?php

require_once __DIR__ . '/exchange-rates.php';

Flight::route('GET /api/v1/health', function () {
    api_run(function () {
        return [
            'app' => 'ShoeStore API',
            'status' => 'ok',
        ];
    });
});

Flight::group('/api/v1', function () {
    require_once __DIR__ . '/crud.php';
});
