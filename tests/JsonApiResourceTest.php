<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use SkoreLabs\JsonApi\Support\JsonApi;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;

class JsonApiResourceTest extends TestCase
{
    public function testResourcesMayBeConvertedToJsonApi()
    {
        $this->bypassPolicies();

        Route::get('/', function () {
            return JsonApi::format(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]));
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
        $this->bypassPolicies();

        Route::get('/', function () {
            return JsonApi::format(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]));
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasId(5)->hasType('post');
        });
    }

    public function testResourcesHasAttribute()
    {
        $this->bypassPolicies();

        Route::get('/', function () {
            return JsonApi::format(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]));
        });

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $json) {
            $json->hasAttribute('title', 'Test Title');
        });
    }

    public function testResourcesHasAttributes()
    {
        $this->bypassPolicies();

        Route::get('/', function () {
            return JsonApi::format(new Post([
                'id'       => 5,
                'title'    => 'Test Title',
                'abstract' => 'Test abstract',
            ]));
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
    //     $resource = JsonApi::format(new Post([
    //         'id'       => 5,
    //         'title'    => 'Test Title',
    //         'abstract' => 'Test abstract',
    //     ]), true);

    //     $this->assertSame('{"id":"5","type":"post","attributes":{"title":"Test Title","abstract":"Test abstract"}}', $resource->toJson());
    // }

    public function testResourcesWithRelationshipsMayBeConvertedToJsonApi()
    {
        $this->bypassPolicies();

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

            return JsonApi::format($post);
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
        $this->bypassPolicies();

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

            return JsonApi::format($post);
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
        $this->bypassPolicies();

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

            return JsonApi::format($post);
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
            return JsonApi::format(new Post([
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
