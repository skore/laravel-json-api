# Implementations

To complement this package functionality you'll want to add one of these packages we described here.

## Spatie's query builder

::: warning
Please consider using our forked one if you're using a version of Laravel older than 9.x and/or PHP 8.0.
:::

Installation with:

```sh
composer require spatie/laravel-query-builder
```

- **Repository:** [https://github.com/spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder)
- **Documentation:** [https://spatie.be/docs/laravel-query-builder/v5/introduction](https://spatie.be/docs/laravel-query-builder/v5/introduction)

## Our own fork

This is our own forked version of Spatie's one but with fixes and new implementations, also continuing our mission on supporting older versions of PHP and Laravel.

```sh
composer require skorelabs/laravel-query-builder
```

- **Repository:** [https://github.com/skore/laravel-query-builder](https://github.com/skore/laravel-query-builder)
- **Documentation:** [https://spatie.be/docs/laravel-query-builder/v3/introduction](https://spatie.be/docs/laravel-query-builder/v3/introduction)

## What about the frontend?

So many of you will ask this same question. The answer is simple as JSON:API is a common protocol, you could do your own implementation or use one of these:

- JavaScript browser & server (NodeJS / SSR-capable): [https://github.com/olosegres/jsona](https://github.com/olosegres/jsona)
- Check any other libraries in: [https://jsonapi.org/implementations/](https://jsonapi.org/implementations/)