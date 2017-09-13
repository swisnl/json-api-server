<?php

namespace Swis\LaravelApi\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

abstract class BaseApiRepository
{
    /**
     * @var Model
     */
    protected $model;

    protected $page;

    protected $perPage;

    protected $relationshipTypes = [
        MorphToMany::class,
        HasMany::class,
        HasManyThrough::class,
        HasOne::class,
        HasOneOrMany::class,
        MorphMany::class,
        MorphOne::class,
        MorphPivot::class,
        MorphTo::class,
    ];

    /**
     * BaseApiRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    public function getAll($columns = ['*'])
    {
        return $this->model->paginate($this->getPerPage(), $columns, 'page', $this->getPage());
    }

    public function findById($value, $columns = ['*'])
    {
        return $this->model->findOrFail($value, $columns);
    }

    public function findByIds(array $ids, $columns = ['*'])
    {
        return $this->model->whereIn('id', $ids)->paginate($this->getPerPage(), $columns, 'page', $this->getPage());
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $resourceKey)
    {
        // todo: gebruik niet hardcoded 'id' maar gebruik model->getKey()
        return $this->model->where($this->model->getKeyName(), $resourceKey)->update($data);
    }

    public function destroy($resourceId)
    {
    }

    public function makeModel()
    {
        $model = app()->make($this->getModelName());
        $this->model = $model;
    }

    public function getModelRelationships(): array //TODO: Skipt dit geen relaties die geen return type hebben?
    {
        $relations = [];

        //TODO: ook op permissies checken welke relaties ze mogen zien.
        $class = new \ReflectionClass(get_class($this->model));
        foreach ($class->getMethods() as $method) {
            $returnType = $method->getReturnType();
            //TODO: niet alleen op ! checken maar beter afvangen
            if (!$returnType) {
                continue;
            }

            if (in_array(pathinfo($returnType)['basename'], $this->relationshipTypes)) {
                $relations[] = $method->getName();
            }
        }

        return $relations;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    abstract public function getModelName(): string;
}
