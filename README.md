# 🕵️ Eloquent Inspector

[![Author][ico-author]][link-author]
[![PHP Version][ico-php]][link-php]
[![Laravel Version][ico-laravel]][link-laravel]
[![Octane Compatibility][ico-octane]][link-octane]
[![Build Status][ico-actions]][link-actions]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![PSR-12][ico-psr12]][link-psr12]
[![Total Downloads][ico-downloads]][link-downloads]

Inspect Laravel Eloquent models to collect properties, relationships and more.


## Install

Via Composer

``` bash
composer require cerbero/eloquent-inspector
```

## Usage

To inspect an Eloquent model, we can simply pass its class name to the `inspect()` method:

```php
use App\Models\User;
use Cerbero\EloquentInspector\Inspector;

$inspector = Inspector::inspect(User::class);
```

An `Inspector` singleton is created every time a new model is inspected, this lets us inspect the same model multiple times while running the inspection logic only once.

If we need to free memory or cleanup some inspected model information, we can either flush all model inspections, flush only one model inspection or tell an inspection to forget its data:

```php
// flush information of all inspected models
Inspector::flush();

// flush information of an inspected model
Inspector::flush(User::class);

// forget information of the current inspection
Inspector::inspect(User::class)->forget();
```

To retrieve the class of the inspected model from an `Inspector`, we can call `getModel()`:

```php
$model = Inspector::inspect(User::class)->getModel(); // App\Models\User
```

The method `getUseStatements()` returns an array with all the `use` statements of a model, keyed by either the class name or the alias:

```php
use Illuminate\Database\Eloquent\Model;
use Foo\Bar as Baz;

class User extends Model
{
    // ...
}

$useStatements = Inspector::inspect(User::class)->getUseStatements();
/*
[
    'Model' => 'Illuminate\Database\Eloquent\Model',
    'Baz' => 'Foo\Bar',
]
*/
```

Calling `getProperties()` performs a scan of the model database table and returns an array of `Property` instances containing the properties information. The array is keyed by the properties name:

```php
$properties = Inspector::inspect(User::class)->getProperties();
/*
[
    'id' => <Cerbero\EloquentInspector\Dtos\Property>,
    'name' => <Cerbero\EloquentInspector\Dtos\Property>,
    ...
]
*/

$properties['id']->name; // id
$properties['id']->type; // int
$properties['id']->dbType; // integer
$properties['id']->nullable; // false
$properties['id']->default; // null
```

To inspect the relationships of a model, we can call the method `getRelationships()`. The result is an array of `Relationship` instances, keyed by the relationship name, containing all the relationships information:

```php
$relationships = Inspector::inspect(User::class)->getRelationships();
/*
[
    'posts' => <Cerbero\EloquentInspector\Dtos\Relationship>,
    'tags' => <Cerbero\EloquentInspector\Dtos\Relationship>,
    ...
]
*/

$relationships['posts']->name; // posts
$relationships['posts']->type; // hasMany
$relationships['posts']->class; // Illuminate\Database\Eloquent\Relations\HasMany
$relationships['posts']->model; // App\Models\Post
$relationships['posts']->relatesToMany; // true
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email andrea.marco.sartori@gmail.com instead of using the issue tracker.

## Credits

- [Andrea Marco Sartori][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-author]: https://img.shields.io/static/v1?label=author&message=cerbero90&color=50ABF1&logo=twitter&style=flat-square
[ico-php]: https://img.shields.io/packagist/php-v/cerbero/eloquent-inspector?color=%234F5B93&logo=php&style=flat-square
[ico-laravel]: https://img.shields.io/static/v1?label=laravel&message=%E2%89%A58.0&color=ff2d20&logo=laravel&style=flat-square
[ico-octane]: https://img.shields.io/static/v1?label=octane&message=compatible&color=ff2d20&logo=laravel&style=flat-square
[ico-version]: https://img.shields.io/packagist/v/cerbero/eloquent-inspector.svg?label=version&style=flat-square
[ico-actions]: https://img.shields.io/github/workflow/status/cerbero90/eloquent-inspector/build?style=flat-square&logo=github
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-psr12]: https://img.shields.io/static/v1?label=compliance&message=PSR-12&color=blue&style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cerbero90/eloquent-inspector.svg?style=flat-square&logo=scrutinizer
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cerbero90/eloquent-inspector.svg?style=flat-square&logo=scrutinizer
[ico-downloads]: https://img.shields.io/packagist/dt/cerbero/eloquent-inspector.svg?style=flat-square

[link-author]: https://twitter.com/cerbero90
[link-php]: https://www.php.net
[link-laravel]: https://laravel.com
[link-octane]: https://github.com/laravel/octane
[link-packagist]: https://packagist.org/packages/cerbero/eloquent-inspector
[link-actions]: https://github.com/cerbero90/eloquent-inspector/actions?query=workflow%3Abuild
[link-psr12]: https://www.php-fig.org/psr/psr-12/
[link-scrutinizer]: https://scrutinizer-ci.com/g/cerbero90/eloquent-inspector/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cerbero90/eloquent-inspector
[link-downloads]: https://packagist.org/packages/cerbero/eloquent-inspector
[link-contributors]: ../../contributors
