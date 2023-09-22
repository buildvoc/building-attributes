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

        $raw = DB::select( DB::raw('with location as (select st_transform(
            (ST_SetSRID
            (st_makepoint( :longitude, :latitude),4326)),3857)geom)
            select st_transform(t1.geometry,3857) as geometry_transformed, public.ST_AsGeoJSON(st_transform(t1.geometry,4326)) as geometry_json,
            t1.* from bld_fts_buildingpart t1,location t2
            where st_intersects(st_buffer(t2.geom,10),st_transform(t1.geometry,3857)) order by
            st_transform(t1.geometry,3857)<-> t2.geom
            limit 1'), array(
            'longitude' => $longitude, 'latitude' => $latitude
        )
    );

        $data = BuildingPart::hydrate($raw);

        return ApiJsonResponse::sendOkResponse(['building_part' => $data]);
    }
}
