<?php

namespace vin33t\HotelBooking\Requests\Travellanda;

class GetCitiesRequest
{
    public static function create(array $data)
    {
        $xml = new \SimpleXMLElement('<Body></Body>');
        if (isset($data['CountryCode'])) {
            $xml->addChild('CountryCode', htmlspecialchars($data['CountryCode']));
        }
        return $xml;
    }
}
