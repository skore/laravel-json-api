<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

trait Authorizable
{
    /**
     * Determine whether authorize to view this resource.
     *
     * @var bool
     */
    public $authorise;

    /**
     * Authorize to view this resource.
     *
     * @param mixed $resource
     *
     * @return void
     */
    protected function authorize($resource)
    {
        $this->authorise = $this->authorise
            ?: $this->getAuthorisableConfig('view')
            ?: !$resource instanceof Model
            ?: Gate::check('view', $resource);

        return $this->authorise;
    }
}
