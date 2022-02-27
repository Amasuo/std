<?php

namespace App\Enums;

class HTTPHeader
{
    public const SUCCESS = 200;
    public const CREATED = 201;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const UNPROCESSABLE_ENTITY = 422;
}
