<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
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
            ? $this->refreshPaginator($resource)
            : $this->collection;
    }

    /**
     * Undocumented function.
     *
     * @param \Illuminate\Pagination\AbstractPaginator $resource
     *
     * @return void
     */
    protected function refreshPaginator(AbstractPaginator $resource)
    {
        return (new LengthAwarePaginator(
            $this->collection,
            $this->collection->count(),
            $resource->perPage(),
            $resource->currentPage(),
            $resource->getOptions()
        ))->setPageName($resource->getPageName());
    }

    /**
     * Get resource collection filtered by authorization.
     *
     * @param mixed $resource
     * @param mixed $collects
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFiltered($resource, $collects)
    {
        /** @var \Illuminate\Support\Collection $collection */
        $collection = $resource->map(function ($item) use ($collects) {
            $authorize = $this->authorize;

            if (gettype($this->authorize) !== 'boolean') {
                $authorize = Auth::user()->can('viewAny', class_basename($item));
            }

            return new $collects($item, $authorize);
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
