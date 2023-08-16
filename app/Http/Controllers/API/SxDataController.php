<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiJsonResponse;
use App\Models\SxData;

class SxDataController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/sx-data/",
     * tags={"SX Data"},
     * @OA\Response(response=200, description="Get all SX Data", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        return ApiJsonResponse::sendOkResponse(['sx_data' => SxData::all()]);
    }
}
