<?php

namespace App\Http\Controllers;

use vin33t\Travelport\Travelport;

class FlightController extends Controller
{
    public function index()
    {
        $travelport = app(Travelport::class);

    }

    
}
