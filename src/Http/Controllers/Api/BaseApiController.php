<?php

namespace Swis\JsonApi\Server\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
     * @return $this
     * @throws ForbiddenException
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
     * @return string
     * @throws ForbiddenException
     */
    public function show($id)
    {
        $item = $this->repository->findById($id);
        if (config('laravel_api.permissions.checkDefaultShowPermission')) {
            $this->authorizeAction('show', $item);
        }

        return $this->respondWithOK($item);
    }

    /**
     * Creates a new row in the db.
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws ForbiddenException
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
     * @return $this
     * @throws ForbiddenException
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws ForbiddenException
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
     * @throws ForbiddenException
     */
    protected function authorizeAction($policyMethod, $item)
    {
        try {
            $this->authorize($policyMethod, $item);
        } catch (AuthorizationException $e) {
            throw new ForbiddenException('This action is forbidden');
        }
    }

    public function validateObject($id = null)
    {
        if(!$this->request->input('data')) {
            throw new JsonException('No data object');
        }

        if(!$this->request->input('data.type')) {
            throw new JsonException('No type attribute');
        }
        //TODO get rules custom validator instead of model?
        $model = $this->repository->makeModel();
        $this->validate($this->request, $model->getRules($id));

        $attributes = $model->getFillable();
        $values = $this->request->input();
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
