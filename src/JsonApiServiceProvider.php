<?php

namespace SkoreLabs\JsonApi;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

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
                __DIR__.'/../config/json-api-paginate.php' => config_path('json-api-paginate.php'),
            ], 'config');
        }

        $this->registerMacro();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json-api-paginate.php', 'json-api-paginate');
    }

    /**
     * Register package added macros.
     *
     * @return void
     */
    protected function registerMacro()
    {
        /*
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function ($perPage = null, $total = null, $page = null, $pageName = 'page') {
            $perPage = $perPage ?? request('page.size') ?? config('json-api-paginate.default_size');
            $page = $page ?? request('page.number') ?? LengthAwarePaginator::resolveCurrentPage($pageName);

            $jsonPaginator = new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                (int) $perPage,
                $page,
                [
                    'path'     => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => 'page[number]',
                ]
            );

            return $jsonPaginator->appends(
                Arr::except((array) request()->query(), 'page.number')
            );
        });
    }
}
