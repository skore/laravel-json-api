<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Database\Eloquent\{Model, Collection};
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property mixed $resource
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
        if ($this->resource instanceof LengthAwarePaginator) {
            $this->resource->getCollection()->map(function (Model $model) {
                $this->attachRelations($model, true);
            });
        } else if ($this->resource instanceof Model) {
            $this->attachRelations($this->resource);
        }
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $collects
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
     * @param \Illuminate\Database\Eloquent\Model $relation
     * @return array
     */
    protected function processModelRelation(Model $relation)
    {
        if (count(array_filter($relation->getRelations())) > 0) {
            $this->attachRelations($relation);
            $relation->setRelations([]);
        }

        $relationResource = new JsonApiResource($relation, $this->authorize);

        $this->with['included'][] = $relationResource;

        return $relationResource->getResourceIdentifier();
    }
}
