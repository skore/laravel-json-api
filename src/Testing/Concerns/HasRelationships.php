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
    /**
     * @var array
     */
    protected $relationships;

    /**
     * @var array
     */
    protected $includeds;

    /**
     * Assert on the related resource by its model instance.
     * 
     * @param \Illuminate\Database\Eloquent\Model $model 
     * @return \SkoreLabs\JsonApi\Testing\Concerns\HasRelationships 
     */
    public function atRelation(Model $model)
    {
        $item = head(array_filter($this->includeds, function ($included) use ($model) {
            return $included['type'] === JsonApi::getResourceType($model) && $included['id'] == $model->getKey();
        }));

        return new self($item['id'], $item['type'], $item['attributes'], $item['relationships'] ?? [], $this->includeds);
    }

    /**
     * Assert that a resource has any relationship and included (optional) by type.
     * 
     * @param mixed $name 
     * @param bool $withIncluded 
     * @return $this 
     */
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
    
    /**
     * Assert that a resource does not have any relationship and included (optional) by type.
     * 
     * @param mixed $name 
     * @param bool $withIncluded 
     * @return $this 
     */
    public function hasNotAnyRelationships($name, $withIncluded = false)
    {
        $type = JsonApi::getResourceType($name);

        PHPUnit::assertFalse(count(array_filter($this->relationships, function ($relation) use ($type) {
            return $relation['data']['type'] === $type;
        })) > 0, sprintf('There is a relationship with type "%s" for resource "%s"', $type, $this->getIdentifierMessageFor()));

        if ($withIncluded) {
            PHPUnit::assertFalse(count(array_filter($this->includeds, function ($included) use ($type) {
                return $included['type'] === $type;
            })) > 0, sprintf('There is a included relationship with type "%s"', $type));
        }

        return $this;
    }

    /**
     * Assert that a resource has any relationship and included (optional) by model instance.
     * 
     * @param \Illuminate\Database\Eloquent\Model $model 
     * @param bool $withIncluded 
     * @return $this 
     */
    public function hasRelationshipWith(Model $model, $withIncluded = false)
    {
        $type = JsonApi::getResourceType($model);

        PHPUnit::assertTrue(count(array_filter($this->relationships, function ($relation) use ($type, $model) {
            return $relation['data']['type'] === $type && $relation['data']['id'] == $model->getKey();
        })) > 0, sprintf('There is no relationship "%s" for resource "%s"', $this->getIdentifierMessageFor($model->getKey(), $type), $this->getIdentifierMessageFor()));

        if ($withIncluded) {
            PHPUnit::assertTrue(count(array_filter($this->includeds, function ($included) use ($type, $model) {
                return $included['type'] === $type && $included['id'] == $model->getKey();
            })) > 0, sprintf('There is no included relationship "%s"', $this->getIdentifierMessageFor($model->getKey(), $type)));
        }

        return $this;
    }

    /**
     * Assert that a resource does not have any relationship and included (optional) by model instance.
     * 
     * @param \Illuminate\Database\Eloquent\Model $model 
     * @param bool $withIncluded 
     * @return $this 
     */
    public function hasNotRelationshipWith(Model $model, $withIncluded = false)
    {
        $type = JsonApi::getResourceType($model);

        PHPUnit::assertFalse(count(array_filter($this->relationships, function ($relation) use ($type, $model) {
            return $relation['data']['type'] === $type && $relation['data']['id'] == $model->getKey();
        })) > 0, sprintf('There is a relationship "%s" for resource "%s"', $this->getIdentifierMessageFor($model->getKey(), $type), $this->getIdentifierMessageFor()));

        if ($withIncluded) {
            PHPUnit::assertFalse(count(array_filter($this->includeds, function ($included) use ($type, $model) {
                return $included['type'] === $type && $included['id'] == $model->getKey();
            })) > 0, sprintf('There is a included relationship "%s"', $this->getIdentifierMessageFor($model->getKey(), $type)));
        }

        return $this;
    }
}
