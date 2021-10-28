<?php

namespace SkoreLabs\JsonApi\Testing\Concerns;

use PHPUnit\Framework\Assert as PHPUnit;

/**
 * @mixin \SkoreLabs\JsonApi\Testing\Assert
 */
trait HasAttributes
{
    public function hasAttribute($name, $value = null)
    {
        PHPUnit::assertArrayHasKey($name, $this->attributes, sprintf('JSON:API response does not have an attribute named "%s"', $name));

        if ($value) {
            PHPUnit::assertContains($value, $this->attributes, sprintf('JSON:API response does not have an attribute named "%s" with value "%s"', $name, $value));
        }

        return $this;
    }

    public function hasAttributes($attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->hasAttribute($name, $value);
        }

        return $this;
    }
}
