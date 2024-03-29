<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property mixed $resource
 * @property array $with
 * @property bool  $authorise
 */
trait RelationshipsWithIncludes
{
    /**
     * Included relations on the resource.
     *
     * @var array
     */
    protected $relationships;

    /**
     * Attach with the resource model relationships.
     *
     * @return void
     */
    protected function withRelationships()
    {
        if ($this->resource instanceof Model) {
            $this->attachRelations($this->resource);
        }
    }

    /**
     * Attach relationships to the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    protected function attachRelations(Model $model)
    {
        $relations = array_filter($model->getRelations(), static function ($value, $key) {
            return $key !== 'pivot' ?: (bool) $value === false;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($relations as $relation => $relationObj) {
            if (config('json-api.normalize_relations', false)) {
                $relation = Str::snake($relation);
            }

            if ($relationObj instanceof DatabaseCollection) {
                /** @var \Illuminate\Database\Eloquent\Model $relationModel */
                foreach ($relationObj->all() as $relationModel) {
                    $this->relationships[$relation]['data'][] = $this->processModelRelation(
                        $relationModel
                    );
                }
            }

            if ($relationObj instanceof Model) {
                $this->relationships[$relation]['data'] = $this->processModelRelation(
                    $relationObj
                );
            }
        }
    }

    /**
     * Process a model relation attaching to its model additional attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    protected function processModelRelation(Model $model)
    {
        $modelResourceClass = $this->getModelResource($model);
        /** @var \SkoreLabs\JsonApi\Http\Resources\JsonApiResource $modelResource */
        $modelResource = new $modelResourceClass($model, $this->authorise);
        $modelIdentifier = $modelResource->getResourceIdentifier();

        if (! empty(Arr::get($modelIdentifier, $model->getKeyName(), null))) {
            $this->addIncluded($modelResource);

            return $modelIdentifier;
        }

        return [];
    }

    /**
     * Set included data to resource's with.
     *
     * @param $resource
     * @return void
     */
    protected function addIncluded(JsonApiResource $resource)
    {
        $includesCol = Collection::make([
            $resource,
            array_values($this->getIncluded()),
            array_values($resource->getIncluded()),
        ])->flatten();

        $includesArr = $this->checkUniqueness(
            $includesCol
        )->values()->all();

        if (! empty($includesArr)) {
            Arr::set($this->with, $this->getIncludedConfig(), $includesArr);
        }
    }

    /**
     * Get resource included relationships.
     *
     * @return array
     */
    public function getIncluded()
    {
        return $this->with[$this->getIncludedConfig()] ?? [];
    }

    /**
     * Check and return unique resources on a collection.
     *
     * @param \Illuminate\Support\Collection
     * @return \Illuminate\Support\Collection
     */
    protected function checkUniqueness(Collection $collection)
    {
        return $collection->unique(static function ($resource) {
            return implode('', $resource->getResourceIdentifier());
        });
    }

    /**
     * Get API resource from model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    protected function getModelResource(Model $model)
    {
        return defined(get_class($model).'::JSON_SERIALIZER')
            ? $model::JSON_SERIALIZER
            : JsonApiResource::class;
    }
}
