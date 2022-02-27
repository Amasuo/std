<?php

namespace Tests\Feature;

use App\Enums\HTTPHeader;
use App\Http\Controllers\StudentController;
use App\Http\Requests\CourseRegistrationRequest;
use App\Models\Course;
use App\Models\Student;

class StudentTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->path = '/api/student';
        $this->controller = StudentController::class;
        $this->assertValidator('registerCourse', CourseRegistrationRequest::class);

    }

    private function assertItemEquals($item, $response)
    {
        $this->assertEquals(
            $item->email,
            json_decode($response->getContent())->data->email
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
        $students = Student::all();
        $response = $this->getJson($this->path);
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertCollectionEquals($students, $response);
    }

    /** @test getOne without auth */
    public function test_get_one_without_auth()
    {
        $student = Student::first();
        $response = $this->getJson($this->path . '/' . $student->id);

        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test getOne with auth */
    public function test_get_one_with_auth()
    {
        $this->signIn();
        $student = Student::first();
        $response = $this->getJson($this->path . '/' . $student->id);
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertItemEquals($student, $response);
    }

    /** @test current without auth */
    public function test_get_current_without_auth()
    {
        $response = $this->getJson($this->path . '/current');

        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test current with auth */
    public function test_get_current_with_auth()
    {
        $student = $this->signIn();
        $response = $this->getJson($this->path . '/current');
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertItemEquals($student, $response);
    }

    /** @test register course without auth */
    public function test_register_course_without_auth()
    {
        $temp = new \stdClass();
        $temp->course_id = Course::first()->id;
        $response = $this->postJson($this->path . '/register', json_decode(json_encode($temp), true));
        $response->assertStatus(HTTPHeader::UNAUTHORIZED);
    }

    /** @test register course with auth & valid body */
    public function test_register_course_with_auth_valid_body()
    {
        $temp = new \stdClass();
        $temp->course_id = Course::first()->id;
        $this->signIn();
        $response = $this->postJson($this->path . '/register', json_decode(json_encode($temp), true));
        $response->assertStatus(HTTPHeader::SUCCESS);
        $this->assertEquals($temp->course_id,
            json_decode($response->getContent())->data->courses[0]->id
        );
    }

    /** @test register course with auth & invalid body */
    public function test_register_course_with_auth_invalid_body()
    {
        $this->signIn();
        $response = $this->postJson($this->path . '/register');
        $response->assertStatus(HTTPHeader::UNPROCESSABLE_ENTITY);
    }

    private function registerCourse($id = null)
    {
        $temp = new \stdClass();
        $temp->course_id = $id ?? Course::first()->id;
        return $this->postJson($this->path . '/register', json_decode(json_encode($temp), true));
    }

    /** @test register course with auth & already registered */
    public function test_register_course_with_auth_already_registered()
    {
        $this->signIn();
        $this->registerCourse();
        $response = $this->registerCourse();
        $response->assertStatus(HTTPHeader::BAD_REQUEST);
        $this->assertEquals(__('app.course.student-already-registered'),
            json_decode($response->getContent())->message
        );
    }

    /** @test register course with auth & full */
    public function test_register_course_with_auth_full()
    {
        $this->signIn();
        $course = Course::first();
        $ids = [];
        for ($i = 2; $i <= $course->capacity+1; $i++) { // first user is the one connected
            $ids[] = $i;
        }
        $course->students()->attach($ids);
        $this->registerCourse($course->id);
        $response = $this->registerCourse();
        $response->assertStatus(HTTPHeader::BAD_REQUEST);
        $this->assertEquals(__('app.course.full'),
            json_decode($response->getContent())->message
        );
    }

}
