<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiJsonResponse;
use App\Models\Image;

class ImageController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/images/",
     * tags={"Image"},
     * @OA\Response(response=200, description="Get all images", @OA\JsonContent()),
     * )
     */
    public function index()
    {
        return ApiJsonResponse::sendOkResponse(['images' => Image::all()]);
    }
}
