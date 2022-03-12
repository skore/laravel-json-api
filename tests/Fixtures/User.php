<?php

namespace SkoreLabs\JsonApi\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use SkoreLabs\JsonApi\Contracts\JsonApiable;

class User extends Authenticatable implements JsonApiable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['id', 'name', 'email', 'password'];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var string[]
     */
    protected $visible = ['name', 'email'];

    /**
     * Get a custom resource type for JSON:API formatting.
     *
     * @return string
     */
    public function resourceType(): string
    {
        return 'client';
    }

    /**
     * Return its parent post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
