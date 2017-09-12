<?php

namespace Swis\test\Unit;

use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase;
use Swis\LaravelApi\JsonEncoders\ErrorsJsonEncoder;
use Swis\LaravelApi\JsonEncoders\JsonEncoder;
use Swis\LaravelApi\JsonSchemas\BaseApiSchema;
use Swis\LaravelApi\Models\Responses\RespondHttpUnauthorized;
use Swis\LaravelApi\Repositories\BaseApiRepository;

class JsonEncoderTest extends TestCase
{
    /** @var JsonEncoder $jsonEncoder */
    private $jsonEncoder;

    public function setUp()
    {
        parent::setUp();
        $this->jsonEncoder = new JsonEncoder();
        $this->jsonEncoder->setRepository(app()->make(TestRepository::class));
    }

    /** @test */
    public function it_formats_the_given_object_to_json_api_format()
    {
        $testModel = new TestModel();

        $jsonObject = $this->jsonEncoder->encodeToJson($testModel);

        $this->assertJson($jsonObject);
    }

    /** @test */
    public function it_sets_the_repository()
    {
        $this->jsonEncoder->setRepository(app()->make(TestRepository::class));

        $this->assertInstanceOf(TestRepository::class, $this->jsonEncoder->getRepository());
    }

    /** @test */
    public function it_formats_a_json_error()
    {
        $errorEncoder = new ErrorsJsonEncoder();
        $errorModel = new RespondHttpUnauthorized();

        $error = $errorEncoder->encodeError($errorModel, 'UNAUTHORIZED');

        $this->assertJson($error);
    }
}

class TestRepository extends BaseApiRepository {

    public function getModelName(): string
    {
        return TestModel::class;
    }
}

class TestModel extends Model {
    public $schema = TestSchema::class;
    public $repository = TestRepository::class;
}

class TestSchema extends BaseApiSchema {

}