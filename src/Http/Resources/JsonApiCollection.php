<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JsonApiCollection extends ResourceCollection
{
    use CollectsWithRelationships;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->collects = JsonApiResource::class;

        parent::__construct($resource);
        
        $this->processRelations();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // 

        return JsonApiResource::collection(
            $this->collection
        );
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'included' => $this->when(
                $this->included, $this->included
            ),
        ];
    }
}