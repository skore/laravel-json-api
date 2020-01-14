<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

trait CollectsResources
{
    /**
     * Map the given collection resource into its individual resources.
     *
     * @param mixed $resource
     *
     * @return mixed
     */
    protected function collectResource($resource)
    {
        if ($resource instanceof MissingValue) {
            return $resource;
        }

        $collects = $this->collects();

        $this->collection = $collects && !$resource->first() instanceof $collects
            ? $this->getFiltered($resource, $collects)
            : $resource->toBase();

        return $resource instanceof AbstractPaginator
            ? $resource->setCollection($this->collection)
            : $this->collection;
    }

    /**
     * Get resource collection filtered by authorisation.
     *
     * @param mixed $resource
     * @param mixed $collects
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFiltered($resource, $collects)
    {
        if ($resource instanceof AbstractPaginator) {
            $resource = $resource->getCollection();
        }

        $collection = $resource->map(function ($item) use ($collects) {
            $authoriseKey = 'viewAny';
            $authorise = $this->authorise;

            if (!$this->getAuthorisableConfig($authoriseKey) && gettype($this->authorise) !== 'boolean') {
                $authorise = Gate::check($authoriseKey, class_basename($item));
            }

            return new $collects($item, $authorise);
        });

        return $collection->filter(function (JsonApiResource $item) {
            return !$item->resource instanceof MissingValue;
        });
    }

    /**
     * Get the resource that this resource collects.
     *
     * @return string|null
     */
    protected function collects()
    {
        if ($this->collects) {
            return $this->collects;
        }

        if (Str::endsWith(class_basename($this), 'Collection') &&
            class_exists($class = Str::replaceLast('Collection', '', get_class($this)))) {
            return $class;
        }
    }

    /**
     * Get an iterator for the resource collection.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }
}
