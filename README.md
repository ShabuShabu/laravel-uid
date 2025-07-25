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
php artisan vendor:publish --tag="uid-config"
```

### Prefixes

You will then have to add all your models to the `prefixes` array in the config file:

```php
return [
    // ...
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

The first step for every model should be to add the provided `HasUid` trait and the `Identifiable` interface. This also ensures that route model binding works as expected with UIDs.

```php
use ShabuShabu\Uid\Concerns\HasUid;
use ShabuShabu\Uid\Contracts\Identifiable;

class User extends Model implements Identifiable
{
    use HasUid;
}
```

### Alphabets per model

If you want to use custom alphabets for your models, you need to add them to the config file. Any model that does not use a custom alphabet will use the default alphabet.

```php
return [
    // ...
    'alphabets' => [
        'usr' => 'abcdefgh...'
    ],
];
```

### Encode from an id

```php
use ShabuShabu\Uid\Service\Uid;

$uid = Uid::make()->encodeFromId(User::class, 1);

// something like: usr_86Rf07xd4z
```

### Encode a model

```php
use ShabuShabu\Uid\Facades\Uid;

// using the facade...
$uid = Uid::encode(User::find(1));

// something like: usr_86Rf07xd4z
```

### Decode a uid

```php
// using the global function
$decoded = uid()->decode('usr_86Rf07xd4z');

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
use ShabuShabu\Uid\Facades\Uid;

$alias = Uid::alias(User::class);

// returns usr
```

### Model info based on a UID

If you have a UID and would like some info about it, then you can use the following command:

```bash
php artisan uid:info
```

### Using your prefixes as the morph map

You need to either set the `UID_MORPH_MAP_ENABLED` env variable to `true` or enable it directly in the config file.

By default, this will enable an enforced morph map, but you are free to change this to a regular one.

```php
return [
    // ...
    'morph_map' => [
        'enabled' => true,
        'type' => 'regular',
    ],
];
```

### A word of warning

Please note that this package does **not** work with string or compound primary keys. The underlying [Squids](https://github.com/sqids/sqids-php) library supports the encoding of an integer array, so compound primary keys might eventually be supported.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrade guide

Please see [UPGRADE](UPGRADE.md) for details.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Squids](https://github.com/sqids/sqids-php)
- [ShabuShabu](https://github.com/ShabuShabu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
