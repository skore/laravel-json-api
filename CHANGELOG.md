# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
