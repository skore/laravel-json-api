![json-api-logo](https://jsonapi.org/images/jsonapi.png)

<h1 align="center">Laravel JSON:API</h1>

Integrate JSON:API resources on Laravel.

**Note: This package is under initial development stage, please use it carefully on production.**

## Installation

You can install the package via composer:

```
composer require skore-labs/laravel-json-api
```

### Usage

As simple as importing the class `SkoreLabs\JsonApi\Http\Resources\JsonApiCollection` for collections or `SkoreLabs\JsonApi\Http\Resources\JsonApiResource` for resources.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SkoreLabs\JsonApi\Http\Resources\JsonApiCollection;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new JsonApiCollection(
            User::simplePaginate()
        );
    }
}
```

## Features

- Full formatting using pure built-in model methods and properties.
- Relationships and nested working with [eager loading](https://laravel.com/docs/master/eloquent-relationships#eager-loading).
- Permissions "out-of-the-box" authorising each resource view or list of resources.
- Auto-hide not allowed attributes from responses like `user_id` or `post_id`.

## Recommendations

These packages are recommended (and suggested by the package installation) for a better integration with JSON:API standards:

- [spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder)
- [spatie/json-api-paginate](https://github.com/spatie/laravel-json-api-paginate)

## Credits

- Ruben Robles ([@d8vjork](https://github.com/d8vjork))
- Skore ([https://www.getskore.com/](https://www.getskore.com/))
- [And all the contributors](https://github.com/skore-labs/laravel-json-api/graphs/contributors)
