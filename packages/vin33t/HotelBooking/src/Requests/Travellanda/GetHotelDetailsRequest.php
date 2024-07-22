<?php

namespace vin33t\HotelBooking\Requests\Travellanda;

class GetHotelDetailsRequest
{
    public static function create(array $data)
    {
        $xml = new \SimpleXMLElement('<Body></Body>');
        if (isset($data['HotelIds'])) {
            $hotelIds = $xml->addChild('HotelIds');
            foreach ($data['HotelIds'] as $hotelId) {
                $hotelIds->addChild('HotelId', htmlspecialchars($hotelId));
            }
        }
        return $xml;
    }
}
