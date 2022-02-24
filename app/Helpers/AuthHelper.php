<?php

namespace App\Helpers;

use App\Models\Student;

class AuthHelper
{
    public static function register($input)
    {
        $student = Student::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password'])
        ]);

        $access_token = $student->createToken('access-token')->accessToken;
        return self::generateAuthResult($access_token, $student);
    }

    public static function login($input)
    {
        $login_credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];
        if (auth()->attempt($login_credentials)) {
            $student = auth()->user();
            $access_token = $student->createToken('access-token')->accessToken;
            return self::generateAuthResult($access_token, $student);
        } else {
            return false;
        }
    }

    private static function generateAuthResult($access_token, $student)
    {
        $res = new \stdClass();
        $res->access_token = $access_token;
        $res->student = $student;

        return $res;
    }
}
