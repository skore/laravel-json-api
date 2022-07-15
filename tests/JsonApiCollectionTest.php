<?php

namespace SkoreLabs\JsonApi\Tests;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\AssertionFailedError;
use SkoreLabs\JsonApi\Support\JsonApi;
use SkoreLabs\JsonApi\Testing\Assert;
use SkoreLabs\JsonApi\Tests\Fixtures\Post;

class JsonApiCollectionTest extends TestCase
{
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
            return JsonApi::format(collect([
                new Post([
                    'id' => 5,
                    'title' => 'Test Title',
                    'abstract' => 'Test abstract',
                ]),
                new Post([
                    'id' => 6,
                    'title' => 'Test Title 2',
                ]),
            ]));
        });
    }

    public function testCollectionsMayBeConvertedToJsonApi()
    {
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
                    ],
                ],
                [
                    'id' => '6',
                    'type' => 'post',
                    'attributes' => [
                        'title' => 'Test Title 2',
                    ],
                ],
            ],
        ], true);
    }

    public function testCollectionsAtHasAttribute()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->at(0)->hasAttribute('title', 'Test Title');

            $jsonApi->at(1)->hasAttribute('title', 'Test Title 2');
        });
    }

    public function testCollectionsTakeByDefaultFirstItem()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->hasAttribute('title', 'Test Title');
        });
    }

    public function testCollectionsHasSize()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->hasSize(2);
        });
    }

    public function testCollectionsAtUnreachablePosition()
    {
        $this->expectException(AssertionFailedError::class);

        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $jsonApi) {
            $jsonApi->at(10);
        });
    }

    public function testCollectionsToArrayReturnsArray()
    {
        $this->get('/', ['Accept' => 'application/json'])->assertJsonApi(function (Assert $jsonApi) {
            $responseArray = $jsonApi->toArray();

            $this->assertIsArray($responseArray);
            $this->assertFalse(empty($responseArray), 'toArray() should not be empty');
        });
    }
}
