<?php

namespace Swis\LaravelApi\JsonEncoders;

use Illuminate\Contracts\Pagination\Paginator;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Swis\LaravelApi\Repositories\Repository;

class JsonEncoder
{
    /**
     * @var Repository
     * */
    protected $repository;
    protected $modelsToEncode;

    /**
     * Encodes the given data to the JSON API format.
     *
     * @param $object
     *
     * @return string
     */
    public function encodeToJson($object)
    {
        $encoder = Encoder::instance($this->getModelsToEncode(), $this->getEncoderOptions())->withMeta($this->getMeta($object));

        return $encoder->encodeData($object, new EncodingParameters($this->getIncludes()));
    }

    protected function getEncoderOptions()
    {
        return new EncoderOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES, env('API_URL'));
    }

    protected function getIncludes()
    {
        return explode(',', request()->get('include', null));
    }

    protected function getMeta($items)
    {
        if (!$items instanceof Paginator) {
            return null;
        }

        return [
            'total' => $items->count(),
            'per_page' => $items->perPage(),
            'current_page' => $items->currentPage(),
            'total_pages' => $items->lastPage(),
            'previous_page_url' => $items->previousPageUrl(),
            'next_page_url' => $items->nextPageUrl(),
        ];
    }

    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * This method generates Model names and ModelSchema names based on the repository model.
     *
     * @return array|mixed
     */
    protected function getModelsToEncode()
    {
        $model = $this->repository->getModel();
        $modelPath = get_class($model);
        $this->insertIntoArray($modelPath);

        $relationships = $this->repository->getResourceRelationships();
        foreach ($relationships as $relation) {
            $modelPath = get_class($model->$relation()->getRelated());
            $this->insertIntoArray($modelPath);
        }

        return $this->modelsToEncode;
    }

    /**
     * Helper function to insert the model and schema into an array.
     *
     * @param $modelPath
     *
     * @return mixed
     */
    protected function insertIntoArray($modelPath)
    {
        $schemaName = $this->createSchemaName($modelPath);
        $this->modelsToEncode[$modelPath] = $schemaName;

        return $this;
    }

    protected function createSchemaName($modelPath)
    {
        $modelSchema = app()->make($modelPath)->schema;
        if ($modelSchema !== null) {
            return $modelSchema;
        }

        $schema = $modelPath.'\\'.class_basename($modelPath).'Schema';
        if (!class_exists($schema)) {
            return 'App\JsonSchemas\\'.class_basename($modelPath).'Schema';
            //TODO: kan mogelijk leiden tot meerdere van dezelfde naam, check dit asap.
        }

        return $schema;
    }
}
