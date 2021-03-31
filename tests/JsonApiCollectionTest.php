<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;
use SkoreLabs\JsonApi\Http\Resources\JsonApiCollection;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;

class JsonApiCollectionTest extends TestCase
{
    public function testCollectionsMayBeConvertedToJsonApi()
    {
        Route::get('/', function () {
            return new JsonApiCollection(collect([
                new Post([
                    'id' => 5,
                    'title' => 'Test Title',
                    'abstract' => 'Test abstract',
                ]),
                new Post([
                    'id' => 6,
                    'title' => 'Test Title 2'
                ])
            ]), true);
        });

        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'id' => '5',
                    'type' => 'post',
                    'attributes' => [
                        'title' => 'Test Title',
                        'abstract' => 'Test abstract',
                    ]
                ],
                [
                    'id' => '6',
                    'type' => 'post',
                    'attributes' => [
                        'title' => 'Test Title 2',
                    ]
                ]
            ]
        ], true);
    }
}
