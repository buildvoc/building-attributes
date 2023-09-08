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
        // with t as (SELECT st_transform( st_setsrid( ST_MakePoint("easting","northing"),27700),4326) geom ,* from osopentoid)
        // SELECT ST_X (ST_Transform (geom, 4326)) AS long, ST_Y (ST_Transform (geom, 4326)) AS lat,* from t
        // LEFT JOIN sx_data sd on toid = sd.os_topo_toid
        // WHERE ST_DWithin(geom, ST_SetSRID(ST_Point(-0.7995479,51.2139666), 4326), 10)

        return ApiJsonResponse::sendOkResponse(['sx_data' => SxData::all()]);
    }
}
