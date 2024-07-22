<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = [
        'name',
        'alias',
        'iata',
        'icao',
        'callsign',
        'country',
        'active',
    ];
}
