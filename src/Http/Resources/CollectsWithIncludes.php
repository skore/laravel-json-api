<?php

namespace SkoreLabs\JsonApi\Http\Resources;

/**
 * @property \Illuminate\Support\Collection $collection
 */
trait CollectsWithIncludes
{
    /**
     * Attach with the collects' resource models relationships.
     *
     * @return void
     */
    protected function withIncludes()
    {
        /** @var \SkoreLabs\JsonApi\Http\Resources\JsonApiResource $jsonResource */
        foreach ($this->collection->toArray() as $jsonResource) {
            $this->addIncluded($jsonResource);
        }
    }
}
