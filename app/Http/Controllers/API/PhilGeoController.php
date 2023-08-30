<?php

namespace App\Http\Controllers\API;

use App\Models\PhilGeo;
use App\Models\ApiJsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PhilGeoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PhilGeoController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/geo/",
     * tags={"Geo"},
     * @OA\Parameter(
     *      name="minEasting",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="minNorthing",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="maxEasting",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="maxNorthing",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Get all geos",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function index(Request $request)
    {
        $minEasting = $request->minEasting;
        $minNorthing = $request->minNorthing;
        $maxEasting = $request->maxEasting;
        $maxNorthing = $request->maxNorthing;

        $data = PhilGeo::query()
        ->when($minEasting, function ($query) use ($minEasting, $minNorthing,$maxEasting, $maxNorthing) {
            $query->whereRaw("geom && ST_MakeEnvelope($minEasting, $minNorthing,$maxEasting, $maxNorthing, 4326)");
            //$query->whereRaw("public.ST_DWithin(geom, ST_SetSRID(ST_Point($minNorthing,$minEasting), 4326), 30000)")
        })
        ->get();

        // SELECT * FROM phil_geos WHERE ST_DWithin(geom, ST_SetSRID(ST_Point(0.08318185,51.2430483), 4326), 30000000)

        return ApiJsonResponse::sendOkResponse(['phil_geos' => $data]);
    }

    /**
     * @OA\Get(
     * path="/api/v1/geo/nearest",
     * tags={"Geo"},
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
     *      description="Get nearest geos with radius",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function nearest(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?: 100;

        $data = PhilGeo::query()
        ->when($latitude, function ($query) use ($latitude, $longitude, $radius) {
            $query->whereRaw("public.ST_DWithin(geom, ST_SetSRID(ST_Point($longitude,$latitude), 4326), $radius)");
        })
        ->get();

        // SELECT * FROM phil_geos WHERE ST_DWithin(geom, ST_SetSRID(ST_Point(0.08318185,51.2430483), 4326), 30000000)

        return ApiJsonResponse::sendOkResponse(['phil_geos' => $data]);
    }

    /**
     * @OA\Post(
     * path="/api/v1/geo/",
     * tags={"Geo"},
     * @OA\Parameter(
     *      name="geom",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="osid",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="toid",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="height_max",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="symbol",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Response(response=200, description="Post Geo", @OA\JsonContent()),
     * )
     */
    public function store(PhilGeoRequest $request)
    {
        $geom = json_encode($request->geom);

        $data = PhilGeo::create([
            'osid' => $request->osid,
            'toid' => $request->toid,
            'height_max' => $request->height_max,
            'symbol' => $request->symbol,
            'geom' => DB::raw("public.ST_SetSRID(public.ST_GeomFromGeoJSON('$geom'), 4326)"),
        ]);

        $data = PhilGeo::find($data->id);

        return ApiJsonResponse::sendOkResponse(["geojson" => $data]);
    }

    /**
     * @OA\Post(
     * path="/api/v1/geo/upload/",
     * tags={"Geo"},
     * @OA\Parameter(
     *      name="geom",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="osid",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="toid",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="height_max",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="symbol",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Response(response=200, description="Upload Geo", @OA\JsonContent()),
     * )
     */
    public function storeAsJSONFile(PhilGeoRequest $request)
    {
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'geometry' => $request->geom,
                    'id' => $request->osid,
                    'properties' => [
                        'RelHMax' => $request->height_max,
                        'TOID' => $request->toid,
                        '_symbol' => $request->symbol,
                    ],
                    'type' => 'Feature',
                ]
            ]
        ];

        Storage::disk('public')->put("{$request->toid}.geojson", json_encode($geojson));

        return ApiJsonResponse::sendOkResponse([
            'message' => 'File was successfully uploaded',
            'file' => asset(Storage::url("{$request->toid}.geojson"))
        ]);
    }
}
