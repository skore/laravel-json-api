<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;

/**
 * @property mixed $resource
 * @property array $with
 * @property bool $authorize
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
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    protected function attachRelations(Model $model)
    {
        $relations = array_filter($model->getRelations());

        foreach ($relations as $relation => $relationObj) {
            if ($relationObj instanceof Collection) {
                /** @var \Illuminate\Database\Eloquent\Model $relationModel */
                foreach ($relationObj->all() as $relationModel) {
                    $this->relationships[$relation]['data'][] = $this->processModelRelation(
                        $relationModel
                    );
                }
            }

            if ($relationObj instanceof Model && !$relationObj instanceof Pivot) {
                $this->relationships[$relation]['data'] = $this->processModelRelation(
                    $relationObj
                );
            }
        }
    }

    /**
     * Process a model relation attaching to its model additional attributes.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    protected function processModelRelation(Model $model)
    {
        $modelResource = new JsonApiResource($model, $this->authorize);

        $this->addIncluded([$modelResource],
            Arr::get($modelResource->with, 'included', [])
        );

        return $modelResource->getResourceIdentifier();
    }

    /**
     * Set included data to resource's with.
     *
     * @param array $arrays,...
     *
     * @return void
     */
    protected function addIncluded(...$arrays)
    {
        Arr::set($this->with, 'included',
            array_merge(Arr::get($this->with, 'included', []), ...$arrays)
        );
    }
}
