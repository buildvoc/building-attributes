<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiJsonResponse;
use App\Models\SxData;

class SxDataController extends Controller
{
    public function index()
    {
        return ApiJsonResponse::sendOkResponse(['sx_data' => SxData::all()]);
    }
}
