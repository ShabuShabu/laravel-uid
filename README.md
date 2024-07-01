# UIDs for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shabushabu/laravel-uid.svg?style=flat-square)](https://packagist.org/packages/boris-glumpler/laravel-uid)
[![Total Downloads](https://img.shields.io/packagist/dt/shabushabu/laravel-uid.svg?style=flat-square)](https://packagist.org/packages/boris-glumpler/laravel-uid)

Add Stripe-like universal ids to your models that can be decoded back to their integer ids.

## Installation

> [!CAUTION]
> Please note that this package completely takes over the morph map of your app, so don't install this package if you don't want that!


You can install the package via composer:

```bash
composer require shabushabu/laravel-uid
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-uid-config"
```

## Usage



```php

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
