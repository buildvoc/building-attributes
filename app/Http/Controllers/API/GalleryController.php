<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiJsonResponse;
use App\Models\Gallery;

class GalleryController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/galleries/",
     * tags={"Gallery"},
     * @OA\Response(response=200, description="Get all images", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        return ApiJsonResponse::sendOkResponse(['galleries' => Gallery::all()]);
    }
}
