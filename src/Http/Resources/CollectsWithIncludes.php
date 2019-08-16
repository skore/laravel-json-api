<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property \Illuminate\Support\Collection $collection
 * @property array $with
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
            if ($jsonResource->with) {
                $this->addIncluded(Arr::get($jsonResource->with, 'included'));
            }
        }

        $this->with['included'] = $this->uniqueIncludes();
    }

    /**
     * Post-process for unique relationship includes.
     *
     * @return array
     */
    protected function uniqueIncludes()
    {
        $includedCollection = Collection::make(
            Arr::get($this->with, 'included', [])
        )->flatten();

        return $includedCollection->unique(static function (JsonApiResource $resource) {
            return implode('', $resource->getResourceIdentifier());
        })->values()->all();
    }
}
