# Laravel `vite()` helper method

[![Latest Version on Packagist](https://img.shields.io/packagist/v/motomedialab/laravel-vite-helper.svg?style=flat-square)](https://packagist.org/packages/motomedialab/laravel-vite-helper)
[![Total Downloads](https://img.shields.io/packagist/dt/motomedialab/laravel-vite-helper.svg?style=flat-square)](https://packagist.org/packages/motomedialab/laravel-vite-helper)
[![Build Status](https://img.shields.io/travis/motomedialab/laravel-vite-helper/master.svg?style=flat-square)](https://travis-ci.org/motomedialab/laravel-vite-helper)  
![GitHub Actions](https://github.com/motomedialab/laravel-vite-helper/actions/workflows/main.yml/badge.svg)

A super simple Laravel helper to fill the void that Laravel Mix left. Mix had a helper, aptly named `mix()`
that would return an absolute URL to the mix resource.  With the introduction of Vite (as of Laravel 9.19),
there is no equivalent for Vite, at least, until now.

This was originally submitted as a [PR](https://github.com/laravel/framework/pull/43098) to the core Laravel framework,
but unfortunately wasn't deemed as a necessary addition.

## Installation

You can install the package via composer:

```bash
composer require motomedialab/laravel-vite-helper
```

## Usage

The usage for this helper is extremely simple, and directly replaces Laravel's `mix()` helper.

```php
// will return the compiled asset path for 'resources/css/app.css'
vite('resources/css/app.css');
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email chris@motocom.co.uk instead of using the issue tracker.

## Credits

-   [Chris Page](https://github.com/motomedialab)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
