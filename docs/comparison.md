# Comparison

Of course there are lot more libraries out there for Laravel and even more generic (for PHP) that we can also use instead of this one.

So here we'll explain the differences between them and this one.

## laravel-json-api

- Repository here: [https://github.com/laravel-json-api/laravel](https://github.com/laravel-json-api/laravel)

This is much more complex as it adds another layer of complexity on our application called `Schemas` so it doesn't only provide JSON:API parsing of the Eloquent models but also validation and comes with more extra stuff but with a much bigger sacrifice (**need to write much more code, while our solution is almost code-less**).

Also it comes licensed as Apache 2.0, while our is following the same Laravel's license: MIT.

## Fractal

- Repository here: [https://github.com/thephpleague/fractal](https://github.com/thephpleague/fractal)

Much simpler than the one above, but still adds a new layer (as **it is not a Laravel package**).

So it's much of the same, doesn't take advantage of the tools that the framework itself already provides like the API resources. Still a very good option thought as its one of the official _The PHP League_ packages, so you'll expect a very good support.