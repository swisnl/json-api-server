<?php

namespace Tests\Unit;

use App\Sample;
use App\SamplePermissions;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SampleAuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $baseUrl;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();

        $this->user->givePermissionTo(SamplePermissions::RETRIEVE_SAMPLE);
        $this->user->givePermissionTo(SamplePermissions::RETRIEVE_ALL_SAMPLES);
        $this->user->givePermissionTo(SamplePermissions::CREATE_SAMPLE);
        $this->user->givePermissionTo(SamplePermissions::UPDATE_SAMPLE);
        $this->user->givePermissionTo(SamplePermissions::DELETE_SAMPLE);

        $this->baseUrl = env('API_URL').'/samples/';
        $this->withHeaders(['Accept' => 'application/vnd.api+json']);
    }

    /** @test */
    public function it_creates_an_sample_unauthenticated()
    {
        $response = $this->post($this->baseUrl);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_creates_an_sample_authenticated()
    {
        Passport::actingAs($this->user);

        $response = $this->post($this->baseUrl);
        $response->assertStatus(201);
    }

    /** @test */
    public function it_updates_an_sample_unauthenticated()
    {
        $sample = factory(Sample::class)->create();

        $response = $this->put($this->baseUrl.$sample->id, $sample->toArray());
        $response->assertStatus(401);
    }

    /** @test */
    public function it_updates_an_sample_authenticated()
    {
        Passport::actingAs($this->user);

        $sample = factory(Sample::class)->create();

        $response = $this->put($this->baseUrl.$sample->id, $sample->toArray());

        $response->assertStatus(200);
    }

    /** @test */
    public function it_retrieves_an_sample_unauthenticated()
    {
        $sample = factory(Sample::class)->create();

        $response = $this->get($this->baseUrl.$sample->id);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_retrieves_an_sample_authenticated()
    {
        Passport::actingAs($this->user);

        $sample = factory(Sample::class)->create();

        $response = $this->get($this->baseUrl.$sample->id);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_retrieves_all_samples_unauthenticated()
    {
        factory(Sample::class, 3)->create();

        $response = $this->get($this->baseUrl);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_retrieves_all_samples_authenticated()
    {
        Passport::actingAs($this->user);

        factory(Sample::class, 3)->create();

        $response = $this->get($this->baseUrl);
        $response->assertStatus(206);
    }

    /** @test */
    public function it_deletes_an_sample_unauthenticated()
    {
        $sample = factory(Sample::class)->create();

        $response = $this->delete($this->baseUrl.$sample->id);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_deletes_an_sample_authenticated()
    {
        Passport::actingAs($this->user);

        $sample = factory(Sample::class)->create();

        $response = $this->delete($this->baseUrl.$sample->id);
        $response->assertStatus(204);
    }
}
