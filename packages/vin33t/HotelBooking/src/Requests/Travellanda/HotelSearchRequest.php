<?php

namespace vin33t\HotelBooking\Requests\Travellanda;

class HotelSearchRequest
{
    public static function create(array $data)
    {
        $xml = new \SimpleXMLElement('<Body></Body>');

        if (isset($data['CityIds'])) {
            $cityIds = $xml->addChild('CityIds');
            foreach ($data['CityIds'] as $cityId) {
                $cityIds->addChild('CityId', htmlspecialchars($cityId));
            }
        }

        if (isset($data['HotelIds'])) {
            $hotelIds = $xml->addChild('HotelIds');
            foreach ($data['HotelIds'] as $hotelId) {
                $hotelIds->addChild('HotelId', htmlspecialchars($hotelId));
            }
        }

        $xml->addChild('CheckInDate', htmlspecialchars($data['CheckInDate']));
        $xml->addChild('CheckOutDate', htmlspecialchars($data['CheckOutDate']));

        if (isset($data['Rooms'])) {
            $rooms = $xml->addChild('Rooms');
            foreach ($data['Rooms'] as $room) {
                $roomNode = $rooms->addChild('Room');
                $roomNode->addChild('NumAdults', htmlspecialchars($room['NumAdults']));
                if (isset($room['Children']['ChildAge'])) {
                    $children = $roomNode->addChild('Children');
                    foreach ($room['Children']['ChildAge'] as $childAge) {
                        $children->addChild('ChildAge', htmlspecialchars($childAge));
                    }
                }
            }
        }

        $xml->addChild('Nationality', htmlspecialchars($data['Nationality']));
        $xml->addChild('Currency', htmlspecialchars($data['Currency']));
        $xml->addChild('AvailableOnly', htmlspecialchars($data['AvailableOnly']));

        return $xml;
    }
}
