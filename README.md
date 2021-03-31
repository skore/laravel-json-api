![json-api-logo](https://jsonapi.org/images/jsonapi.png)

<h1 align="center">Laravel JSON:API</h1>

[![latest tag](https://img.shields.io/github/v/tag/skore/laravel-json-api?label=latest&sort=semver)](https://github.com/skore/laravel-json-api/releases/latest) [![packagist version](https://img.shields.io/packagist/v/skore-labs/laravel-json-api)](https://packagist.org/packages/skore-labs/laravel-json-api) [![run-tests](https://github.com/skore/laravel-json-api/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/skore/laravel-json-api/actions/workflows/tests.yml) [![Psalm](https://github.com/skore/laravel-json-api/actions/workflows/psalm.yml/badge.svg?branch=master)](https://github.com/skore/laravel-json-api/actions/workflows/psalm.yml) [![StyleCI](https://github.styleci.io/repos/198988581/shield?style=flat&branch=master)](https://github.styleci.io/repos/198988581) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/aa7cce89305c47f7bf9b0684b97e90ca)](https://www.codacy.com/app/d8vjork/laravel-json-api?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=skore/laravel-json-api&amp;utm_campaign=Badge_Grade) [![Scc Count Badge](https://sloc.xyz/github/skore/laravel-json-api?category=code)](https://github.com/skore/laravel-json-api) [![Scc Count Badge](https://sloc.xyz/github/skore/laravel-json-api?category=comments)](https://github.com/skore/laravel-json-api)

Integrate JSON:API resources on Laravel.

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
            User::all()
        );
    }
}
```

### Authorisation

For authorize a resource or collection you'll need the `view` and `viewAny` on the **model policy**, which you can create passing the model to the make command:

```
php artisan make:policy UserPolicy -m User
```

Alternatively, you can pass an authorisation (boolean) to the constructor of the resource like this:

```php
// Forced to allow view the user
return new JsonApiResource($user, true);
```

## Features

- Full formatting using pure built-in model methods and properties.
- Relationships and nested working with [eager loading](https://laravel.com/docs/master/eloquent-relationships#eager-loading).
- Permissions "out-of-the-box" authorising each resource view or list of resources.
- Auto-hide not allowed attributes from responses like `user_id` or `post_id`.

## Credits

- Ruben Robles ([@d8vjork](https://github.com/d8vjork))
- Skore ([https://www.getskore.com/](https://www.getskore.com/))
- [And all the contributors](https://github.com/skore-labs/laravel-json-api/graphs/contributors)
