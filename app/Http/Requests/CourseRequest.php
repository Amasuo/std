<?php

namespace App\Http\Requests;

use App\Rules\ValidCapacity;
use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST' :
            case 'PUT' :
            {
                return [
                    'name' => 'required|string',
                    'capacity' => ['required', new ValidCapacity()]
                ];
            }
            case 'PATCH' :
            {
                return [
                    'name' => 'sometimes|string',
                    'capacity' => ['sometimes', new ValidCapacity()]
                ];
            }
            default :
                return [];
        }
    }
}
