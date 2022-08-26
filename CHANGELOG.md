# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.2.8] - 2022-08-26

### Added

- Support for [hammerstone/fast-paginate](https://github.com/hammerstonedev/fast-paginate)

## [3.2.7] - 2022-07-15

### Fixed

- Missing autoload-dev, tests were autoloaded with the released version (ouch!)

## [3.2.6] - 2022-05-17

### Fixed

- Missing app container binding for support facade usage

## [3.2.5] - 2022-04-06

### Fixed

- Pagination handling when cursor pagination is being used on resources
- Minor removals of warnings and deprecations on newer versions of PHP

## [3.2.4] - 2022-03-22

### Fixed

- Assert testing utilities with hasMany relationships

## [3.2.3] - 2022-02-18

### Changed

- Suggested package from Spatie's to our own, read further here: https://github.com/skore/laravel-query-builder#important

## [3.2.2] - 2022-02-17

### Fixed

- `hasAttribute` / `hasAttributes` now accepts array as values

## [3.2.1] - 2022-02-12

### Fixed

- Some extra dependencies not needed :/

## [3.2.0] - 2022-01-14

## Added

- Laravel 9 support
- Testing utilities with more useful failure messages and docblocks
- PHPStan for static analysis (Github full integration still in progress...)
- Tests to some nested relationships
- Code analysis thanks to @codacy

## Changed

- `hasSize` renamed to `count` (original will be still maintained as an alias)
- Internal package API: Moved `Assert` props to traits

## [3.1.0] - 2021-10-28

## Added

- JSON:API pagination to `Illuminate\Database\Query\Builder` class
- Testing utilities (as macros of `Illuminate\Foundation\Testing\TestResponse`): `hasId`, `hasType`, `hasAttribute`, `hasAttributes`, `atRelation`, `hasRelationshipWith`, `hasAnyRelationships`, `at`, `hasSize`
- `SkoreLabs\JsonApi\Support\JsonApi` utilities facade
- `SkoreLabs\JsonApi\Contracts\JsonApiable` contract interface for custom resource types

## [3.0.1] - 2021-01-20

## Added

- Support for PHP 8

## [3.0.0] - 2020-11-30

## Added

- Support for Laravel 8

## Removed

- Support for Laravel 5

## [2.0.2] - 2020-11-11

### Added

- Relations with custom API resource classes

## [2.0.1] - 2020-07-02

### Added

- Normalize relationships names (check [config](https://github.com/skore/laravel-json-api/blob/master/config/json-api.php))

## [2.0.0] - 2020-04-21

### Added

- `Collection::paginate()` macro with params and config for maximum per page results
- Config file (*json-api.php*) with some configurable behavior of the package
- 3rd parameter to `JsonApiCollection` class constructor for customise the resource that collects
- `JsonApiResource::withAttributes()` method for add additional attributes from child resources
- Laravel 7 compatibility

### Changed

- Now JsonApiCollection constructor **ONLY** accepts Laravel collections and arrays of models
- `includes` now are `included` following JSON:API specs
- Removed usage of Auth facade (replaced by Gate), allowing anonymous authorisations

### Removed

- **Pagination refresh on JsonApiCollection**
- Composer suggested package: [spatie/laravel-json-api-paginate](https://github.com/spatie/laravel-json-api-paginate)
- **Drop compatibility with unsupported Laravel 5.x versions**

## [1.2.1] - 2019-09-10

### Fixed

- Composer dependencies

## [1.2.0] - 2019-09-10

### Added

- Compatibility with Laravel 6.0

### Changed

- Allow include of Pivot relationships

### Fixed

- Resource on included on JsonApiCollection when include param not present

## [1.1.2] - 2019-09-05

### Fixed

- Included repeated on JsonApiResource

## [1.1.1] - 2019-08-28

### Fixed

- Some pagination bugs

## [1.1.0] - 2019-08-16

### Added

- Second parameter on **JsonApiCollection** for force authorization on resource list
- Use of the _snake_case_ 🐍 for object types (see [member names](https://jsonapi.org/format/#document-member-names))
- Changelog following [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

### Removed

- Temporary use of PSR-12 on some classes (we'll still use PSR-2 and all the approved ones)

### Fixed

- Multiple bugs on the pagination when resources are not authorised and it still show old count of items
- When _included_ show empty on **JsonApiCollection**

## [1.0.2] - 2019-07-30

### Fixed

- Prevent pivot relationships to be included

## [1.0.1] - 2019-07-30

### Fixed

- The whole package functionality

## [1.0.0] - 2019-07-29

### Added

- Initial commit of the package
- Package published on Packagist (composer)
