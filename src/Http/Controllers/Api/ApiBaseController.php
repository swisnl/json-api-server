<?php

namespace Swis\LaravelApi\Http\Controllers\Api;

use Swis\LaravelApi\Repositories\Repository;
use Swis\LaravelApi\Traits\HandleResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Swis\LaravelApi\JsonEncoders\JsonEncoder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ApiBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HandleResponses;

    protected $respondController;
    protected $jsonEncoder;
    protected $repository;
    protected $request;

    public function __construct(JsonEncoder $jsonEncoder, Repository $repository, Request $request)
    {
        $this->jsonEncoder = $jsonEncoder;
        $this->repository = $repository;
        $this->request = $request;
    }

    public function index()
    {
        $this->repository->setPage($this->request->get('page', null));
        $this->repository->setPerPage($this->request->get('per_page', null));

        $this->authorize('index', $this->repository->getModelName());
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
        $this->authorize('show', $item);

        return $this->respondWithOK($this->jsonEncoder->encodeToJson($item));
    }

    /**
     * Creates a new row in the db.
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function create()
    {
        $this->authorize('create', $this->repository->getModelName());
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
        $this->authorize('update', $this->repository->findById($id));
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

    abstract public function validateResource(Request $request, $id = null);
}
