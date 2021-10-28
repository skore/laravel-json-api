<?php

namespace SkoreLabs\JsonApi\Testing\Concerns;

use PHPUnit\Framework\Assert as PHPUnit;

/**
 * @mixin \SkoreLabs\JsonApi\Testing\Assert
 */
trait HasIdentifications
{
    public function hasId($value)
    {
        $value .= '';

        PHPUnit::assertTrue($this->id === $value, sprintf('JSON:API response does not have id "%s"', $value));

        return $this;
    }

    public function hasType($value)
    {
        PHPUnit::assertSame($this->type, $value, sprintf('JSON:API response does not have type "%s"', $value));

        return $this;
    }
}
