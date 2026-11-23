<?php

class Config
{
    public static function DB_HOST()
    {
        return 'localhost';
    }

    public static function DB_PORT()
    {
        return 3306;
    }

    public static function DB_NAME()
    {
        return 'shoestore';
    }

    public static function DB_USER()
    {
        return 'root';
    }

    public static function DB_PASSWORD()
    {
        return '';
    }

    public static function exchange_rate_api_key()
    {
        return 'YOUR_EXCHANGE_RATE_API_KEY';
    }

    public static function exchange_rate_api_url()
    {
        return 'https://v6.exchangerate-api.com/v6';
    }

    public static function exchange_rate_timeout_seconds()
    {
        return 5;
    }

    public static function exchange_rate_connect_timeout_seconds()
    {
        return 3;
    }
}
