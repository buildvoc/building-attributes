<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UprnTopo extends Model
{
    use HasFactory;

    protected $table = 'nhle_uprn_topo_joined';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'toid',
        'geom',
    ];

    protected $spatialFields = ['geom'];

    protected $hidden = [
        'id',
        'fid',
        'objectid',
        'UPRN',
        'name',
        'easting',
        'northing',
        'LATITUDE',
        'LONGITUDE',
        'distance',
        'easting_2',
        'northing_2',
        'distance_2',
        'geom',
    ];

    protected $casts = [
        'easting' => 'float',
        'northing' => 'float',
        'distance' => 'float',
        'easting_2' => 'float',
        'northing_2' => 'float',
        'distance_2' => 'float',
        'LATITUDE' => 'float',
        'LONGITUDE' => 'float',
    ];

    protected $appends = ['geojson'];

    public function newQuery()
    {
        return parent::newQuery()->select(
            'id',
            'fid',
            'objectid',
            'UPRN',
            'name',
            'easting',
            'northing',
            'LATITUDE',
            'LONGITUDE',
            'distance',
            'easting_2',
            'northing_2',
            'distance_2',
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
                                'name' => $this->name,
                                'latitude' => $this->LATITUDE,
                                'longitude' => $this->LONGITUDE,
                                'distance' => $this->distance,
                                'easting' => $this->easting,
                                'northing' => $this->northing,
                                'distance_2' => $this->distance_2,
                                'easting_2' => $this->easting_2,
                                'northing_2' => $this->northing_2,
                            ],
                            'type' => 'Feature',
                        ]
                    ]
                ];
            }
        );
    }
}
