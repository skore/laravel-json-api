<?php

namespace SkoreLabs\JsonApi;

use Illuminate\Foundation\Testing\TestResponse as LegacyTestResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Testing\TestResponse;
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Builder::mixin(new JsonApiBuilder());

        $this->mergeConfigFrom(__DIR__.'/../config/json-api.php', 'json-api');

        $this->registerTestingMacros();
    }

    protected function registerTestingMacros()
    {
        if (class_exists(TestResponse::class)) {
            TestResponse::mixin(new TestResponseMacros());

            return;
        }
        
        // Laravel <= 6.0
        if (class_exists(LegacyTestResponse::class)) {
            LegacyTestResponse::mixin(new TestResponseMacros());

            return;
        }
    }
}
