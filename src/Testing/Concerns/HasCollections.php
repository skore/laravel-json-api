<?php

namespace SkoreLabs\JsonApi\Testing\Concerns;

use PHPUnit\Framework\Assert as PHPUnit;

/**
 * @mixin \SkoreLabs\JsonApi\Testing\Assert
 */
trait HasCollections
{
    public function at($position)
    {
        if (!array_key_exists($position, $this->collection)) {
            PHPUnit::fail(sprintf('There is no item at position "%d" on the collection response.', $position));

            return $this;
        }

        $data = $this->collection[$position];

        return new self($data['id'], $data['type'], $data['attributes']);
    }

    public function hasSize(int $value)
    {
        PHPUnit::assertCount($value, $this->collection, sprintf('The collection size is not same as "%d"', $value));

        return $this;
    }
}
