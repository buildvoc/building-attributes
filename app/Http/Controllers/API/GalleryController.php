<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ApiJsonResponse;
use App\Models\Gallery;

class GalleryController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/galleries/",
     * tags={"Gallery"},
     * @OA\Parameter(
     *      name="name",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="slug",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="created_at",
     *      in="query",
     *      @OA\Schema(
     *           type="string",
     *           format="date"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Get all galleries",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function index(Request $request)
    {
        $name = $request->name;
        $slug = $request->slug;
        $created_at = $request->created_at;

        $data = Gallery::query()
        ->when($created_at, function ($query) use ($created_at) {
            $query->whereDate('created_at', $created_at);
        })
        ->when($slug, function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
        ->when($name, function ($query) use ($name) {
            $query->where('name', 'like', '%'.$name.'%');
        })
        ->latest()
        ->paginate(20);

        return ApiJsonResponse::sendOkResponse(['galleries' => $data]);
    }
}
