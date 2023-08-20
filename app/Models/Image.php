<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'image';
    protected $connection = 'mysql';

    protected $fillable = [
        'gallery_id',
        'filename',
        'resized_filename',
        'thumbnail_filename',
        'description',
        'long_description',
        'exif_data_latitude',
        'exif_data_longitude',
        'exif_data_altitude',
        'exif_data_make',
        'exif_data_model',
        'exif_data_exposure',
        'exif_data_aperture',
        'exif_data_focal_length',
        'exif_data_iso',
        'exif_data_taken_at',
        'exif_data_gps_img_direction',
        'weather_description',
        'weather_temperature',
        'weather_humidity',
        'weather_pressure',
        'weather_wind_speed',
        'exif_data_gps_longitude_ref',
        'exif_data_gps_latitude_ref',
        'exif_data_focal_length_in35mm_film',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }
}
