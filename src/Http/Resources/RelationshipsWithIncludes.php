<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Database\Eloquent\{Model, Collection};
use Illuminate\Support\{Str, Collection as SupportCollection};

trait RelationshipsWithIncludes
{
    /**
     * Resource attached relationships.
     *
     * @var array
     */
    public $relationships = [];

    /**
     * Process the relation attaching to the resource response.
     *
     * @param string $relation
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected function processRelation(Model $model)
    {
        $relationType = Str::lower(class_basename($model));

        $this->with['included'] = [
            'id' => (string) $model->getKey(),
            'type' => $relationType,
            'attributes' => array_filter($model->toArray(), function ($key) {
                return ! Str::endsWith($key, '_id') && $key !== 'id';
            }, ARRAY_FILTER_USE_KEY),
        ];
        
        return [
            $model->getKeyName() => (string) $model->getKey(),
            'type' => $relationType,
        ];
    }

    /**
     * Get the resource model relationships.
     *
     * @return void
     */
    protected function getRelations()
    {
        foreach ($this->resource->getRelations() as $relation => $relationObj) {
            if ($relationObj instanceof Collection) {
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

            $this->resource->unsetRelation($relation);
        }
    }

    protected function uniqueIncludes()
    {
        $filtered = SupportCollection::make($this->included)->unique(function ($item) {
            return $item['id'] . $item['type'];
        });

        $this->included = $filtered->values()->all();
    }
}
