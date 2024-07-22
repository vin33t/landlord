<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FlightController extends Controller
{
    public function index()
    {
        return view('agency.flight.index');
    }

    public function booked()
    {
        return view('agency.flight.booked');
    }
}
