<?php

namespace SkoreLabs\JsonApi;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\ServiceProvider;
use SkoreLabs\JsonApi\Builder as JsonApiBuilder;
use SkoreLabs\JsonApi\Testing\TestResponseMacros;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/json-api.php' => config_path('json-api.php'),
            ], 'config');
        }

        Builder::mixin(new JsonApiBuilder());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json-api.php', 'json-api');

        if (class_exists(TestResponse::class)) {
            TestResponse::mixin(new TestResponseMacros());
        }
    }
}
