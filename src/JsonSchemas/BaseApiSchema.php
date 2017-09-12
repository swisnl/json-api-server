<?php

namespace Swis\LaravelApi\JsonSchemas;

use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Schema\SchemaProvider;
use Swis\LaravelApi\Exceptions\RepositoryNotFoundException;
use Swis\LaravelApi\Repositories\BaseApiRepository;

abstract class BaseApiSchema extends SchemaProvider
{
    /** @var  BaseApiRepository $repository */
    protected $repository;

    public function __construct(SchemaFactoryInterface $factory)
    {
        $this->setResourceType();
        $this->setRepository();
        parent::__construct($factory);
    }

    protected $resourceType;

    /**
     * Get resource identity.
     *
     * @param object $resource
     *
     * @return string
     */
    public function getId($resource)
    {
//        Model::getKey()
        return $resource->id; //getKey //todo
    }

    /**
     * Get resource attributes.
     *
     * @param object $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        $resource->addHidden('id');

        return $resource->attributesToArray();
    }

    /**
     * Gets all relationships which are assigned to be passed to the request.
     * self and related URLs could be shown/hidden for each relationship individually with
     * self::SHOW_SELF and self::SHOW_RELATED parameters.
     *
     * @param object $object
     * @param bool $isPrimary
     * @param array $includeList
     *
     * @return array
     */
    public function getRelationships($object, $isPrimary, array $includeList)
    {
        $relations = [];
        foreach ($this->repository->getModelRelationships() as $relation) {
            $relations[$relation] = [
                self::DATA => $object->$relation,
                self::SHOW_SELF => false,
                self::SHOW_RELATED => false,
            ];
        }

        return $relations;
    }

    protected function setRepository()
    {
        $modelName = $this->extractClassName();
        $modelWithPath = $this->extractModelWithPath();

        if (class_exists($modelWithPath)) {
            $modelRepository = app()->make($modelWithPath)->repository;
            if ($modelRepository !== null) {
                $this->repository = app()->make($modelRepository);

                return $this;
            }
        }

        // TODO: Met Arnaud bespreken of we niet gewoon altijd willen configureren in de Model. Dus als $modelRepository null is, throw new Exception

        $repository = $modelWithPath . 'Repository';
        if (class_exists($repository)) {
            $this->repository = app()->make($repository);

            return $this;
        }

        $repositoryInRepositoriesDir = 'App\Repositories\\' . $modelName . 'Repository';
        if (class_exists($repositoryInRepositoriesDir)) {
            $this->repository = app()->make($repositoryInRepositoriesDir);

            return $this;
        }

        $repositoryInRoot = 'App\\' . $modelName . 'Repository';
        if (class_exists($repositoryInRoot)) {
            $this->repository = app()->make($repositoryInRoot);

            return $this;
        }

        throw new RepositoryNotFoundException('No repository found for: ' . $modelName);
    }

    protected function setResourceType()
    {
        if (!empty($this->resourceType)) {
            return;
        }
        $this->resourceType = snake_case($this->extractClassName());
    }

    protected function extractClassName()
    {
        $calledClassname = $this->extractModelWithPath();
        $calledClassname = substr($calledClassname, strrpos($calledClassname, '\\') + 1);

        return $calledClassname;
    }

    protected function extractModelWithPath()
    {
        return substr(get_called_class(), 0, strrpos(get_called_class(), 'Schema'));
    }
}
