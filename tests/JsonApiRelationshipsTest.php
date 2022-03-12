<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Support\JsonApi;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;
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
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->bypassPolicies();

        Route::get('/', function () {
            $this->authoredPost = new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]);

            $this->authoredPost->setRelation('author', new User([
                'id' => 1,
                'name' => 'Myself',
                'email' => 'me@internet.org',
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
    }

    public function testCollectionHasAnyClientAuthorRelationship()
    {
        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertSuccessful();

        $response->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->hasAnyRelationships('client', true)
                ->hasNotAnyRelationships('post', true);

            $jsonApi->at(0)->hasNotRelationshipWith($this->lonelyPost, true);
        });
    }
}
