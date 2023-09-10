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
        $radius = $request->radius ?: 10;

        $raw = DB::select( DB::raw('with t as
        (select st_buffer
        (st_transform(
        (ST_SetSRID
        (st_makepoint(:longitude,:latitude),4326)),3857),
        :radius -- this is buffer meter
        ) geom),
        c as (
        select tls.gid, tls.toid, tls.version_nu, tls.version_da, public.ST_AsGeoJSON(tls.geom) as geom_json from topography_layer_sx tls,t
        where st_intersects(tls.geom, st_transform((t.geom),4326)))
        select * from c
        inner join sx_data sd on sd.os_topo_toid = c.toid
        limit 1'), array(
            'longitude' => $longitude, 'latitude' => $latitude, 'radius' => $radius
        )
    );

        $data = TopographyLayerSX::hydrate($raw);

        // $data = TopographyLayerSX::with('sx_data')
        // // ->when($latitude, function ($query) use ($latitude, $longitude, $radius) {
        // //     $query->whereRaw("public.ST_DWithin(geom, ST_SetSRID(ST_Point($longitude,$latitude), 4326), $radius)");
        // // })
        // // ->join('sx_data', 'topography_layer_sx.toid', '=', 'sx_data.os_topo_toid')
        // ->where('toid', 'osgb1000002070300013')
        // ->limit(1)
        // ->get();

        return ApiJsonResponse::sendOkResponse(['layer_sx' => $data]);
    }
}
