<?php

namespace Swis\LaravelApi\Http\Controllers\Api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Swis\LaravelApi\Repositories\RepositoryInterface;
use Swis\LaravelApi\Traits\HandleResponses;
use Swis\LaravelApi\Traits\HasPermissionChecks;

abstract class BaseApiController extends Controller
{
    use DispatchesJobs, ValidatesRequests, HandleResponses, HasPermissionChecks;

    protected $respondController;
    protected $repository;
    protected $request;

    public function __construct(RepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request = $request;

        $this->repository->setParameters($request->query());
    }

    public function index()
    {
        $perPage = $this->request->get('per_page', null);
        $page = $this->request->get('page', null);
        $items = $this->repository->setUser($this->request->user())
            ->paginate($perPage, $page, null, $this->request->query());

        $this->authorizeAction('index');

        return $this->respondWithCollection($items);
    }

    /**
     * This method returns an object by requested id if you have the permissions.
     *
     * @param $id
     *
     * @return string
     */
    public function show($id)
    {
        $item = $this->repository->findById($id);
        $this->authorizeAction('show', $item);

        return $this->respondWithOK($item);
    }

    /**
     * Creates a new row in the db.
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function create()
    {
        $this->authorizeAction('create');
        $createdResource = $this->repository->create($this->validateObject());

        return $this->respondWithCreated($createdResource);
    }

    /**
     * Updates an item in the db.
     *
     * @param $id
     *
     * @return $this
     */
    public function update($id)
    {
        $this->authorizeAction('update', $this->repository->findById($id));

        $this->repository->update($this->validateObject($id), $id);

        $updatedItem = $this->repository->findById($id);

        return $this->respondWithOK($updatedItem);
    }

    /**
     * Deletes an item in the db. Will probably not be implemented.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $this->authorizeAction('delete', $this->repository->findById($id));

        $this->repository->destroy($id);

        return $this->respondWithNoContent();
    }

    protected function authorizeAction($policyMethod, $requestedObject = null)
    {
        if (!config('laravel_api.checkForPermissions')) {
            return;
        }

        $this->checkIfUserHasPermissions(
            $policyMethod,
            $this->repository->getModelName(),
            $requestedObject
        );
    }

    public function validateObject($id = null)
    {
        $model = $this->repository->makeModel();
        $this->validate($this->request, $model->getRules($id));

        $attributes = $model->getFillable();
        $values = json_decode($this->request->getContent(), true);

        foreach ($values as $value => $data) {
            if (in_array($value, $attributes)) {
                continue;
            }

            unset($values[$value]);
        }

        return $values;
    }
}
