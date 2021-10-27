
# Laravel Mild Database Session Driver

[![Latest Stable Version](https://poser.pugx.org/rolandstarke/laravel-mild-database-session-driver/v/stable)](https://packagist.org/packages/rolandstarke/laravel-mild-database-session-driver)
[![LICENSE](https://img.shields.io/packagist/l/rolandstarke/laravel-mild-database-session-driver.svg)](https://github.com/rolandstarke/laravel-mild-database-session-driver/blob/master/LICENSE)

Does the same as default database session driver with the benefit that the the session is only written to the database if it changed to reduce the database load.

## Installation

Install with composer.

```bash
composer require rolandstarke/laravel-mild-database-session-driver
```

Make sure the session table exists.

```bash
php artisan session:table

php artisan migrate
```

In your `.env` file change the `SESSION_DRIVER` to `mild_database`.

```env
SESSION_DRIVER=mild_database
```

## License

[MIT](LICENSE)
