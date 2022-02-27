<?php

namespace Tests\Feature;

use App\Enums\HTTPHeader;
use App\Http\Controllers\CourseController;
use App\Http\Requests\CourseRequest;
use App\Models\Course;


class CourseTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->path = '/api/course';
        $this->controller = CourseController::class;
        $this->assertValidator('store', CourseRequest::class);
        $this->assertValidator('update', CourseRequest::class);
    }

    private function assertItemEquals($item, $response)
    {
        $this->assertEquals(
            $item->name,
            json_decode($response->getContent())->data->name
        );
    }

    /** @test getAll without auth */
    public function test_get_all_without_auth()
    {
        $response = $this->getJson($this->path);

        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test getAll with auth */
    public function test_get_all_with_auth()
    {
        $this->signIn();
        $courses = Course::all();
        $response = $this->getJson($this->path);
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertCollectionEquals($courses, $response);
    }

    /** @test getOne without auth */
    public function test_get_one_without_auth()
    {
        $student = Course::first();
        $response = $this->getJson($this->path . '/' . $student->id);

        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test getOne with auth */
    public function test_get_one_with_auth()
    {
        $this->signIn();
        $course = Course::first();
        $response = $this->getJson($this->path . '/' . $course->id);
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertItemEquals($course, $response);
    }

    private function store($name, $capacity)
    {
        $course = new \stdClass();
        $course->name = $name;
        $course->capacity = $capacity;
        return $this->postJson($this->path, json_decode(json_encode($course), true));
    }

    /** @test store without auth */
    public function test_store_without_auth()
    {
        $response = $this->store('test', intval(config('consts.course.capacity_min')));
        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test store with auth & valid body */
    public function test_store_with_auth_valid_body()
    {
        $course = new \stdClass();
        $course->name = 'test';
        $course->capacity = intval(config('consts.course.capacity_min'));
        $this->signIn();
        $response = $this->postJson($this->path, json_decode(json_encode($course), true));
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertItemEquals($course, $response);
    }

    /** @test store with auth & invalid body */
    public function test_store_with_auth_invalid_body()
    {
        $this->signIn();
        // capacity : min - 1
        $response = $this->store('test',
            intval(config('consts.course.capacity_min')) - 1
        );
        $response->assertStatus(HTTPHeader::UNPROCESSABLE_ENTITY);
        // capacity : max + 1
        $response = $this->store('test',
            intval(config('consts.course.capacity_max')) + 1
        );
        $response->assertStatus(HTTPHeader::UNPROCESSABLE_ENTITY);
    }

    private function update($name, $capacity)
    {
        $course = new \stdClass();
        $course->name = $name;
        $course->capacity = $capacity;
        $dbCourse = Course::first();
        return $this->putJson($this->path . '/' . $dbCourse->id,
            json_decode(json_encode($course), true)
        );
    }

    /** @test update without auth */
    public function test_update_without_auth()
    {
        $response = $this->update('test', intval(config('consts.course.capacity_min')));
        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test update with auth & valid body */
    public function test_update_with_auth_valid_body()
    {
        $course = new \stdClass();
        $course->name = 'test';
        $course->capacity = intval(config('consts.course.capacity_min'));
        $this->signIn();
        $dbCourse = Course::first();
        $response = $this->putJson($this->path . '/' . $dbCourse->id,
            json_decode(json_encode($course), true)
        );
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertItemEquals($course, $response);
    }

    /** @test update with auth & invalid body */
    public function test_update_with_auth_invalid_body()
    {
        $this->signIn();
        // capacity : min - 1
        $response = $this->update('test',
            intval(config('consts.course.capacity_min')) - 1
        );
        $response->assertStatus(HTTPHeader::UNPROCESSABLE_ENTITY);
        // capacity : max + 1
        $response = $this->update('test',
            intval(config('consts.course.capacity_max')) + 1
        );
        $response->assertStatus(HTTPHeader::UNPROCESSABLE_ENTITY);
    }

    private function destroy()
    {
        $course = Course::first();
        return $this->deleteJson($this->path . '/' . $course->id);
    }

    /** @test delete without auth */
    public function test_delete_without_auth()
    {
        $response = $this->destroy();
        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test delete with auth */
    public function test_delete_with_auth()
    {
        $this->signIn();
        $response = $this->destroy();
        $response->assertStatus(HTTPHeader::SUCCESS);
    }
}
