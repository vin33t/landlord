<?php

namespace vin33t\Travelport;


use Illuminate\Support\Facades\Facade;

class TravelportFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Travelport::class;
    }
}
