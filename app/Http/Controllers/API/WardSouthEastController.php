<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\WstCollection;
use App\Models\WardSouthEast;
use Illuminate\Http\Request;

class WardSouthEastController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/ward-south-east/",
     * tags={"Ward South East"},
     * @OA\Parameter(
     *      name="max_lat",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="min_lat",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="max_lng",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="min_lng",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="List of Ward South East",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function index(Request $request)
    {
        $maxEasting = $request->max_lng;
        $maxNorthing = $request->max_lat;
        $minEasting = $request->min_lng;
        $minNorthing = $request->min_lat;

        $data = WardSouthEast::query()
        ->when($minEasting, function ($query) use ($minEasting, $minNorthing,$maxEasting, $maxNorthing) {
            $query->whereRaw("wkb_geometry && ST_Transform(ST_MakeEnvelope($minEasting, $minNorthing,$maxEasting, $maxNorthing, 4326), 27700)");
            // $query->whereRaw("wkb_geometry && ST_Transform(ST_MakeEnvelope(-1.142303,50.784567,-1.037933,50.860084, 4326), 27700);");
        })
        ->get();

        return new WstCollection($data);
    }
}
