<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Http\Requests\CourseRegistrationRequest;
use App\Models\Course;
use App\Models\Student;
use App\Transformers\StudentTransformer;
use Illuminate\Http\Request;

class StudentController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->transformer = new StudentTransformer();
    }

    public function getData(Request $request)
    {
        $data = Student::with('courses')->get();
        if (!$data) {
            return $this->failure(__('app.student.model-not-found'), HTTPHeader::NOT_FOUND);
        }
        return $this->success(__('app.student.get-all'), $data);
    }

    public function getItem(Request $request)
    {
        $this->validateId();
        $student = Student::findOrFail($this->model_id);
        $data = $this->transform($student);
        return $this->success(__('app.student.get-one'), $data);
    }

    public function current()
    {
        $student = auth('api')->user();
        if (!$student) {
            return $this->failure(__('app.student.current-not-found'));
        }
        $this->transform($student);
        return $this->success(__('app.student.current-found'), $student);
    }

    public function registerCourse(CourseRegistrationRequest $request)
    {
        $input = $request->validated();
        $course_id = $input['course_id'];
        $course = Course::findOrFail($course_id);
        $student = auth('api')->user();
        if ($course->students->contains($student->id)) {
            return $this->failure(__('app.course.student-already-registered'));
        }
        if (!$course->isAvailable()) {
            return $this->failure(__('app.course.full'));
        }
        $course->students()->attach($student->id);
        $data = $this->transform($student);
        return $this->success(__('app.student.registered'), $data);
    }
}
