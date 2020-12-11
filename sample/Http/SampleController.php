<?php

namespace App\Http\Controllers\Api;

use App\Repositories\SampleRepository;
use Illuminate\Http\Request;
use Swis\JsonApi\Server\Http\Controllers\Api\BaseApiController;
use Swis\JsonApi\Server\Repositories\RepositoryInterface;

class SampleController extends BaseApiController
{
    /** @var RepositoryInterface */
    protected $repository;

    public function __construct(SampleRepository $repository, Request $request)
    {
        $this->repository = $repository;
        parent::__construct($this->repository, $request);
    }
}
