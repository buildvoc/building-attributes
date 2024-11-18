<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UprnOpen extends Model
{
    use HasFactory;

    protected $table = 'osopenuprn_test';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'FID',
        'UPRN',
        'geom',
    ];

    protected $spatialFields = ['geom'];

    protected $hidden = [
        'id',
        'FID',
        'UPRN',
        'X_COORDINATE',
        'Y_COORDINATE',
        'LATITUDE',
        'LONGITUDE',
        'geom',
    ];

    protected $casts = [
        'X_COORDINATE' => 'float',
        'Y_COORDINATE' => 'float',
        'LATITUDE' => 'float',
        'LONGITUDE' => 'float',
    ];

    protected $appends = ['geojson'];

    public function newQuery()
    {
        return parent::newQuery()->select(
            'id',
            'FID',
            'UPRN',
            'X_COORDINATE',
            'Y_COORDINATE',
            'LATITUDE',
            'LONGITUDE',
            DB::raw('public.ST_AsGeoJSON(geom) as geom')
        );
    }

    public function geojson(): Attribute
    {
        return Attribute::make(
            get: function () {
                return [
                    'type' => 'FeatureCollection',
                    'features' => [
                        [
                            'geometry' => json_decode($this->geom),
                            'id' => $this->id,
                            'properties' => [
                                'uprn' => $this->UPRN,
                                'fid' => $this->FID,
                                'latitude' => $this->LATITUDE,
                                'longitude' => $this->LONGITUDE,
                                'x_coordinate' => $this->X_COORDINATE,
                                'y_coordinate' => $this->Y_COORDINATE,
                            ],
                            'type' => 'Feature',
                        ]
                    ]
                ];
            }
        );
    }
}
