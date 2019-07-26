<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JsonApiResource extends JsonResource
{
    use RelationshipsWithIncludes, ConditionallyLoadsAttributes;

    /**
     * The resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * Specify if show any pivot table data.
     *
     * @var bool
     */
    protected $showPivot = false;

    /**
     * Determine whether authorize to view this resource.
     *
     * @var bool
     */
    protected $authorize;

    /**
     * Authorize to view this resource.
     *
     * @return bool
     */
    protected function authorize()
    {
        $strClass = get_class($this->resource);

        return $this->authorize
            ?: is_string($strClass)
            ?: Auth::user()->can('viewAny', $strClass)
            ?: Auth::user()->can('view', $this->resource);
    }

    /**
     * Set authorization bypassing automatic authorization.
     *
     * @param bool $value
     * @return void
     */
    public function setAuthorize($value = true)
    {
        $this->authorize = $value;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->authorize() === false) {
            return new MissingValue();
        }

        if (is_null($this->resource)) {
            return [];
        }

        return (is_array($this->resource))
            ? $this->resource
            : $this->formatResponse();
    }

    /**
     * Format model response to array.
     *
     * @return array
     */
    protected function formatResponse()
    {
        $hiddenAttrs = [
            $this->resource->getKeyName(),
        ];

        if (! $this->showPivot) {
            $hiddenAttrs[] = 'pivot';
        }

        $this->resource->addHidden($hiddenAttrs);

        return [
            $this->resource->getKeyName() => (string) $this->resource->getKey(),
            'type' => Str::lower(class_basename($this->resource)),
            'attributes' => $this->resource->toArray(),
            'relationships' => $this->when(
                $this->relationships, $this->relationships
            ),
        ];
    }
}