::: tip
We strongly recommend to use another package to manage the requests filtering, sorting, appending, etc. Check [Implementations](implementations.md).
:::

# Usage

How to use this on your Laravel website.

## Custom resource type

To customise the resource type of a model you should:

1. Add `SkoreLabs\JsonApi\Contracts\JsonApiable` contract to the model class.
2. And add `resourceType` method to the model returning the type as a string.

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

::: tip
Just remember to check the allowed types in [the oficial JSON:API spec](https://jsonapi.org/format/#document-member-names).
:::

## Authorisation

For authorize a resource or collection you'll need the `view` and `viewAny` on the **model policy**, which you can create passing the model to the make command:

```
php artisan make:policy UserPolicy -m User
```

### Bypass authorisation locally

Alternatively, you can pass an authorisation (boolean) to the constructor of the resource like this:

```php
// Forced to allow view the user
return new JsonApiResource($user, true);
```

### Bypass authorisation globally

::: tip
For this you need to be able to modify the config file of this package. If you miss how to expose it to your project check the [Getting started](README.md#getting-started) on the Installation page.
:::

You could also disable all the authorisation globally by setting to true `view` and `viewAny` on the `config/json-api.php`.

Remember that `view` is checked for any single resource meanwhile `viewAny` is for check once one is on a resource collection of the same type.

## Custom API resource class

Adding the `JSON_SERIALIZER` constant to your model class will point to a customised API resource:

```php
/**
 * The class that serialize this model to JSON.
 */
public const JSON_SERIALIZER = App\Http\Resources\PostResource::class;
```

Also your JSON:API resource class should look like this:

```php
<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use SkoreLabs\JsonApi\Http\Resources\JsonApiResource;

class PostResource extends JsonApiResource
{
    /**
     * Attach with the resource model relationships.
     *
     * @return void
     */
    protected function withRelationships()
    {
        if ($this->resource) {
            $this->resource->loadMissing('author');
        }

        parent::withRelationships();
    }

    /**
     * Attach additional attributes data.
     *
     * @return array
     */
    protected function withAttributes()
    {
        return [
            'is_first_visit' => $this->last_accessed_at === null,
            $this->mergeWhen(Auth::user() instanceof User && $this->author->id === Auth::id(), [
                'is_author' => true,
            ]),
        ];
    }
}
```
