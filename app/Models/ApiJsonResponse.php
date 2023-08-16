<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiJsonResponse extends Model
{
    public static function sendOkResponse($data, int $code = 200)
    {
        return response()->json([
            'success' => true,
            'http_code' => $code,
            'data' => $data
        ], $code);
    }

    public static function sendErrors($errors, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'http_code' => $code,
            'errors' => $errors
        ], $code);
    }
}
