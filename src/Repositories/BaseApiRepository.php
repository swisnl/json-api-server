<?php

namespace Swis\LaravelApi\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Swis\LaravelApi\Traits\HandlesRelationships;

abstract class BaseApiRepository implements RepositoryInterface
{
    use HandlesRelationships;

    /** @var Model $model */
    protected $model;
    protected $page;
    protected $perPage;
    protected $parameters;

    /** @var Builder $query */
    protected $query;

    /**
     * BaseApiRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    public function paginate($perPage = 15, $page = 1, $columns = ['*'], $parameters = [])
    {
        $this->query = $this->model->newQuery();
        $this->parameters = $parameters;
        $this->perPage = $perPage;
        $this->page = $page;

        $this->setFilters();

        return $this->query->paginate($perPage, $page, null, $page);
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
        foreach ($data as &$item) { //TODO: nulls zijn niet toegestaan maar er worden wel lege velden mee gegeven van client
            if (null == $item) {
                $item = '';
            }
        }

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

    protected function setFilters()
    {
        $this->setIds();
        $this->sortByAsc();
        $this->sortByDesc();
    }

    public function setIds()
    {
        if (!isset($this->parameters['ids'])) {
            return;
        }

        $this->query->whereIn('id', explode(',', $this->parameters['ids']));
    }

    public function sortByAsc()
    {
        if (!isset($this->parameters['sort_by_asc'])) {
            return;
        }

        $this->query->sortBy($this->parameters['sort_by_asc']);
    }

    public function sortByDesc()
    {
        if (!isset($this->parameters['sort_by_desc'])) {
            return;
        }

        $this->query->sortByDesc($this->parameters['sort_by_desc']);
    }

    abstract public function getModelName(): string;
}
