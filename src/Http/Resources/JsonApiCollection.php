<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use SkoreLabs\JsonApi\Http\Resources\Json\ResourceCollection;

class JsonApiCollection extends ResourceCollection
{
    use CollectsWithIncludes;

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

        $this->withIncludes();
    }
}
