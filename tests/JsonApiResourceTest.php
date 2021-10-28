<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Http\Resources\JsonApiResource;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;

class JsonApiResourceTest extends TestCase
{
    public function testResourcesMayBeConvertedToJsonApi()
    {
        Route::get('/', function () {
            return new JsonApiResource(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]), true);
        });

        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id'         => '5',
                'type'       => 'post',
                'attributes' => [
                    'title'    => 'Test Title',
                    'abstract' => 'Test abstract',
                ],
            ],
        ], true);
    }

    public function testResourcesHasIdentifier()
    {
        Route::get('/', function () {
            return new JsonApiResource(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]), true);
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasId(5)->hasType('post');
        });
    }

    public function testResourcesHasAttribute()
    {
        Route::get('/', function () {
            return new JsonApiResource(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]), true);
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasAttribute('title', 'Test Title');
        });
    }

    public function testResourcesHasAttributes()
    {
        Route::get('/', function () {
            return new JsonApiResource(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]), true);
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasAttributes([
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]);
        });
    }

    // FIXME: Not available in Laravel 6, wait to support removal
    // public function testResourcesMayBeConvertedToJsonApiWithToJsonMethod()
    // {
    //     $resource = new JsonApiResource(new Post([
    //         'id'       => 5,
    //         'title'    => 'Test Title',
    //         'abstract' => 'Test abstract',
    //     ]), true);

    //     $this->assertSame('{"id":"5","type":"post","attributes":{"title":"Test Title","abstract":"Test abstract"}}', $resource->toJson());
    // }

    public function testResourcesWithRelationshipsMayBeConvertedToJsonApi()
    {
        Route::get('/', function () {
            $post = new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]);

            $post->setRelation('parent', new Post([
                'id'    => 4,
                'title' => 'Test Parent Title',
            ]));

            return new JsonApiResource($post, true);
        });

        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id'         => '5',
                'type'       => 'post',
                'attributes' => [
                    'title'    => 'Test Title',
                    'abstract' => 'Test abstract',
                ],
                'relationships' => [
                    'parent' => [
                        'data' => [
                            'id'   => '4',
                            'type' => 'post',
                        ],
                    ],
                ],
            ],
            'included' => [
                [
                    'id'         => '4',
                    'type'       => 'post',
                    'attributes' => [
                        'title' => 'Test Parent Title',
                    ],
                ],
            ],
        ], true);
    }

    public function testResourcesHasRelationshipWith()
    {
        Route::get('/', function () {
            $post = new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]);

            $post->setRelation('parent', new Post([
                'id'    => 4,
                'title' => 'Test Parent Title',
            ]));

            return new JsonApiResource($post, true);
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasRelationshipWith(new Post([
                'id'    => 4,
                'title' => 'Test Parent Title',
            ]), true);
        });
    }

    public function testResourcesAtRelationHasAttribute()
    {
        Route::get('/', function () {
            $post = new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]);

            $post->setRelation('parent', new Post([
                'id'    => 4,
                'title' => 'Test Parent Title',
            ]));

            return new JsonApiResource($post, true);
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->atRelation(new Post([
                'id'    => 4,
                'title' => 'Test Parent Title',
            ]))->hasAttribute('title', 'Test Parent Title');
        });
    }

    public function testResourcesMayNotBeConvertedToJsonApiWithoutPermissions()
    {
        Route::get('/', function () {
            return new JsonApiResource(new Post([
                'id'    => 5,
                'title' => 'Test Title',
            ]));
        });

        $response = $this->get('/', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [],
        ]);
    }
}
