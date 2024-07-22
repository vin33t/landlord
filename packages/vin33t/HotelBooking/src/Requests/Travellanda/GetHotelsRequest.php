<?php


namespace vin33t\HotelBooking\Requests\Travellanda;

class GetHotelsRequest
{
    public static function create(array $data)
    {
        $xml = new \SimpleXMLElement('<Body></Body>');
        if (isset($data['CountryCode'])) {
            $xml->addChild('CountryCode', htmlspecialchars($data['CountryCode']));
        }
        if (isset($data['CityId'])) {
            $xml->addChild('CityId', htmlspecialchars($data['CityId']));
        }
        return $xml;
    }
}
