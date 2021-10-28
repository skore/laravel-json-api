<?php

namespace SkoreLabs\JsonApi\Support\Facades;

use Illuminate\Support\Facades\Facade;

class JsonApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'json-api';
    }
}
