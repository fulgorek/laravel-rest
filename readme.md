# Simple Laravel - JWT API

Requirements

- PHP 5.5.x
- Composer


## Installation

Install all dependencies

```composer install```

Modify your DB settings

Rename your ```.env.example``` file to ```.env``` and change your settings.

Make your ```cipher``` key:

```php artisan key:generate```

Run database migrations

```php artisan migrate```

Seed the database

```php artisan db:seed```


## Notes:
Default credentials

Username: ```admin@site.com```

Password: ```password```


## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
