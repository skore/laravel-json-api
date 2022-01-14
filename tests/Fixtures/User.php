<?php

namespace SkoreLabs\JsonApi\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var string[]
     */
    protected $visible = ['name', 'email'];

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
