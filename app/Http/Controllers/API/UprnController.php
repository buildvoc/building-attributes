<?php

namespace App\Http\Controllers\API;

use App\Models\ApiJsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UprnOpen;
use App\Models\UprnTopo;
use Illuminate\Http\Request;

class UprnController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/uprn",
     * tags={"UPRN"},
     * @OA\Response(response=200, description="List of OPEN UPRN", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        $data = UprnOpen::query()->paginate(20);
        return ApiJsonResponse::sendOkResponse(['uprn' => $data]);
    }

    /**
     * @OA\Get(
     * path="/api/v1/uprn/joined",
     * tags={"UPRN"},
     * @OA\Response(response=200, description="List of UPRN TOPO JOINED", @OA\JsonContent()),
     * )
     */
    public function joined()
    {
        $data = UprnTopo::query()->paginate(20);
        return ApiJsonResponse::sendOkResponse(['uprn' => $data]);
    }
}
