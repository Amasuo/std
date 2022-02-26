<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
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
        $students = Student::all();
        if (!$students) {
            return $this->failure(__('app.student.model-not-found'), HTTPHeader::NOT_FOUND);
        }
        $data = $this->transform($students);
        return $this->success(__('app.student.get-all'), $data);
    }

    public function getItem(Request $request)
    {
        $this->validateId();
        $id = $this->model_id;
        $student = Student::findOrFail($id);
        $data = $this->transform($student);
        return $this->success(__('app.student.get-one'), $data);
    }

    public function current()
    {
        $student = auth()->user();
        if (!$student) {
            return $this->failure(__('app.student.current-not-found'));
        }
        $this->transform($student);
        return $this->success(__('app.student.current-found'), $student);
    }

    public function registerCourse(Request $request)
    {
        $request->validate(
            [
                'course_id' => 'required|integer'
            ]
        );
        $course_id = $request->get('course_id');
        $course = Course::findOrFail($course_id);
        if ($course->students->contains(auth()->user()->id)) {
            return $this->failure(__('app.course.student-already-registered'));
        }
        if (!$course->isAvailable()) {
            return $this->failure(__('app.course.full'));
        }
        $course->students()->attach(auth()->user()->id);
        return $this->success(__('app.student.registered'), $course);
    }
}
