<?php

namespace Swis\LaravelApi\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Swis\LaravelApi\Traits\HandlesRelationships;

abstract class BaseApiRepository implements RepositoryInterface
{
    use HandlesRelationships;

    /** @var Model $model */
    protected $model;
    protected $user;

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
        if (!isset($this->query)) {
            $this->query = $this->model->newQuery();
        }

        $this->parameters = $parameters;
        $this->perPage = $perPage;
        $this->page = $page;

        $this->setFilters();

        return $this->query->paginate($perPage, $columns, null, $page);
    }

    public function findById($value, $columns = ['*'])
    {
        if (!isset($this->query)) {
            $this->query = $this->model->newQuery();
        }

        $this->eagerLoadRelationships();

        return $this->query->findOrFail($value, $columns);
    }

    public function create(array $data)
    {
        $data = array_map([$this, 'nullToEmptyString'], $data);

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
        $this->orderByAsc();
        $this->orderByDesc();
        $this->eagerLoadRelationships();
    }

    public function setIds()
    {
        if (!isset($this->parameters['ids'])) {
            return;
        }

        $this->query->whereIn('id', explode(',', $this->parameters['ids']));
    }

    public function orderByAsc()
    {
        if (!isset($this->parameters['order_by_asc'])) {
            return;
        }

        $this->query->orderBy($this->parameters['order_by_asc']);
    }

    public function orderByDesc()
    {
        if (!isset($this->parameters['order_by_desc'])) {
            return;
        }

        $this->query->orderByDesc($this->parameters['order_by_desc']);
    }

    function dumpQueryWithBindings(){
        $sql = $this->query->toSql();
        foreach($this->query->getBindings() as $key => $binding){
            $sql = preg_replace('/\?/', "'$binding'", $sql, 1);
        }
        dd($sql);
    }

    public function setUser(User $user = null)
    {
        if (!isset($user)) {
            return $this;
        }

        $this->user = $user;

        return $this;
    }

    protected function nullToEmptyString($value)
    {
        if ($value !== null) {
            return $value;
        }

        return '';
    }

    protected function eagerLoadRelationships()
    {
        //$relations = $this->getRelationships($this->model);
        $relations = [];

        if (!empty($this->parameters) && array_key_exists('include', $this->parameters)) {
            $relations = explode(',', $this->parameters['include']);
        //$relations = array_merge($includes, $relations);
        }

        $this->query->with(array_unique($relations));
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    abstract public function getModelName(): string;
}
