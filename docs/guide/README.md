# Introduction

Install with the following command:

```sh
composer require skorelabs/laravel-json-api
```

## Getting started

First publish the config file once installed like this:

```sh
php artisan vendor:publish --provider="SkoreLabs\JsonApi\JsonApiServiceProvider"
```

And use as simple as importing the class `SkoreLabs\JsonApi\Http\Resources\JsonApiCollection` for collections or `SkoreLabs\JsonApi\Http\Resources\JsonApiResource` for resources.

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