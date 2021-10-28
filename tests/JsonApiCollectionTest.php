<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Http\Resources\JsonApiCollection;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;

class JsonApiCollectionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/', function () {
            return new JsonApiCollection(collect([
                new Post([
                    'id'       => 5,
                    'title'    => 'Test Title',
                    'abstract' => 'Test abstract',
                ]),
                new Post([
                    'id'    => 6,
                    'title' => 'Test Title 2',
                ]),
            ]), true);
        });
    }

    public function testCollectionsMayBeConvertedToJsonApi()
    {
        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'id'         => '5',
                    'type'       => 'post',
                    'attributes' => [
                        'title'    => 'Test Title',
                        'abstract' => 'Test abstract',
                    ],
                ],
                [
                    'id'         => '6',
                    'type'       => 'post',
                    'attributes' => [
                        'title' => 'Test Title 2',
                    ],
                ],
            ],
        ], true);
    }

    public function testCollectionsAtHasAttribute()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->at(0)->hasAttribute('title', 'Test Title');

            $json->at(1)->hasAttribute('title', 'Test Title 2');
        });
    }
    
    public function testCollectionsTakeByDefaultFirstItem()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasAttribute('title', 'Test Title');
        });
    }
    
    public function testCollectionsHasSize()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasSize(2);
        });
    }
}
