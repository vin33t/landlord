<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'name',
        'city',
        'country',
        'iata',
        'icao',
        'latitude',
        'longitude',
        'altitude',
        'timezone',
        'dst',
        'tz_database_timezone',
        'type',
        'source'
    ];
}
