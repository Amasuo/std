<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Transformers\CourseTransformer;
use Illuminate\Http\Request;

class CourseController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->transformer = new CourseTransformer();
    }

    public function getData(Request $request)
    {
        $courses = Course::all();
        if (!$courses) {
            return $this->failure(__('app.course.model-not-found'), HTTPHeader::NOT_FOUND);
        }
        $data = $this->transform($courses);
        return $this->success(__('app.course.get-all'), $data);
    }

    public function getItem(Request $request)
    {
        $this->validateId();
        $id = $this->model_id;
        $course = Course::findOrFail($id);
        $data = $this->transform($course);
        return $this->success(__('app.course.get-one'), $data);
    }

    public function store(CourseRequest $request)
    {
        $input = $request->validated();
        $course = new Course();
        $course->fill($input);
        $course->save();
        return $this->success(__('app.course.created'), $course);
    }

    public function update(CourseRequest $request)
    {
        $this->validateId();
        $id = $this->model_id;
        $course = Course::findOrFail($id);
        $input = $request->validated();
        $course->fill($input);
        $course->save();
        $data = $this->transform($course);
        return $this->success(__('app.course.updated'), $data);
    }

    public function delete(Request $request)
    {
        $this->validateId();
        $id = $this->model_id;
        $course = Course::findOrFail($id);
        $course->delete();
        return $this->success(__('app.course.deleted'), $course);
    }
}
