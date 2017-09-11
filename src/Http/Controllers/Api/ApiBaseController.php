<?php

namespace Swis\LaravelApi\Http\Controllers\Api;

use Illuminate\Routing\Route;
use Swis\LaravelApi\Repositories\Repository;
use Swis\LaravelApi\Traits\HandleResponses;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Swis\LaravelApi\JsonEncoders\JsonEncoder;
use Swis\LaravelApi\Traits\HasPermissionChecks;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ApiBaseController extends Controller
{
    use DispatchesJobs, ValidatesRequests, HandleResponses, HasPermissionChecks;

    protected $respondController;
    protected $jsonEncoder;
    protected $repository;
    protected $request;
    protected $route;

    public function __construct(JsonEncoder $jsonEncoder, Repository $repository, Request $request, Route $route)
    {
        $this->jsonEncoder = $jsonEncoder;
        $this->repository = $repository;
        $this->request = $request;
        $this->route = $route;
    }

    public function index()
    {
        $this->repository->setPage($this->request->get('page', null));
        $this->repository->setPerPage($this->request->get('per_page', null));

        $this->validateUser();

        if ($this->request->exists('ids')) {
            return $this->getByUrlInputIds();
        }

        $items = $this->repository->getAll();

        return $this->respondWithCollection($this->jsonEncoder->encodeToJson($items));
    }

    private function getByUrlInputIds()
    {
        $ids = explode(',', $this->request->get('ids', null));
        $items = $this->repository->findByIds($ids);

        return $this->respondWithCollection($this->jsonEncoder->encodeToJson($items));
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
        $this->validateUser($item);

        return $this->respondWithOK($this->jsonEncoder->encodeToJson($item));
    }

    /**
     * Creates a new row in the db.
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function create()
    {
        $this->validateUser();
        $createdResource = $this->repository->create($this->validateResource($this->request));
        $encodedResource = $this->jsonEncoder->encodeToJson($createdResource);

        return $this->respondWithCreated($encodedResource);
    }

    /**
     * Updates an item in the db.
     *
     * @param $id
     *
     * @return bool
     */
    public function update($id)
    {
        $this->validateUser($this->repository->findById($id));
        $updated = $this->repository->update($this->validateResource($this->request, $id), $id);
        if (!$updated) {
            throw new NotFoundHttpException();
        }

        return $this->respondwithNoContent();
    }

    /**
     * Deletes an item in the db. Will probably not be implemented.
     *
     * @param $id
     */
    public function delete($id)
    {
    }

    protected function validateUser($requestedObject = null, $policyActionName = null)
    {
        if ($this->checkForPermissions()) {
            $this->checkIfUserHasPermissions($this->route, $this->repository->getModelName(),
                $requestedObject, $policyActionName);
        }
    }

    abstract public function validateResource(Request $request, $id = null);

    abstract public function checkForPermissions(): bool;
}
