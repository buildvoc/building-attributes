<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ApiJsonResponse;
use App\Models\Image;

class ImageController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/images/",
     * tags={"Image"},
     * @OA\Parameter(
     *      name="gallery_id",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="filename",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="description",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="long_description",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_latitude",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_longitude",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_altitude",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_focal_length",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_iso",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_taken_at",
     *      in="query",
     *      @OA\Schema(
     *          type="string",
     *          format="date"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_gps_img_direction",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_gps_longitude_ref",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="exif_data_gps_latitude_ref",
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
     *      description="Get all images",
     *      @OA\JsonContent()
     * ),
     * )
     */
    public function index(Request $request)
    {
        $gallery_id = $request->gallery_id;
        $filename = $request->filename;
        $description = $request->description;
        $long_description = $request->long_description;
        $exif_data_latitude = $request->exif_data_latitude;
        $exif_data_longitude = $request->exif_data_longitude;
        $exif_data_altitude = $request->exif_data_altitude;
        $exif_data_focal_length = $request->exif_data_focal_length;
        $exif_data_iso = $request->exif_data_iso;
        $exif_data_taken_at = $request->exif_data_taken_at;
        $exif_data_gps_img_direction = $request->exif_data_gps_img_direction;
        $exif_data_gps_longitude_ref = $request->exif_data_gps_longitude_ref;
        $exif_data_gps_latitude_ref = $request->exif_data_gps_latitude_ref;
        $created_at = $request->created_at;

        $data = Image::query()
        ->when($created_at, function ($query) use ($created_at) {
            $query->whereDate('created_at', $created_at);
        })
        ->when($exif_data_taken_at, function ($query) use ($exif_data_taken_at) {
            $query->whereDate('exif_data_taken_at', $exif_data_taken_at);
        })
        ->when($gallery_id, function ($query) use ($gallery_id) {
            $query->where('gallery_id', $gallery_id);
        })
        ->when($exif_data_iso, function ($query) use ($exif_data_iso) {
            $query->where('exif_data_iso', $exif_data_iso);
        })
        ->when($exif_data_focal_length, function ($query) use ($exif_data_focal_length) {
            $query->where('exif_data_focal_length', $exif_data_focal_length);
        })
        ->when($exif_data_gps_img_direction, function ($query) use ($exif_data_gps_img_direction) {
            $query->where('exif_data_gps_img_direction', $exif_data_gps_img_direction);
        })
        ->when($exif_data_latitude, function ($query) use ($exif_data_latitude) {
            $query->where('exif_data_latitude', $exif_data_latitude);
        })
        ->when($exif_data_longitude, function ($query) use ($exif_data_longitude) {
            $query->where('exif_data_longitude', $exif_data_longitude);
        })
        ->when($exif_data_altitude, function ($query) use ($exif_data_altitude) {
            $query->where('exif_data_altitude', $exif_data_altitude);
        })
        ->when($exif_data_gps_longitude_ref, function ($query) use ($exif_data_gps_longitude_ref) {
            $query->where('exif_data_gps_longitude_ref', $exif_data_gps_longitude_ref);
        })
        ->when($exif_data_gps_latitude_ref, function ($query) use ($exif_data_gps_latitude_ref) {
            $query->where('exif_data_gps_latitude_ref', $exif_data_gps_latitude_ref);
        })
        ->when($filename, function ($query) use ($filename) {
            $query->where('filename', 'like', '%'.$filename.'%');
        })
        ->when($description, function ($query) use ($description) {
            $query->where('description', 'like', '%'.$description.'%');
        })
        ->when($long_description, function ($query) use ($long_description) {
            $query->where('long_description', 'like', '%'.$long_description.'%');
        })
        ->latest()
        ->paginate(20);


        return ApiJsonResponse::sendOkResponse(['images' => $data]);
    }
}
