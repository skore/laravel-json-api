<?php

namespace SkoreLabs\JsonApi\Contracts;

interface JsonApiable
{
    /**
     * Get a custom resource type for JSON:API formatting.
     *
     * @return string
     */
    public function resourceType(): string;
}
