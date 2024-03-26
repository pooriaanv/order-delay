<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     *
     */
    public function __construct()
    {
        $this->model = $this->getModel();
    }

    /**
     * @param $identifier
     *
     * @return Model|null
     */
    public function find($identifier): ?Model
    {
        return $this->model->query()->find($identifier);
    }


    /**
     * @param string $field
     * @param string $key
     *
     * @return Model|null
     */
    public function findByField(string $field, string $key): ?Model
    {
        return $this->model->query()->where($field, $key)->first();
    }

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function save(Model $model): Model
    {
        $model->save();

        return $model->refresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    abstract function getModel(): Model;
}
