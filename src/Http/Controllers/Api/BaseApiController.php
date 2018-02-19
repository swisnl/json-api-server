<?php

namespace Swis\JsonApi\Server\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Swis\JsonApi\Server\Exceptions\BadRequestException;
use Swis\JsonApi\Server\Exceptions\ForbiddenException;
use Swis\JsonApi\Server\Exceptions\JsonException;
use Swis\JsonApi\Server\Repositories\RepositoryInterface;
use Swis\JsonApi\Server\Traits\HandleResponses;

abstract class BaseApiController extends Controller
{
    use DispatchesJobs, ValidatesRequests, HandleResponses, AuthorizesRequests;

    protected $respondController;
    protected $repository;
    protected $request;

    public function __construct(RepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request = $request;

        $this->repository->setParameters($request->query());
    }

    /**
     * @throws ForbiddenException
     *
     * @return $this
     */
    public function index()
    {
        $items = $this->repository
            ->paginate($this->request->query());

        if (config('laravel_api.permissions.checkDefaultIndexPermission')) {
            $this->authorizeAction('index', $this->repository->getModelName());
        }

        return $this->respondWithCollection($items);
    }

    /**
     * This method returns an object by requested id if you have the permissions.
     *
     * @param $id
     *
     * @throws ForbiddenException
     *
     * @return string
     */
    public function show($id)
    {
        $item = $this->repository->findById($id, $this->request->query());
        if (config('laravel_api.permissions.checkDefaultShowPermission')) {
            $this->authorizeAction('show', $item);
        }

        return $this->respondWithOK($item);
    }

    /**
     * Creates a new row in the db.
     *
     * @throws ForbiddenException
     * @throws JsonException
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function create()
    {
        if (config('laravel_api.permissions.checkDefaultCreatePermission')) {
            $this->authorizeAction('create', $this->repository->getModelName());
        }
        $createdResource = $this->repository->create($this->validateObject());

        return $this->respondWithCreated($createdResource);
    }

    /**
     * Updates an item in the db.
     *
     * @param $id
     *
     * @throws ForbiddenException
     * @throws JsonException
     *
     * @return $this
     */
    public function update($id)
    {
        if (config('laravel_api.permissions.checkDefaultUpdatePermission')) {
            $this->authorizeAction('update', $this->repository->findById($id));
        }

        return $this->respondWithOK($this->repository->update($this->validateObject($id), $id));
    }

    /**
     * Deletes an item in the db. Will probably not be implemented.
     *
     * @param $id
     *
     * @throws ForbiddenException
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        if (config('laravel_api.permissions.checkDefaultDeletePermission')) {
            $this->authorizeAction('delete', $this->repository->findById($id));
        }

        $this->repository->destroy($id);

        return $this->respondWithNoContent();
    }

    /**
     * @param $policyMethod
     * @param $item
     *
     * @throws ForbiddenException
     */
    protected function authorizeAction($policyMethod, $item)
    {
        //Todo if item is empty, it always returns forbidden.
        try {
            $this->authorize($policyMethod, $item);
        } catch (AuthorizationException $e) {
            throw new ForbiddenException('This action is forbidden');
        }
    }

    /**
     * @param null $id
     *
     * @throws JsonException
     *
     * @return array|string
     */
    public function validateObject($id = null)
    {
        //TODO refactor this to a helper function, used also in BaseApiResource & IdentifierResource
        $resourceClass = class_basename($this->repository->getModelName());
        $resourcePlural = str_plural($resourceClass);
        $lowerCaseResourceType = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $resourcePlural));
        $input = $this->request->input('data');
        if (!$input) {
            throw new BadRequestException('No data object');
        }
        if (!$input['type']) {
            throw new BadRequestException('Type attribute is not present');
        }

        if ($input['type'] !== $lowerCaseResourceType) {
            throw new BadRequestException('Wrong type attribute');
        }
        //TODO get rules custom validator instead of model?
        $model = $this->repository->makeModel();
        $this->validate($this->request, $model->getRules($id));

        $attributes = $model->getFillable();
        $values = $input;

        //TODO Check if $values exist
        foreach ($values as $value => $data) {
            if (in_array($value, $attributes)) {
                continue;
            }

            unset($values[$value]);
        }

        return $values;
    }
}
