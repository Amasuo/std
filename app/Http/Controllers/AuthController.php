<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\AuthHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $res = AuthHelper::register($input);
        return $this->success(__('app.student.register-success'), $res);  // we use __() for translation
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();
        $res = AuthHelper::login($input);
        if ($res) {
            return $this->success(__('app.student.login-success'), $res);
        } else {
            return $this->failure(__('app.student.login-failure'), HTTPHeader::NOT_FOUND);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return $this->success(__('app.student.logout-success'));
    }
}
