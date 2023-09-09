<?php

namespace App\Http\Controllers\API;

use App\Models\ApiJsonResponse;
use App\Models\TopographyLayerSX;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopographyLayerSXController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/layer-sx/",
     * tags={"Topography Layer SX"},
     * @OA\Response(response=200, description="Get all Topography Layer SX Data", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        $data = TopographyLayerSX::query()->paginate(20);
        return ApiJsonResponse::sendOkResponse(['layer_sx' => $data]);
    }

    /**
     * @OA\Get(
     * path="/api/v1/layer-sx/nearest",
     * tags={"Topography Layer SX"},
     * @OA\Parameter(
     *      name="latitude",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="longitude",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="radius",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Get nearest SX with radius",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function nearest(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?: 100;

        $data = TopographyLayerSX::with('sx_data')
        // ->when($latitude, function ($query) use ($latitude, $longitude, $radius) {
        //     $query->whereRaw("public.ST_DWithin(geom, ST_SetSRID(ST_Point($longitude,$latitude), 4326), $radius)");
        // })
        ->where('toid', 'osgb1000002070300013')
        ->limit(1)
        ->get();

        return ApiJsonResponse::sendOkResponse(['layer_sx' => $data]);
    }
}
