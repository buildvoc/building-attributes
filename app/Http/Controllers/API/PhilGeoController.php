<?php

namespace App\Http\Controllers\API;

use App\Models\PhilGeo;
use App\Models\ApiJsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PhilGeoRequest;
use Illuminate\Support\Facades\Storage;

class PhilGeoController extends Controller
{
    public function index()
    {
        $data = PhilGeo::all();

        return ApiJsonResponse::sendOkResponse(['phil_geos' => $data]);
    }

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
