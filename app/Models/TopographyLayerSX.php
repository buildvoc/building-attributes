<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopographyLayerSX extends Model
{
    use HasFactory;

    protected $table = 'topography_layer_sx';
    protected $connection = 'pgsql';
    protected $fillable = [
        'toid',
        'version_nu',
        'version_da',
        'geom',
    ];
    protected $hidden = [
        'toid',
        'version_nu',
        'version_da',
        'geom',
    ];
    protected $spatialFields = ['geom'];
    protected $appends = ['geojson'];

    public function newQuery()
    {
        return parent::newQuery()->select(
            'id',
            'gid',
            'toid',
            'version_nu',
            'version_da',
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
                                'TOID' => $this->toid,
                                'version_nu' => $this->version_nu,
                                'version_da' => $this->version_da,
                            ],
                            'type' => 'Feature',
                        ]
                    ]
                ];
            }
        );
    }

    public function sx_data()
    {
        return $this->hasOne(SxData::class, 'os_topo_toid', 'toid');
    }
}
