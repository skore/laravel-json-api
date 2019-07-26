<?php

namespace SkoreLabs\JsonApi\Http\Resources;

use Illuminate\Http\Resources\MissingValue;

trait ConditionallyLoadsAttributes
{
    /**
     * Retrieve an attribute if it has been appended.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  mixed  $default
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function whenAppended($attribute, $value = null, $default = null)
    {
        if (func_num_args() < 3) {
            $default = new MissingValue();
        }

        if (! $this->resource->getAppends('flags')) {
            return value($default);
        }

        if (func_num_args() === 1) {
            return $this->resource->{$attribute};
        }

        if ($this->resource->{$attribute} === null) {
            return;
        }

        return value($value);
    }
}