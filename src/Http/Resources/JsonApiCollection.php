<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use SkoreLabs\JsonApi\Concerns\HasConfig;
use SkoreLabs\JsonApi\Http\Resources\Json\ResourceCollection;

class JsonApiCollection extends ResourceCollection
{
    use CollectsWithIncludes;
    use HasConfig;

    /**
     * Create a new resource instance.
     *
     * @param mixed     $resource
     * @param bool|null $authorise
     * @param mixed|null $collects
     *
     * @return void
     */
    public function __construct($resource, $authorise = null, $collects = null)
    {
        $this->collects = $collects ?: JsonApiResource::class;
        $this->authorise = $authorise;

        parent::__construct($resource);

        $this->withIncludes();
    }
}
