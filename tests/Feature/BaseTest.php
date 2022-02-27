<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

class BaseTest extends TestCase
{
    use DatabaseMigrations;
    use AdditionalAssertions;

    protected $controller;
    protected $path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function signIn()
    {
        $student = Student::first();
        $this->actingAs($student, 'api');
        return $student;
    }

    protected function assertCollectionEquals($collection, $response)
    {
        $this->assertEquals(
            count($collection),
            count(json_decode($response->getContent())->data)
        );
    }

    protected function assertValidator($method, $requestClass)
    {
        $this->assertActionUsesFormRequest(
            $this->controller,
            $method,
            $requestClass
        );
    }
}
