<?php

namespace SkoreLabs\JsonApi\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = ['title', 'abstract'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * Return its parent post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Return whether the post is published.
     *
     * @return bool
     */
    public function getIsPublishedAttribute()
    {
        return true;
    }
}
