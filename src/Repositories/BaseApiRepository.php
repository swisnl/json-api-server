<?php

namespace Swis\JsonApi\Server\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Swis\JsonApi\Server\Exceptions\NotFoundException;
use Swis\JsonApi\Server\Traits\HandlesRelationships;

abstract class BaseApiRepository implements RepositoryInterface
{
    use HandlesRelationships;

    const PAGE = 1;
    const PER_PAGE = 15;

    /** @var Model $model */
    protected $model;
    protected $user;

    protected $page = self::PAGE;
    protected $perPage = self::PER_PAGE;
    protected $parameters;

    /** @var Builder $query */
    protected $query;
    protected $columns = ['*'];

    /**
     * BaseApiRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->makeModel();
        $this->initQuery();
    }

    public function paginate($parameters = [])
    {
        $this->initQuery();

        $this->parameters = $parameters;
        $this->setFilters();

        if (array_key_exists('all', $this->parameters)) {
            $collection = $this->query->get();
            $total = count($collection);

            return new LengthAwarePaginator($collection, $total, $total);
        }

        return $this->query->paginate($this->perPage, $this->columns, 'page', $this->page);
    }

    /**
     * @param $value
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     * @throws NotFoundException
     */
    public function findById($value, $parameters = [])
    {
        $this->initQuery();
        $this->parameters = $parameters;
        $this->setFilters();
        $this->eagerLoadRelationships();
        $this->model = $this->query->find($value);
        if (!$this->model) {
            throw new NotFoundException("{$this->getModelName()} {$value} not found");
        }
        return $this->model;
    }

    public function create(array $data)
    {
        $data = array_map([$this, 'nullToEmptyString'], $data);

        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $objectKey
     *
     * @throws NotFoundException
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function update(array $data, $objectKey)
    {
        $this->model = $this->findById($objectKey);
        $this->model->update($data);

        return $this->model;
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
        $this->excludeIds();
        $this->orderByAsc();
        $this->orderByDesc();
        $this->eagerLoadRelationships();
        $this->setPagination();
        $this->setColumns();
    }

    public function setIds()
    {
        if (!isset($this->parameters['ids'])) {
            return;
        }

        $this->query->whereIn('id', explode(',', $this->parameters['ids']));
    }

    public function excludeIds()
    {
        if (!isset($this->parameters['exclude_ids'])) {
            return;
        }

        $this->query->whereNotIn('id', explode(',', $this->parameters['exclude_ids']));
    }

    public function orderByAsc()
    {
        if (!isset($this->parameters['order_by_asc'])) {
            return;
        }

        $this->query->getQuery()->orders = null;
        $this->query->orderBy($this->parameters['order_by_asc']);
    }

    public function orderByDesc()
    {
        if (!isset($this->parameters['order_by_desc'])) {
            return;
        }

        $this->query->getQuery()->orders = null;
        $this->query->orderByDesc($this->parameters['order_by_desc']);
    }

    public function initQuery()
    {
        if (!isset($this->query)) {
            $this->query = $this->model->newQuery();
        }
    }

    protected function nullToEmptyString($value)
    {
        if (null !== $value) {
            return $value;
        }

        return '';
    }

    protected function eagerLoadRelationships()
    {
        $relations = [];
        if (!empty($this->parameters) && array_key_exists('include', $this->parameters)) {
            $relations = explode(',', $this->parameters['include']);
        }
        //Todo if relation isn't found throw BadException, currently throws 500.
        $this->query->with(array_unique($relations));
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function setPagination()
    {
        if (isset($this->parameters['page'])) {
            $this->page = $this->parameters['page'];
        }

        if (isset($this->parameters['per_page'])) {
            $this->perPage = $this->parameters['per_page'];
        }
    }

    abstract public function getModelName(): string;

    public function setColumns()
    {
        if (isset($this->parameters['fields'])) {
            $this->columns = explode(',', $this->parameters['fields']);
            //Need to set id else pagination breaks
            $this->columns[] = 'id';
        }
    }
}
