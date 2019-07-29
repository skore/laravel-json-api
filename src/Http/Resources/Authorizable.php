<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

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
     * @return void
     */
    protected function authorize($resource)
    {
        $this->authorize = $this->authorize
            ?: ! $resource instanceof Model
            ?: Auth::user()->can('view', $resource);

        return $this->authorize;
    }
}
