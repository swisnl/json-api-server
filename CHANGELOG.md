# Changelog

All Notable changes to `json-api-server` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 0.4.0

### Added

- Laravel 5.8 support.

## 0.3.7

### Fixed

- Fixed validation of objects through model rules (issue #26, thanks @bondas83)

## 0.3.6

### Fixed 

- Fixed getRelationships windows problem (issue #23)

## 0.3.5

### Fixed

- Extra check so that uncountable data is not counted when getting relationships (Issue #20, thanks @bondas83)

## 0.3.4

### Fixed

- Server should always respond with `Content-type: application/vnd.api+json`
- Server should not escape slashes in responses (issue #11)

## 0.3.3

### Fixed

- The `authentication_test.stub` called `PUT` instead of `PATCH` (issue #14)

## 0.3.2

### Fixed

- Enable disable middleware for tests and add "Accept: application/vnd.api+json" header when running test calls.

## 0.3.1

### Fixed 

- Fix inconsistent url in `authentication_test.stub` (issue #8)
- Route stub generated a route with the controller action `destroy` instead of `delete` (issue #9)
- As per specification, the server should allow using the `Accept` header in requests. (issue #12)

## 0.3.0

### Changed

- Added support for recent Laravel versions.
- Run tests on Travis instead of Shippable

## 0.2.1

### Changed

- Change LICENSE.md to default GitHub contents.

### Fixed

- Bugfix when using the 'all' parameter and the query returns an empty collection.

## 0.2 - 2018-03-12

### Added
- Changed namespace from `Swis\LaravelApi` to `Swis\JsonApi\Server`
- Changed commands from `laravel-api:generate` to `json-api-server:generate`
- Tests for commands
- Tests for BaseApiRepository
- Tests for BaseApiController
- Explanation on how to use laravel/passport

### Deprecated
- laravel-api:generate commands
- Swis\LaravelApi namespace

### Fixed
- README to reflect new changes
- laravel_api.php to reflect new namespace
- Stubs to use the proper namespaces
- Commands to reflect new namespaces
- Improved exception handling
- Repository interface so you can implement it 

### Removed
- Unnecessary packages from composer.json
- PermissionsMiddleware
- Exception renderers
- HasPermissionsChecks


## 0.1 - 2018-02-09

### Added
- Everything, initial release.

