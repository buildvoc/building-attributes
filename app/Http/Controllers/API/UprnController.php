<?php

namespace App\Http\Controllers\API;

use App\Models\ApiJsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UprnTopo;
use Illuminate\Http\Request;

class UprnController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/uprn",
     * tags={"UPRN"},
     * @OA\Response(response=200, description="List of UPRN Topo", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        $data = UprnTopo::query()->paginate(20);
        return ApiJsonResponse::sendOkResponse(['uprn' => $data]);
    }
}
