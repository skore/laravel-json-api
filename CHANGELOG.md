# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
