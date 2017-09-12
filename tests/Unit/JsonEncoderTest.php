<?php

namespace Swis\test\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\TestCase;
use Swis\LaravelApi\JsonEncoders\ErrorsJsonEncoder;
use Swis\LaravelApi\JsonEncoders\JsonEncoder;
use Swis\LaravelApi\Models\Responses\RespondHttpUnauthorized;
use Swis\sample\Repositories\SampleRepository;
use Swis\sample\Sample;

class JsonEncoderTest extends TestCase
{
    use DatabaseTransactions;

    /** @var JsonEncoder $jsonEncoder */
    private $jsonEncoder;

    public function setUp()
    {
        parent::setUp();
        $this->jsonEncoder = new JsonEncoder();
        $this->jsonEncoder->setRepository(app()->make(SampleRepository::class));
    }

    /** @test */
    public function it_formats_the_given_object_to_json_api_format()
    {
        $user = factory(Sample::class)->create();

        $jsonUser = $this->jsonEncoder->encodeToJson($user);

        $this->assertJson($jsonUser);
    }

    /** @test */
    public function it_sets_the_repository()
    {
        $this->jsonEncoder->setRepository(app()->make(SampleRepository::class));

        $this->assertInstanceOf(SampleRepository::class, $this->jsonEncoder->getRepository());
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
