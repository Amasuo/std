<?php

namespace App\Transformers;

use App\Models\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Course $row)
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'capacity' => $row->capacity,
            'available' => ($row->capacity > (count($row->students))),
            'students' => $row->students
        ];
    }
}
