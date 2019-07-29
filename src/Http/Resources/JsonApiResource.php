<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Str;

class JsonApiResource extends JsonResource
{
    use Authorizable, RelationshipsWithIncludes;

    /**
     * The resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param bool $authorize
     * @return void
     */
    public function __construct($resource, $authorize = false)
    {
        if (gettype($authorize) === 'boolean') {
            $this->authorize = $authorize;
        }

        if (! $this->authorize($resource)) {
            $this->resource = new MissingValue();
        } else {
            $this->resource = $resource;
            $this->withRelationships();
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->evaluateResponse()) {
            return [
                $this->merge($this->getResourceIdentifier()),
                'attributes' => $this->getAttributes(),
                'relationships' => $this->when(
                    $this->relationships, $this->relationships
                ),
            ];
        }

        return $this->resource;
    }

    /**
     * Test response if valid for formatting.
     *
     * @return bool
     */
    protected function evaluateResponse()
    {
        return ! is_array($this->resource)
            && ! is_null($this->resource)
            && ! $this->resource instanceof MissingValue;
    }

    /**
     * Get object identifier "id" and "type".
     *
     * @return array
     */
    public function getResourceIdentifier()
    {
        return [
            $this->resource->getKeyName() => (string) $this->resource->getKey(),
            'type' => Str::lower(class_basename($this->resource)),
        ];
    }

    /**
     * Get filtered attributes excluding all the ids.
     *
     * @return array
     */
    protected function getAttributes()
    {
        return array_filter($this->resource->attributesToArray(), function ($key) {
            return ! Str::endsWith($key, '_id') && $key !== $this->resource->getKeyName();
        }, ARRAY_FILTER_USE_KEY);
    }
}
