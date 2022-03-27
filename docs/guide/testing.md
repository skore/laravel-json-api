# Testing

This package also have some testing utilities built on top of PHPUnit and Laravel's framework assertions.

## Assertions

Simple assert that your API route is returning a proper JSON:API response:

```php
$response = $this->getJson('/posts');

$response->assertJsonApi();
```

[[toc]]

### at

Assert the resource at position of the collection starting by 0.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->at(0)->hasAttribute('title', 'Hello world');
});
```

### atRelation

Assert the related model.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts');

$relatedComment = Comment::find(4);

$response->assertJsonApi(function (Assert $jsonApi) use ($relatedComment) {
  $json->at(0)->atRelation($relatedComment)->hasAttribute('content', 'Foo bar');
});
```

### hasAttribute

Assert the resource has the specified attribute key and value.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasAttribute('title', 'Hello world');
});
```

### hasAttributes

Assert the resource has the specified attributes keys and values.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasAttributes([
    'title' => 'Hello world'
    'slug' => 'hello-world'
  ]);
});
```

### hasId

Assert the resource has the specified ID (or model key).

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasId(1);
});
```

### hasType

Assert the resource has the specified type.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasType('post');
});
```

### hasAttributes

Assert the resource has the specified attributes keys and values.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasAttributes([
    'title' => 'Hello world'
    'slug' => 'hello-world'
  ]);
});
```

### hasAnyRelationships

Assert that the resource **has any** relationships with the specified **resource type**.

Second parameter is for assert that the response **includes** the relationship data at the `included`.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasAnyRelationships('comment', true);
});
```

### hasNotAnyRelationships

Assert that the resource **doesn't have any** relationships with the specified **resource type**.

Second parameter is for assert that the response **doesn't includes** the relationship data at the `included`.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/2');

$response->assertJsonApi(function (Assert $jsonApi) {
  $json->hasNotAnyRelationships('comment', true);
});
```

### hasRelationshipWith

Assert that the specific model resource **is a** relationship with the parent resource.

Second parameter is for assert that the response **includes** the relationship data at the `included`.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$relatedComment = Comment::find(4);

$response->assertJsonApi(function (Assert $jsonApi) use ($relatedComment) {
  $json->hasRelationshipWith($relatedComment, true);
});
```

### hasNotRelationshipWith

Assert that the specific model resource **is not** a relationship with the parent resource.

Second parameter is for assert that the response **doesn't includes** the relationship data at the `included`.

```php
use SkoreLabs\JsonApi\Testing\Assert;

$response = $this->getJson('/posts/1');

$relatedComment = Comment::find(4);

$response->assertJsonApi(function (Assert $jsonApi) use ($relatedComment) {
  $json->hasRelationshipWith($relatedComment, true);
});
```
