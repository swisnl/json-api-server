# laravel-api

## Install

Via Composer

``` bash
$ composer require swisnl/laravel-api
```
Add the following to your config/app.php file:
``` php
'providers' => [
 // Other providers
 Swis\LaravelApi\Providers\LaravelApiServiceProvider::class,
 ],
```

## Usage

### Generating the required files
After installing the package you can instantly generate all the required files by executing this command:
``` bash
$ php artisan laravel-api:generate-all {Model}
```

This generates the following files:
- An eloquent model
- A translation model: not mandatory. If you won't use it. You can just delete it after generating
- A schema for your model: used to convert your model to a json api format
- An API controller
- A repository for your model
- A policy for checking permissions
- 2 tests and a testing trait

If everything is generated, all you have to do is write some endpoints for your new controller and you're good to go.

You'll be able to do the basic CRUD actions without writing anything.

### Optional middleware
There are 2 optional middlewares:
- route_permission_middleware: used to check if a user has permission to acces an API endpoint
- configure-locale: used to configure the language for translating your responses. Also configurable by using the URL paramater ?lang={language}

### URL parameters out of the box
The following URL parameters are supported after installing this package:
- ?include={relationship}: To include all data the relationship has
- ?page={pageNumber}: To decide which page the pagination should show
- ?per_page={amountToShowPerPage}:To decide how many items you get per page
- ?ids={commaSeperatedIds}: To retrieve a collection of objects belonging to the ids
- ?lang={language}: (Requires the configure-locale middleware) to change the php locale to the desired language and automatically translates all translatable models

### Requests and responses
All requests and responses are according to the format specified by http://jsonapi.org/. To encode your response to the correct format you should call JsonEncoder's method: encodeData(). 

### Generating schemas 
If by chance you are in need of a schema but you don't have a model for that schema in your own repository. You can use the following command to generate schemas based on the relationships of the given model:

``` bash
php artisan laravel-api:generate-schemas {Model}
```

Keep in mind that this schema will also need a repository. In the future this command will also generate this for you.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ddewit@swis.nl instead of using the issue tracker.

## Credits

- [Dylan de Wit][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/swisnl/laravel-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/swisnl/laravel-api/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/swisnl/laravel-api.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/swisnl/laravel-api.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/swisnl/laravel-api.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/swisnl/laravel-api
[link-travis]: https://travis-ci.org/swisnl/laravel-api
[link-scrutinizer]: https://scrutinizer-ci.com/g/swisnl/laravel-api/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/swisnl/laravel-api
[link-downloads]: https://packagist.org/packages/swisnl/laravel-api
[link-author]: https://github.com/DylandeWit
[link-contributors]: ../../contributors
