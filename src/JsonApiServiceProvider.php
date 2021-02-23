<?php

namespace SkoreLabs\JsonApi;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use SkoreLabs\JsonApi\Builder as JsonApiBuilder;

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
    }
}
