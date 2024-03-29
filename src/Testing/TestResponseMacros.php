<?php

namespace SkoreLabs\JsonApi\Testing;

use Closure;

class TestResponseMacros
{
    public function assertJsonApi()
    {
        return function (Closure $callback = null) {
            $assert = Assert::fromTestResponse($this);

            if ($callback === null) {
                return $this;
            }

            $callback($assert);

            return $this;
        };
    }
}
