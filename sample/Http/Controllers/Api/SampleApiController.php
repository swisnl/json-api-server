<?php

namespace Sample\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Swis\LaravelApi\Http\Controllers\Api\BaseApiController;
use Swis\LaravelApi\Repositories\RepositoryInterface;
use Tests\TestClasses\TestRepository;

class SampleApiController extends BaseApiController
{
    /** @var RepositoryInterface $repository */
    protected $repository;

    public function __construct(TestRepository $repository, Request $request, Route $route)
    {
        parent::__construct($repository, $request, $route);
    }
}
