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
    public $authorize;

    /**
     * Authorize to view this resource.
     *
     * @param mixed $resource
     *
     * @return void
     */
    protected function authorize($resource)
    {
        $this->authorize = $this->authorize
            ?: !$resource instanceof Model
            ?: Gate::check('view', $resource);

        return $this->authorize;
    }
}
