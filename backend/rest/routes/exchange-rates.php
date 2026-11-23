<?php

$exchange_rate_service = new ExchangeRateService();

Flight::route('GET /api/v1/exchange-rates', function () use ($exchange_rate_service) {
    $query = Flight::request()->query;
    $base = $query['base'] ?? 'USD';

    api_run(function () use ($exchange_rate_service, $base) {
        return $exchange_rate_service->get_rates($base);
    });
});
