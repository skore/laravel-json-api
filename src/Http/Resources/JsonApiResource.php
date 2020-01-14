<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Str;
use SkoreLabs\JsonApi\Concerns\HasConfig;

class JsonApiResource extends JsonResource
{
    use Authorizable;
    use RelationshipsWithIncludes;
    use HasConfig;

    /**
     * The resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * Create a new resource instance.
     *
     * @param mixed     $resource
     * @param bool|null $authorise
     *
     * @return void
     */
    public function __construct($resource, $authorise = null)
    {
        $this->authorise = $authorise;

        if ($this->authorize($resource)) {
            $this->resource = $resource;
            $this->withRelationships();
        } else {
            $this->resource = new MissingValue();
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        if ($this->evaluateResponse()) {
            return [
                $this->merge($this->getResourceIdentifier()),
                'attributes'    => $this->getAttributes(),
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
        return !is_array($this->resource)
            && !is_null($this->resource)
            && !$this->resource instanceof MissingValue;
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
            'type'                        => Str::snake(class_basename($this->resource)),
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
            return !Str::endsWith($key, '_id') && $key !== $this->resource->getKeyName();
        }, ARRAY_FILTER_USE_KEY);
    }
}
