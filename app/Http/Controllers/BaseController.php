<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;

class BaseController extends Controller
{
    use ApiResponse;

    protected $fractal;
    protected $transformer;

    protected $model_id = null;

    public function __construct(Request $request)
    {
        if (isset($request->id)) {
            $this->model_id = $request->id;
        }
        $this->fractal = new Manager();
    }

    protected function validateId()
    {
        if (is_null($this->model_id)) {
            $this->abort(__('app.generic.id-not-found'), HTTPHeader::NOT_FOUND);
        }
    }

    protected function transform($data)
    {
        if ($data instanceof EloquentCollection) {
            $data = new FractalCollection($data, $this->transformer);
        } else {
            $data = new FractalItem($data, $this->transformer);
        }
        return json_decode($this->fractal->createData($data)->toJson());
    }
}
