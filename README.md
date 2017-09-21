[![Run Status](https://api.shippable.com/projects/59b1544eb54e3d0700e6495f/badge?branch=master)](https://app.shippable.com/bitbucket/swisnl/laravel-api)
[![Coverage Badge](https://api.shippable.com/projects/59b1544eb54e3d0700e6495f/coverageBadge?branch=master)](https://app.shippable.com/bitbucket/swisnl/laravel-api)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://choosealicense.com/licenses/mit/)

# Laravel-Api
Set up a laravel API in just a few minutes with this package. All the standard API functionality is already there for you.

This package strives to save you some time while building your API. 
It already has the basic features an API should have, like:

* A generator to generate your needed files for each model
* An abstract layer to handle your basic CRUD actions
* Support for a few useful URL parameters
* Permission and route permission handling
* Responses in json api format (http://jsonapi.org/)
* Automatically translates your models based on your database

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

## Sample 
Please see the folder [sample](sample) for a sample of an application using this package.

## Usage

### Base Api Classes
There are a few base classes your classes should/could inherit:

##### BaseApiController
This controller handles your basic CRUD actions as well as permissions if you choose to use permissions.

##### BaseApiRepository
This repository is a standard base repository with a small addition that it figures out your models relationships. 

If you want to use your own BaseRepository, you have to implement the RepositoryInterface. This ensures that you have full compatibility with the BaseApiController.

The BaseApiRepository uses a trait to retrieve a models relationships. You can use this trait if you want to use the existing implementation.

##### BaseApiSchema
Every model needs a schema to be mapped for Json Api response. This schema needs to inherit the BaseApiSchema. You can override the methods from the BaseApiSchema and if the existing implementation can't figure out the resource type, you can override that as well.

#### Relationships
If you don't like the magic that happens to figure out the relationships. You can override the getRelations() in your model to return an array with your relationships. It should be an array of strings. For example: ['posts', 'comments'].

### Generating the required files
After installing the package you can instantly generate all the required files by executing this command:

``` bash
$ php artisan laravel-api:generate-all {Model}
```

To override the default path without overriding the laravel_generator config file, you can use the `--path={path}` option. For example:
``` bash
$ php artisan laravel-api:generate-all Test --path=app/temp/
```

This generates the following files:

* An eloquent model
* A translation model: not mandatory. If you won't use it. You can just delete it after generating
* A schema for your model: used to convert your model to a json api format
* An API controller
    * Should extend the BaseApiController
* A repository for your model
    * Could extend the BaseApiRepository
* A policy for checking permissions
* 2 tests and a testing trait

If everything is generated, all you have to do is write some endpoints for your new controller and you're good to go.

You'll be able to do the basic CRUD actions without writing anything.

You also have the ability to generate the files separately:
``` bash
$ php artisan laravel-api:generate-controller {Name}
$ php artisan laravel-api:generate-model {Name}
$ php artisan laravel-api:generate-translation {Name}
$ php artisan laravel-api:generate-schema {Name}
$ php artisan laravel-api:generate-policy {Name}
$ php artisan laravel-api:generate-repository {Name}
$ php artisan laravel-api:generate-migration {Name}
```


### Configuration
If you would like to override the configuration files, you can add `--tag=laravel-api` to your artisan publish call. There are two configuration files:
* infyom.laravel_generator
    * This is for the basic generator classes provided by InfyOmLabs
* laravel_api
    * This is for the config of the generator classes added by this package
### Requests and responses
All requests and responses are formatted according to the format specified by http://jsonapi.org/.

There are several respond methods at your disposal in your controller. The following respond methods are implemented at this moment:
``` php
return $this->respondWithOk($object);
return $this->respondWithPartialContent($object);
return $this->respondWithCreated($object);
return $this->respondWithNoContent($object);
return $this->respondWithCollection($object);
``` 

These methods automatically converts your objects to json api format and creates a response with the correct status code and body.

### Using policies
If you decide to use policies to check for the user's pemissions you have to add the policies to your Providers\AuthServicePorvider.

``` php
 protected $policies = [
     Sample::class => SamplePolicy::class,
 ];
 
 public function boot()
 {
     $this->registerPolicies();
 }    
```

You will also have to change the 'checkForPermissions()' method in your controllers to return true.

If you have methods in your own controller and you would like to check for permissions. Add the following line in your methods:
``` php
$this->validateUser();
``` 

If you want to check if they can request a specific object you can add that object as the first parameter:
``` php
$this->validateUser($requestedObject);
```

If  you want to redirect the validation to a specific function in your policy you can write that action as the second parameter:
``` php
$this->validateUser($requestedObject, 'show');
```

### URL parameters out of the box
The following URL parameters are supported after installing this package:

* ?include={relationship}: To add all relationship data to the response
* ?page={pageNumber}: To decide which page the pagination should show
* ?per_page={amountToShowPerPage}:To decide how many items you get per page
* ?ids={commaSeperatedIds}: To retrieve a collection of objects belonging to the ids
* ?lang={language}: (Requires the configure-locale middleware) to change the php locale to the desired language and automatically translates all translatable models

### Optional middleware
There are 3 optional middlewares:

* inspect_content_type: Highly recommended. It ensures that the requests should be in json format. If it's in another format it throws a ContentTypeNotSupportedException.
* route_permission_middleware: used to check if a user has permission to acces an API endpoint
* configure-locale: used to configure the language for translating your responses. Also configurable by using the URL paramater ?lang={language}

### Generating missing schemas 
If by chance you are in need of a schema but you don't have a model for that schema in your own repository. You can use the following command to generate schemas based on the relationships of the given model:

``` bash
$ php artisan laravel-api:generate-missing-schemas {Model}
```

Keep in mind that this schema will also needs a repository. In the future this command will also generate this for you.

## Packages Laravel-Api uses

##### Laravel framework
* https://laravel.com/docs/5.4/

##### InfyOmLabs / laravel-generator
* http://labs.infyom.com/laravelgenerator/docs/5.4/introduction
    * laravelcollective / html
    * infyomlabs / adminlte-templates
    * infyomlabs / swagger-generator
    * jlapp / swaggervel
    

##### Dimsav / laravel-translatable
* https://github.com/dimsav/laravel-translatable#tutorials

##### spatie / laravel-permission
* https://github.com/spatie/laravel-permission

##### neomerx / json-api
* https://github.com/neomerx/json-api



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
