![json-api-logo](https://jsonapi.org/images/jsonapi.png)

<h1 align="center">Laravel JSON:API</h1>

[![latest tag](https://img.shields.io/github/v/tag/skore/laravel-json-api?label=latest&sort=semver)](https://github.com/skore/laravel-json-api/releases/latest) [![packagist version](https://img.shields.io/packagist/v/skore-labs/laravel-json-api)](https://packagist.org/packages/skore-labs/laravel-json-api) [![run-tests](https://github.com/skore/laravel-json-api/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/skore/laravel-json-api/actions/workflows/tests.yml) [![Psalm](https://github.com/skore/laravel-json-api/actions/workflows/psalm.yml/badge.svg?branch=master)](https://github.com/skore/laravel-json-api/actions/workflows/psalm.yml) [![StyleCI](https://github.styleci.io/repos/198988581/shield?style=flat&branch=master)](https://github.styleci.io/repos/198988581) [![Codacy Badge](https://app.codacy.com/project/badge/Grade/032829b3392846c1864b912ba9d0aa90)](https://www.codacy.com/gh/skore/laravel-json-api/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=skore/laravel-json-api&amp;utm_campaign=Badge_Grade) [![Codacy Badge](https://app.codacy.com/project/badge/Coverage/032829b3392846c1864b912ba9d0aa90)](https://www.codacy.com/gh/skore/laravel-json-api/dashboard?utm_source=github.com&utm_medium=referral&utm_content=skore/laravel-json-api&utm_campaign=Badge_Coverage) [![Scc Count Badge](https://sloc.xyz/github/skore/laravel-json-api?category=code)](https://github.com/skore/laravel-json-api) [![Scc Count Badge](https://sloc.xyz/github/skore/laravel-json-api?category=comments)](https://github.com/skore/laravel-json-api) [![Take a peek on VSCode online](https://img.shields.io/badge/vscode-Take%20a%20peek-blue?logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAACJklEQVQoFYVSS2hTURCdue8l7cv3NT8bYrMqCtYitRZEKKIrwW6kGy0obtzpoqCIC/GBoEUptKDQLgRBKIo7FUW6EHFTN1XxW900IAZFbEiwecn9jPNSGkWwDgz3ztxzzswwF+E/Nujdj8Ri0dPRTGHUyeSnmwT3arF4BTfiDU7MF22CC4LoeDxXENFcEYjgtQG6+U/i0JUn3dRUM4g0wmgrni1AdFOR6wQUXLV3XHu5hSg8LBU8fD++rQxAOODN56Uv5/i+t9URGaUIpTLgBDEBRbBv+u0zvu3h8Kk2NCFWSnVEaxIQhwIQ669y1dls70DWdqJjBCACqq2MniHC7Qi4j/vvJzsiofGzG4QVsD4LglNuyn1MHZHzynCtQI3NXip8uN1b2trJiSlCkYFIhgVZ1K9+stA6unT54PMAOHynGhxtE33vwGp8K2m5Ulaq6QPPAcpJgUz2VPxkTwd4gQrngvwfLr7XwscUwaRs1F3540tdKvVVcksKxC5lzFy+89VYi8gtMY6d39htxpzRRscR4Y0gdQIFaq3hBoDp5yELYOBW5uKLnX6jEbND4XarNhl9xADuN026W7k+WgpeUpcWD5PGqyx8oDUwmZO1atWPd6W57bXVt7YJnofsPMFvc7yFzRaEpwjwEBglXNeFRCq3DjBr9PXwrzN2bjErQzQLWo8wMZRI5yTvdxk3+nJtDW8hEdbW2USya3fCTT8wWj9aLqc//gJwC/Y8vXqalgAAAABJRU5ErkJggg==)](https://vscode.dev/github/skore/laravel-json-api)

Integrate JSON:API resources on Laravel.

## Features

- Compatible and tested with all the Laravel LTS supported versions ([see them here](https://laravel.com/docs/master/releases#support-policy))
- Full formatting using pure built-in model methods and properties.
- Relationships and nested working with [eager loading](https://laravel.com/docs/master/eloquent-relationships#eager-loading).
- Permissions "out-of-the-box" authorising each resource view or list of resources.
- Auto-hide not allowed attributes from responses like `user_id` or `post_id`.
- Own testing utilities built-in Laravel's ones to make integration tests easier.

## Installation

You can install the package via composer:

```
composer require skore-labs/laravel-json-api
```

## Usage

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

### Custom resource type

To customise the resource type of a model you should do like this:

```php
<?php

namespace SkoreLabs\JsonApi\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use SkoreLabs\JsonApi\Contracts\JsonApiable;

class Post extends Model implements JsonApiable
{
    /**
     * Get a custom resource type for JSON:API formatting.
     * 
     * @return string 
     */
    public function resourceType(): string
    {
        return 'custom-post';
    }
}
```

**Note: Just remember to check the allowed types in [the oficial JSON:API spec](https://jsonapi.org/format/#document-member-names).**

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

## Testing

```php
public function testGetPostApi()
{
    $this->get('/posts/1')->assertJsonApi(function (Assert $json) {
        $json->hasId(1)
            ->hasType('post')
            ->hasAttribute('title', 'My summer vacation');
    });
}
```

### Collections

In case of a test that **receives a JSON:API collection** as a response, **the first item will be the defaulted** on all the methods that tests **attributes and relationships**.

In case you want to access a specific item you have the `at` method:

```php
public function testGetPostsListApi()
{
    $this->get('/posts')->assertJsonApi(function (Assert $json) {
        // We're testing the first post here!
        $json->hasId(1)
            ->hasType('post')
            ->hasAttribute('title', 'My summer vacation @ Italy');

        // Now we want to test the second post of the collection
        $json->at(1)
            ->hasId(2)
            ->hasType('post')
            ->hasAttribute('title', 'What is really great for your diet');
    });
}
```

**Note: Remember that this method takes in mind the array keys, so the first item is at 0, not 1!**

## Credits

- Ruben Robles ([@d8vjork](https://github.com/d8vjork))
- Skore ([https://www.getskore.com/](https://www.getskore.com/))
- [And all the contributors](https://github.com/skore-labs/laravel-json-api/graphs/contributors)
