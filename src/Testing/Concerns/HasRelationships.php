<?php

namespace SkoreLabs\JsonApi\Testing\Concerns;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert as PHPUnit;
use SkoreLabs\JsonApi\Support\JsonApi;

/**
 * @mixin \SkoreLabs\JsonApi\Testing\Assert
 */
trait HasRelationships
{
    public function atRelation(Model $model)
    {
        $item = head(array_filter($this->includeds, function ($included) use ($model) {
            return $included['type'] === JsonApi::getResourceType($model) && $included['id'] == $model->getKey();
        }));

        return new self($item['id'], $item['type'], $item['attributes'], $item['relationships'] ?? [], $this->includeds);
    }

    public function hasAnyRelationships($name, $withIncluded = false)
    {
        $type = JsonApi::getResourceType($name);

        PHPUnit::assertTrue(count(array_filter($this->relationships, function ($relation) use ($type) {
            return $relation['data']['type'] === $type;
        })) > 0);

        if ($withIncluded) {
            PHPUnit::assertTrue(count(array_filter($this->includeds, function ($included) use ($type) {
                return $included['type'] === $type;
            })) > 0);
        }

        return $this;
    }

    public function hasRelationshipWith(Model $model, $withIncluded = false)
    {
        $type = JsonApi::getResourceType($model);

        PHPUnit::assertTrue(count(array_filter($this->relationships, function ($relation) use ($type, $model) {
            return $relation['data']['type'] === $type && $relation['data']['id'] == $model->getKey();
        })) > 0);

        if ($withIncluded) {
            PHPUnit::assertTrue(count(array_filter($this->includeds, function ($included) use ($type, $model) {
                return $included['type'] === $type && $included['id'] == $model->getKey();
            })) > 0);
        }

        return $this;
    }
}
