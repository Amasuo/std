<?php

namespace App\Transformers;

use App\Models\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
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
    public function transform(Student $row)
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'email' => $row->email,
            'courses' => $row->courses
        ];
    }
}
