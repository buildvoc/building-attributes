<?php

namespace App\Http\Controllers\API;

use App\Models\ApiJsonResponse;
use App\Models\BuildingPart;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuildingPartController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/building-part/",
     * tags={"Building Part"},
     * @OA\Response(response=200, description="List of building part", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        $data = BuildingPart::query()->paginate(20);
        return ApiJsonResponse::sendOkResponse(['building_part' => $data]);
    }

    /**
     * @OA\Get(
     * path="/api/v1/building-part/nearest",
     * tags={"Building Part"},
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
     *      name="distance",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Parameter(
     *      name="imagedirection",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number",
     *           format="double"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Get nearest building part",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function nearest(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $distance = $request->distance ?: 10;
        $imagedirection = $request->imagedirection ?: 9;

        $raw = DB::select( DB::raw('with location as (SELECT st_transform(ST_MakeLine(
    ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326)::geometry,
    ST_SetSRID(ST_Project(ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326)::geometry, :distance, radians(:imagedirection)), 4326)::geometry
    ), 3857) AS geom)
    SELECT st_transform(t1.geometry, 3857) AS geometry_transformed, public.ST_AsGeoJSON(st_transform(t1.geometry, 4326)) AS geometry_json, t1.*
    FROM bld_fts_buildingpart t1, location t2
    WHERE st_intersects(t2.geom, st_transform(t1.geometry, 3857))
    ORDER BY st_transform(t1.geometry, 3857) <-> t2.geom
    LIMIT 1'), array(
            'longitude' => $longitude,
            'latitude' => $latitude,
            'distance' => $distance,
            'imagedirection' => $imagedirection
        )
    );

        $data = BuildingPart::hydrate($raw);

        return ApiJsonResponse::sendOkResponse(['building_part' => $data]);
    }
}
