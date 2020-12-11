<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 19-2-2018
 * Time: 11:52.
 */

namespace Tests\TestClasses;

use Illuminate\Http\Request;
use Swis\JsonApi\Server\Http\Controllers\Api\BaseApiController;
use Swis\JsonApi\Server\Repositories\RepositoryInterface;

class TestController extends BaseApiController
{
    /** @var RepositoryInterface */
    protected $repository;

    public function __construct(TestRepository $repository, Request $request)
    {
        $this->repository = $repository;
        parent::__construct($this->repository, $request);
    }
}
