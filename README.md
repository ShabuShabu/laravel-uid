# UIDs for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shabushabu/laravel-uid.svg?style=flat-square)](https://packagist.org/packages/boris-glumpler/laravel-uid)
[![Total Downloads](https://img.shields.io/packagist/dt/shabushabu/laravel-uid.svg?style=flat-square)](https://packagist.org/packages/boris-glumpler/laravel-uid)

Add Stripe-like universal ids to your models that can be decoded back to their integer ids.

## Installation

> [!CAUTION]
> Please note that this is a new package and, even though it is well tested, it should be considered pre-release software

You can install the package via composer:

```bash
composer require shabushabu/laravel-uid
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-uid-config"
```

### Prefixes

You will then have to add all your models to the `prefixes` array in the config file:

```php
return [
    'prefixes' => [
        'usr' => \App\Models\User::class,
    ],
];
```

### Create a custom alphabet

Run the following command and add the output to your `.env` file:

```bash
php artisan uid:alphabet
```

## Usage

The first step for every model should be to add the provided `HasUid` trait. This ensures that route model binding works as expected with UIDs.

```php
use ShabuShabu\Uid\Concerns\HasUid;

class User extends Model
{
    use HasUid;
}
```

### Encode from an id

```php
use ShabuShabu\Uid\Service\Uid;

$uid = Uid::make()->encodeFromId(User::class, 1);

// something like: usr_86Rf07xd4z
```

### Encode a model

```php
use ShabuShabu\Uid\Service\Uid;

$uid = Uid::make()->encode(User::find(1));

// something like: usr_86Rf07xd4z
```

### Decode a uid

```php
use ShabuShabu\Uid\Service\Uid;

$decoded = Uid::make()->decode('usr_86Rf07xd4z');

// returns an instance of DecodedUid::class
```

### Decode to a model

```php
use ShabuShabu\Uid\Service\Uid;

$model = Uid::make()->decodeToModel('usr_86Rf07xd4z');

// returns an instance of User::class

$model = Uid::make()->withTrashed()->decodeToModel('usr_86Rf07xd4z');

// returns an instance of User::class, even if the model is trashed
```

### Check if a uid is valid

```php
use ShabuShabu\Uid\Service\Uid;

$valid = Uid::make()->isValid('usr_86Rf07xd4z');

// returns true if the prefix exists

$valid = Uid::make()->isValid('usr_86Rf07xd4z', User::class);

// returns true if the prefix exists and belongs to the class
```

### Retrieve the alias of a given class

```php
use ShabuShabu\Uid\Service\Uid;

$alias = Uid::alias(User::class);

// returns usr
```

### Bonus idea

Use the prefixes as your morph map:

```php
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Relation::enforceMorphMap(config('uid.prefixes'));
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Boris Glumpler](https://github.com/boris-glumpler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
