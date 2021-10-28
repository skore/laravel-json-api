<?php

namespace SkoreLabs\JsonApi\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use SkoreLabs\JsonApi\Contracts\JsonApiable;
use SkoreLabs\JsonApi\Http\Resources\JsonApiCollection;
use SkoreLabs\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Support\Str;

class JsonApi
{
    /**
     * Format the input contents to JSON:API.
     * 
     * @param mixed $value 
     * @return \SkoreLabs\JsonApi\Http\Resources\JsonApiResource 
     */
    public static function format($value)
    {
        if ($value instanceof LengthAwarePaginator || $value instanceof Collection) {
            return JsonApiCollection::make($value);
        }

        return JsonApiResource::make($value);
    }

    /**
     * Get resource type from a model.
     * 
     * @param \Illuminate\Database\Eloquent\Model|string $model 
     * @return string 
     */
    public static function getResourceType($model)
    {
        if ($model instanceof JsonApiable) {
            return $model->resourceType();
        }

        return Str::snake(class_basename(is_string($model) ? $model : $model::class));
    }
}
