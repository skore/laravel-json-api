<?php

namespace SkoreLabs\JsonApi\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that should be visible in serialization.
     *
     * @var string[]
     */
    protected $visible = ['name', 'slug'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];
}
