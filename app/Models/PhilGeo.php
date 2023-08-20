<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhilGeo extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    protected $fillable = [
        'osid',
        'toid',
        'height_max',
        'symbol',
        'geom',
    ];

    protected $spatialFields = ['geom'];

    protected $hidden = [
        'osid',
        'toid',
        'height_max',
        'symbol',
        'geom',
    ];

    protected $casts = [
        'height_max' => 'float',
        'symbol' => 'integer',
    ];

    protected $appends = ['geojson'];

    public function newQuery()
    {
        return parent::newQuery()->select(
            'id',
            'osid',
            'toid',
            'height_max',
            'symbol',
            'created_at',
            'updated_at',
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
                            'id' => $this->osid,
                            'properties' => [
                                'RelHMax' => $this->height_max,
                                'TOID' => $this->toid,
                                '_symbol' => $this->symbol,
                            ],
                            'type' => 'Feature',
                        ]
                    ]
                ];
            }
        );
    }
}
