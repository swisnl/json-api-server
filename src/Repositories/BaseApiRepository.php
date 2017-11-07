<?php

namespace Swis\LaravelApi\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
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
        if (!isset($this->query)) {
            $this->query = $this->model->newQuery();
        }

        $this->parameters = $parameters;
        $this->perPage = $perPage;
        $this->page = $page;

        $this->setFilters();

        $this->query->with($this->getRelationships($this->model));

        return $this->query->paginate($perPage, $columns, null, $page);
    }

    public function findById($value, $columns = ['*'])
    {
        return $this->model->findOrFail($value, $columns);
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
        $this->orderByAsc();
        $this->orderByDesc();
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
        if (!isset($this->parameters['sort_by_asc'])) {
            return;
        }

        $this->query->orderBy($this->parameters['sort_by_asc']);
    }

    public function orderByDesc()
    {
        if (!isset($this->parameters['sort_by_desc'])) {
            return;
        }

        $this->query->orderByDesc($this->parameters['sort_by_desc']);
    }

    function dumpQueryWithBindings(){
        $sql = $this->query->toSql();
        foreach($this->query->getBindings() as $key => $binding){
            $sql = preg_replace('/\?/', "'$binding'", $sql, 1);
        }
        dd($sql);
    }

    abstract public function getModelName(): string;
}
