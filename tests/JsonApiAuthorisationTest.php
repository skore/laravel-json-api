<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Http\Resources\JsonApiCollection;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;
use SkoreLabs\JsonApi\Tests\Fixtures\User;

/**
 * @group requiresDatabase
 */
class JsonApiAuthorisationTest extends TestCase
{
    use RefreshDatabase;

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Gate::define('view', function (User $user, Post $post) {
            return $post->user_id === $user->id;
        });

        Route::get('/', function () {
            return new JsonApiCollection(Post::with(['parent', 'author'])->whereNotNull('parent_id')->get(), true);
        });

        Route::get('/restricted', function () {
            return new JsonApiCollection(Post::with(['parent', 'author'])->whereNotNull('parent_id')->get());
        });
    }

    public function test_resource_relationships_are_all_visible_when_bypassed()
    {
        $user = User::create([
            'name'     => 'Ruben',
            'email'    => 'ruben@wonderland.com',
            'password' => 'secret',
        ]);

        $firstPost = Post::create([
            'id'      => 5,
            'title'   => 'Test Title',
            'user_id' => User::create([
                'name'     => 'Another',
                'email'    => 'another@wonderland.com',
                'password' => 'secret',
            ])->id,
        ]);

        $secondPost = Post::create([
            'id'        => 6,
            'title'     => 'Test Title 2',
            'user_id'   => $user->id,
            'parent_id' => $firstPost->id,
        ]);

        $thirdAndMyPost = Post::create([
            'id'        => 7,
            'title'     => 'Test Title 3',
            'user_id'   => $user->id,
            'parent_id' => $secondPost->id,
        ]);

        $this->actingAs($user);

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $jsonApi) use ($firstPost, $secondPost) {
            $jsonApi->at(0)->hasRelationshipWith($firstPost, true);
            $jsonApi->at(1)->hasRelationshipWith($secondPost, true);
        });
    }

    public function test_resource_relationships_are_normally_some_invisible()
    {
        $this->markTestSkipped('Not here yet, really need major release!');

        $user = User::create([
            'name'     => 'Ruben',
            'email'    => 'ruben@wonderland.com',
            'password' => 'secret',
        ]);

        $firstPost = Post::create([
            'id'      => 5,
            'title'   => 'Test Title',
            'user_id' => User::create([
                'name'     => 'Another',
                'email'    => 'another@wonderland.com',
                'password' => 'secret',
            ])->id,
        ]);

        $secondPost = Post::create([
            'id'        => 6,
            'title'     => 'Test Title 2',
            'user_id'   => $user->id,
            'parent_id' => $firstPost->id,
        ]);

        $thirdAndMyPost = Post::create([
            'id'        => 7,
            'title'     => 'Test Title 3',
            'user_id'   => $user->id,
            'parent_id' => $secondPost->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/restricted', ['Accept' => 'application/json']);

        $response->assertJsonApi(function (Assert $jsonApi) use ($firstPost, $secondPost) {
            $jsonApi->count(2);

            $jsonApi->at(0)->hasNotRelationshipWith($firstPost, true);
            $jsonApi->at(1)->hasRelationshipWith($secondPost, true);
        });
    }
}
