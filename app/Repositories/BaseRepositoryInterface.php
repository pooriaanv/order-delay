<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * @param $identifier
     *
     * @return Model|null
     */
    public function find($identifier): ?Model;

    /**
     * @param string $field
     * @param string $key
     *
     * @return Model|null
     */
    public function findByField(string $field, string $key): ?Model;

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function save(Model $model): Model;

    /**
     * @param Model $model
     *
     * @return void
     */
    public function delete(Model $model): void;
}

