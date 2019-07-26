<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Database\Eloquent\{Model, Collection};
use Illuminate\Support\{Str, Collection as SupportCollection};

trait CollectsWithRelationships
{
    /**
     * Included relations on the response.
     *
     * @var array
     */
    protected $included = [];

    /**
     * Process resources relationships to included/relationship attributes.
     *
     * @return void
     */
    protected function processRelations() 
    {
        $this->collection->map(function (JsonApiResource $jsonResource) {
            $relations = $jsonResource->resource->getRelations();

            foreach ($relations as $relation => $relationObj) {
                if ($relationObj instanceof Collection) {
                    foreach ($relationObj->all() as $relationModel) {
                        $jsonResource->with['relationships'][$relation]['data'][] = $this->processModelRelation(
                            $relationModel
                        );
                    }
                }

                if ($relationObj instanceof Model) {
                    $jsonResource->with['relationships'][$relation]['data'] = $this->processModelRelation(
                        $relationObj
                    );
                }

                $jsonResource->resource->unsetRelation($relation);
            }

            return $jsonResource;
        });

        $this->removeRepeateds();
    }

    public function with()
    {
        return $this->included;
    }

    /**
     * Process a model relation attaching to its model additional attributes.
     *
     * @param \Illuminate\Database\Eloquent\Model $relation
     * @return void
     */
    protected function processModelRelation(Model $relation)
    {
        $relatedModelType = Str::lower(class_basename($relation));

        $this->included[] = [
            'id' => (string) $relation->getKey(),
            'type' => $relatedModelType,
            'attributes' => array_filter($relation->toArray(), function ($key) {
                return ! Str::endsWith($key, '_id') && $key !== 'id';
            }, ARRAY_FILTER_USE_KEY),
        ];

        return [
            $relation->getKeyName() => (string) $relation->getKey(),
            'type' => $relatedModelType,
        ];
    }

    public function removeRepeateds()
    {
        $filtered = SupportCollection::make($this->included)->unique(function ($item) {
            return $item['id'] . $item['type'];
        });

        $this->included = $filtered->values()->all();
    }
}