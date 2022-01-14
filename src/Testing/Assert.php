<?php

namespace SkoreLabs\JsonApi\Testing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\AssertionFailedError;
use SkoreLabs\JsonApi\Testing\Concerns\HasAttributes;
use SkoreLabs\JsonApi\Testing\Concerns\HasCollections;
use SkoreLabs\JsonApi\Testing\Concerns\HasIdentifications;
use SkoreLabs\JsonApi\Testing\Concerns\HasRelationships;

class Assert implements Arrayable
{
    use HasIdentifications;
    use HasAttributes;
    use HasRelationships;
    use HasCollections;
    use Macroable;

    /**
     * @var array
     */
    protected $collection;

    protected function __construct($id = '', $type = '', array $attributes = [], array $relationships = [], array $includeds = [], array $collection = [])
    {
        $this->id = $id;
        $this->type = $type;

        $this->attributes = $attributes;
        $this->relationships = $relationships;
        $this->includeds = $includeds;

        $this->collection = $collection;
    }

    public static function fromTestResponse($response)
    {
        try {
            $content = json_decode($response->getContent(), true);
            PHPUnit::assertArrayHasKey('data', $content);
            $data = $content['data'];
            $collection = [];

            if (static::isCollection($data)) {
                $collection = $data;
                $data = head($data);
            }

            PHPUnit::assertIsArray($data);
            PHPUnit::assertArrayHasKey('id', $data);
            PHPUnit::assertArrayHasKey('type', $data);
            PHPUnit::assertArrayHasKey('attributes', $data);
            PHPUnit::assertIsArray($data['attributes']);
        } catch (AssertionFailedError $e) {
            PHPUnit::fail('Not a valid JSON:API response or response data is empty.');
        }

        return new self($data['id'], $data['type'], $data['attributes'], $data['relationships'] ?? [], $content['included'] ?? [], $collection);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Check if data contains a collection of resources.
     *
     * @param array $data
     *
     * @return bool
     */
    public static function isCollection(array $data = [])
    {
        return !array_key_exists('attributes', $data);
    }

    /**
     * Get the identifier in a pretty printable message by id and type.
     *
     * @param mixed  $id
     * @param string $type
     *
     * @return string
     */
    protected function getIdentifierMessageFor($id = null, string $type = null)
    {
        $messagePrefix = '{ id: %s, type: "%s" }';

        if (!$id && !$type) {
            return sprintf($messagePrefix.' at position %d', (string) $this->id, $this->type, $this->atPosition);
        }

        return sprintf($messagePrefix, (string) $id, $type);
    }
}
