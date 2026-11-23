# shoe-store

- This is a shoe store.

## Backend setup

1. Run `composer install` inside `backend/`.
2. Copy `backend/config/config.example.php` to `backend/config/config.php`.
3. Set the database values and `exchange_rate_api_key()` in `backend/config/config.php`.
4. Create the `shoestore` database using the tables from the ERD.

## Run

From the repository root run `php -S localhost:8000 server.php`.

- API base URL: `http://localhost:8000/backend/api/v1`
- Swagger UI: `http://localhost:8000/backend/public/v1/docs/`
- The exchange-rate endpoint returns `401` or `403` when ExchangeRate-API rejects or forbids the configured key.
