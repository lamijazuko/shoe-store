<?php

class ExchangeRateService
{
    public function get_rates($base = 'USD')
    {
        $base = strtoupper(trim((string) $base));
        if (preg_match('/^[A-Z]{3}$/', $base) !== 1) {
            throw new InvalidArgumentException('base must be a 3-letter currency code.');
        }

        $api_key = Config::exchange_rate_api_key();
        if ($api_key === '' || $api_key === 'YOUR_EXCHANGE_RATE_API_KEY') {
            throw new RuntimeException('Exchange rate API key is not configured.');
        }

        if (!function_exists('curl_init')) {
            throw new RuntimeException('cURL extension is required for exchange rates.');
        }

        $url = rtrim(Config::exchange_rate_api_url(), '/')
            . '/' . rawurlencode($api_key)
            . '/latest/' . rawurlencode($base);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, (int) Config::exchange_rate_connect_timeout_seconds());
        curl_setopt($curl, CURLOPT_TIMEOUT, (int) Config::exchange_rate_timeout_seconds());
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false || $error !== '') {
            throw new RuntimeException('Exchange rate provider is unavailable.');
        }

        if (in_array($status, [401, 403], true)) {
            $message = $status === 401 ? 'Exchange rate API key is invalid.' : 'Exchange rate access is forbidden.';
            throw new RuntimeException($message, $status);
        }

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException('Exchange rate provider returned an error.');
        }

        $payload = json_decode($response, true);
        if (!is_array($payload) || ($payload['result'] ?? '') !== 'success') {
            throw new RuntimeException('Exchange rate provider returned invalid data.');
        }

        return [
            'base' => $payload['base_code'] ?? $base,
            'date' => $payload['time_last_update_utc'] ?? null,
            'rates' => $payload['conversion_rates'] ?? [],
        ];
    }
}
