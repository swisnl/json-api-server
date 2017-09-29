<?php

namespace Swis\LaravelApi\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Swis\LaravelApi\Traits\HandlesRelationships;

abstract class BaseApiRepository implements RepositoryInterface
{
    use HandlesRelationships;
    /**
     * @var Model
     */
    protected $model;

    protected $page;

    protected $perPage;

    /**
     * BaseApiRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    public function paginate($per_page = 15, $page = 1, $columns = ['*'])
    {
        return $this->model->paginate($per_page, $columns, 'page', $page);
    }

    public function findById($value, $columns = ['*'])
    {
        return $this->model->findOrFail($value, $columns);
    }

    public function findByIds(array $ids, $per_page = 15, $page = 1, $columns = ['*'])
    {
        return $this->model->whereIn('id', $ids)->paginate($per_page, $columns, 'page', $page);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $objectKey)
    {
        return $this->model->where($this->model->getKeyName(), $objectKey)->update($data);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    public function makeModel(): Model
    {
        $model = app()->make($this->getModelName());

        if (!$model instanceof Model) {
            throw new ModelNotFoundException("Class: {$this->getModelName()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    public function getModelRelationships(): array
    {
        return $this->getRelationships($this->model);
    }

    abstract public function getModelName(): string;
}
