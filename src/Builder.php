<?php

namespace SkoreLabs\JsonApi;

class Builder
{
    /**
     * Paginate the given query using Json API.
     *
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function jsonPaginate()
    {
        return function ($perPage = null, $columns = ['*']) {
            return function () use ($columns, $perPage) {
                $perPage = $perPage ?: $this->model->getPerPage();
                $clientPerPage = (int) request('page.size', config('json-api.pagination.default_size'));

                if (! $perPage || $perPage < $clientPerPage) {
                    $perPage = $clientPerPage;
                }

                return $this->paginate($perPage, $columns, 'page[number]', (int) request('page.number'));
            };
        };
    }
}
