<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Support\JsonApi;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;
use SkoreLabs\JsonApi\Tests\Fixtures\Tag;
use SkoreLabs\JsonApi\Tests\Fixtures\User;

class JsonApiRelationshipsTest extends TestCase
{
    /**
     * @var \SkoreLabs\JsonApi\Tests\Fixtures\Post
     */
    protected $authoredPost;

    /**
     * @var \SkoreLabs\JsonApi\Tests\Fixtures\Post
     */
    protected $lonelyPost;

    /**
     * @var \SkoreLabs\JsonApi\Tests\Fixtures\Tag
     */
    protected $postTag;

    /**
     * @var \SkoreLabs\JsonApi\Tests\Fixtures\Tag
     */
    protected $lonelyTag;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->bypassPolicies();
    }

    public function testCollectionHasAnyClientAuthorRelationship()
    {
        Route::get('/', function () {
            $this->authoredPost = new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]);

            $this->authoredPost->setRelation('author', new User([
                'id'       => 1,
                'name'     => 'Myself',
                'email'    => 'me@internet.org',
                'password' => '1234',
            ]));

            $this->lonelyPost = new Post([
                'id'    => 6,
                'title' => 'Test Title 2',
            ]);

            return JsonApi::format(collect([
                $this->authoredPost,
                $this->lonelyPost,
            ]));
        });

        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertSuccessful();

        $response->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->hasAnyRelationships('client', true)
                ->hasNotAnyRelationships('post', true);

            $jsonApi->at(0)->hasNotRelationshipWith($this->lonelyPost, true);
        });
    }

    /**
     * @group requiresDatabase
     */
    public function testResourceHasTagsRelationships()
    {
        // TODO: setRelation method doesn't work with hasMany relationships, so need migrations loaded
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        Route::get('/', function () {
            $this->authoredPost = Post::create([
                'title'    => 'Test Title',
            ]);

            $this->lonelyTag = Tag::create([
                'name'     => 'Lifestyle',
                'slug'     => 'lifestyle',
            ]);

            $this->postTag = Tag::create([
                'name'     => 'News',
                'slug'     => 'news',
            ]);

            $anotherTag = Tag::create([
                'name'     => 'International',
                'slug'     => 'international',
            ]);

            $this->authoredPost->tags()->attach([
                $this->postTag->id,
                $anotherTag->id,
            ]);

            $this->authoredPost->author()->associate(
                User::create([
                    'name'     => 'Myself',
                    'email'    => 'me@internet.org',
                    'password' => '1234',
                ])->id
            );

            $this->authoredPost->save();

            return JsonApi::format($this->authoredPost->refresh()->loadMissing('author', 'tags'));
        });

        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertSuccessful();

        $response->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->hasRelationshipWith($this->postTag, true)
                ->hasNotRelationshipWith($this->lonelyTag, true);
        });
    }
}
