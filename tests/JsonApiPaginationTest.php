<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Http\Resources\JsonApiCollection;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;

/**
 * @group requiresDatabase
 */
class JsonApiPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Route::get('/posts', function () {
            Post::create(['title' => 'Test Title']);
            Post::create(['title' => 'Test Title 2']);
            Post::create(['title' => 'Test Title 3']);
            Post::create(['title' => 'Test Title 4']);

            return JsonApiCollection::make(Post::jsonPaginate(1), true);
        });
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function testJsonApiPaginationWithPageSize()
    {
        $response = $this->getJson('/posts?page[size]=2');
        
        $response->assertJsonApi(function (Assert $json) {
            $json->hasSize(2);
        });

        $response->assertStatus(200);
    }
}
