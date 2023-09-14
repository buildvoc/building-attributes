<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class HomeController extends Controller
{
    public function index()
    {
        $files = Storage::allFiles("public/data");
        $extensions = ['jpg', 'JPG', 'png', 'jpeg', 'JPEG', 'PNG', 'GIF', 'gif'];

        $geojson = array_map(function ($file) {
            $file_d = basename($file);
            if (str_ends_with($file_d, "geojson")) {
                return $file;
            }
        }, $files);

        $images = array_map(function ($file) use ($extensions) {
            $file_d = basename($file);
            foreach ($extensions as $ext) {
                if (str_ends_with($file_d, $ext)) {
                    return $file_d;
                }
            }
        }, $files);

        $images = array_filter($images, fn ($file) => $file !== null);

        $geojson = array_filter($geojson, fn ($file) => $file !== null);

        $geojson_data = array();

        foreach ($geojson as $filename) {
            $file = Storage::get($filename);
            array_push($geojson_data, $file);
        }

        $imgcontentarray = array();
        foreach ($images as $filename) {
            $exif = @exif_read_data(storage_path("app/public/data/") . $filename, 0, true);
            array_push($imgcontentarray, $exif);
        }

        return view('welcome', ['geojson_data' => $geojson_data, 'imgcontentarray' => $imgcontentarray]);
    }

    public function okans()
    {
        return view('okans');
    }
}
