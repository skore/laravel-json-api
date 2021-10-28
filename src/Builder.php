<?php

namespace SkoreLabs\JsonApi;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class Builder
{
    public function jsonPaginate()
    {
        /**
         * Paginate the given query using JSON:API.
         *
         * @param int|string $perPage
         * @param array $columns
         *
         * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
         */
        return function ($perPage = null, $columns = ['*']) {
            $perPage = $perPage ?: $this->model->getPerPage();
            $clientPerPage = (int) request('page.size', config('json-api.pagination.default_size'));
            
            if (!$perPage || $perPage < $clientPerPage) {
                $perPage = $clientPerPage;
            }

            return $this->paginate($perPage, $columns, 'page[number]', (int) request('page.number'));
        };
    }
}
