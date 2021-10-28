<?php

namespace SkoreLabs\JsonApi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use SkoreLabs\JsonApi\JsonApiServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            JsonApiServiceProvider::class,
        ];
    }
}
