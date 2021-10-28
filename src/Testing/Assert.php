<?php

namespace SkoreLabs\JsonApi\Testing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\AssertionFailedError;
use SkoreLabs\JsonApi\Testing\Concerns\HasRelationships;
use SkoreLabs\JsonApi\Testing\Concerns\HasAttributes;
use SkoreLabs\JsonApi\Testing\Concerns\HasCollections;
use SkoreLabs\JsonApi\Testing\Concerns\HasIdentifications;

class Assert implements Arrayable
{
    use HasIdentifications,
        HasAttributes,
        HasRelationships,
        HasCollections,
        Macroable;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $relationships;
    
    /**
     * @var array
     */
    protected $includeds;

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
            PHPUnit::fail('Not a valid JSON:API response.');
        }

        return new self($data['id'], $data['type'], $data['attributes'], $data['relationships'] ?? [], $content['included'] ?? [], $collection);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public static function isCollection(array $data = [])
    {
        return !array_key_exists('attributes', $data);
    }
}
