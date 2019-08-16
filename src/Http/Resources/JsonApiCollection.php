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
     * @param  bool|null  $authorize
     * @return void
     */
    public function __construct($resource, $authorize = null)
    {
        $this->collects = JsonApiResource::class;
        $this->authorize = $authorize;

        parent::__construct($resource);

        $this->withIncludes();
    }
}
