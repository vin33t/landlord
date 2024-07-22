<?php

namespace vin33t\HotelBooking\Services;

use Illuminate\Support\Facades\Config;
use vin33t\HotelBooking\Contracts\BookingInterface;
use GuzzleHttp\Client;
use vin33t\HotelBooking\Requests\HotelSearchRequest;
use vin33t\HotelBooking\Requests\Travellanda\GetCitiesRequest;
use vin33t\HotelBooking\Requests\Travellanda\GetHotelDetailsRequest;
use vin33t\HotelBooking\Requests\Travellanda\GetHotelsRequest;

class TravellandaService extends BaseService implements BookingInterface
{
    protected $username;
    protected $password;
    protected $endpoint;

    public function __construct()
    {
        $this->username = Config::get('hotelbooking.travellanda.username');
        $this->password = Config::get('hotelbooking.travellanda.password');
        $this->endpoint = Config::get('hotelbooking.travellanda.endpoint');
    }

    public function search(string $location, string $checkIn, string $checkOut, array $guestsArray)
    {
        $requestType = 'Search';

        // Prepare the XML request
        $xmlRequest = $this->prepareXmlRequest($this->username, $this->password, $requestType, [
            'location' => $location,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guestsArray' => $guestsArray,
        ]);
        return [
            [
                'hotel_name' => 'Hotel A',
                'location' => $location,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'price' => 100.00,
                'api' => 'travellanda'
            ],
            ];
    }

    public function book(array $offerDetails)
    {
        // Implement Travellanda API booking logic here
        // Return a unified response format
    }

    public function getCountries()
    {
        $xmlRequest = $this->prepareXmlRequest('GetCountries');
        $response = $this->sendRequest($this->endpoint, $xmlRequest);
        $countries = [];
        foreach ($response['Body']['Countries']['Country'] as $country) {
            $countries[] = [
                'country_code' => $country['CountryCode'],
                'country_name' => $country['CountryName'],
            ];
        }
        return $countries;
    }

    public function getCities(string $countryCode = null)
    {
        $xmlRequest = $this->prepareXmlRequest( 'GetCities', [
            'CountryCode' => $countryCode,
        ]);
        $response = $this->sendRequest($this->endpoint, $xmlRequest);
        $cities = [];
        if($response['Body']['CitiesReturned'] == 0){
            return $cities;
        }
        if($response['Body']['CitiesReturned'] == 1){
            $cities[] = [
                'city_id' => $response['Body']['Countries']['Country']['Cities']['City']['CityId'],
                'city_name' => $response['Body']['Countries']['Country']['Cities']['City']['CityName'],
            ];
        } else {
            foreach ($response['Body']['Countries']['Country']['Cities']['City'] as $city) {
                $cities[] = [
                    'city_id' => $city['CityId'],
                    'city_name' => $city['CityName'],
                ];
            }
        }

        return $cities;
    }

    public function getHotels(string $countryCode = null, int $cityId = null)
    {
        // Prepare the XML request
        $xmlRequest = $this->prepareXmlRequest($this->username, $this->password, 'GetHotels', [
            'CountryCode' => $countryCode,
            'CityId' => $cityId,
        ]);

        // Make the API call to Travellanda API
        $response = $this->sendRequest($this->endpoint, $xmlRequest);

        // Extract hotels from the response
        $hotels = [];
        foreach ($response['Body']['Hotels']['Hotel'] as $hotel) {
            $hotels[] = [
                'hotel_id' => $hotel['HotelId'],
                'city_id' => $hotel['CityId'],
                'hotel_name' => $hotel['HotelName'],
            ];
        }

        return $hotels;
    }

    public function getHotelDetails(array $hotelIds)
    {
        // Prepare the XML request
        $xmlRequest = $this->prepareXmlRequest($this->username, $this->password, 'GetHotelDetails', [
            'HotelIds' => [
                'HotelId' => $hotelIds
            ],
        ]);
        // Make the API call to Travellanda API
        $response = $this->sendRequest($this->endpoint, $xmlRequest);
        // Extract hotel details from the response
        $hotels = [];
        if(count($hotelIds) > 1){
            foreach ($response['Body']['Hotels']['Hotel'] as $hotel) {
                $hotels[] = $this->hotelToArray($hotel);
            }
        }else {
            $hotels[] = $this->hotelToArray($response['Body']['Hotels']['Hotel']);
        }

        return $hotels;
    }

    public function hotelToArray($hotel): array
    {
        return [
            'hotel_id' => $hotel['HotelId'],
            'city_id' => $hotel['CityId'],
            'hotel_name' => $hotel['HotelName'],
            'star_rating' => $hotel['StarRating'],
            'latitude' => $hotel['Latitude'],
            'longitude' => $hotel['Longitude'],
            'address' => $hotel['Address'],
            'location' => $hotel['Location'],
            'phone_number' => $hotel['PhoneNumber'],
            'description' => $hotel['Description'],
            'facilities' => $hotel['Facilities']['Facility'],
            'images' => $hotel['Images']['Image'],
        ];
    }


    public function hotelSearch(array $cityIds = [], array $hotelIds = [], string $checkInDate, string $checkOutDate, array $rooms, string $nationality, string $currency = 'GBP', int $availableOnly = 0)
    {
        $xmlRequest = $this->prepareXmlRequest('HotelSearch', [
            'CityIds' => $cityIds,
            'CheckInDate' => $checkInDate,
            'CheckOutDate' => $checkOutDate,
            'Rooms' => $rooms,
            'Nationality' => $nationality,
            'Currency' => $currency,
            'AvailableOnly' => $availableOnly,
        ]);

        $response = $this->sendRequest($this->endpoint, $xmlRequest);
        $hotels = [];
        // check if response body contains Error node the return exception with error message
        if (isset($response['Body']['Error'])) {
            throw new \Exception($response['Body']['Error']['ErrorText']);
        }
        if (isset($response['Body']['Hotels']['Hotel'])) {
            $hotelData = $response['Body']['Hotels']['Hotel'];

            if (!is_array($hotelData) || !isset($hotelData[0])) {
                $hotelData = [$hotelData]; // Ensure it is an array
            }

            foreach ($hotelData as $hotel) {
                $rooms = [];
                if (isset($hotel['Options']['Option'])) {
                    $options = $hotel['Options']['Option'];

                    if (!is_array($options) || !isset($options[0])) {
                        $options = [$options]; // Ensure it is an array
                    }

                    foreach ($options as $option) {
                        $rooms = [];
                        if (isset($option['Rooms']['Room'])) {
                            $roomData = $option['Rooms']['Room'];

                            if (!is_array($roomData) || !isset($roomData[0])) {
                                $roomData = [$roomData]; // Ensure it is an array
                            }

                            foreach ($roomData as $room) {
                                $rooms[] = [
                                    'room_id' => $room['RoomId'],
                                    'room_name' => $room['RoomName'],
                                    'num_adults' => $room['NumAdults'],
                                    'num_children' => $room['NumChildren'] ?? 0,
                                    'room_price' => $room['RoomPrice'],
                                ];
                            }
                        }

                        $hotels[] = [
                            'hotel_id' => $hotel['HotelId'],
                            'hotel_name' => $hotel['HotelName'],
                            'star_rating' => $hotel['StarRating'],
                            'rooms' => $rooms,
                        ];
                    }
                }
            }
        }
        return $hotels;
    }

    public function prepareXmlRequest(string $requestType,array $body = [])
    {
        $xml = new \SimpleXMLElement('<Request></Request>');
        $head = $xml->addChild('Head');
        $head->addChild('Username', $this->username);
        $head->addChild('Password', $this->password);
        $head->addChild('RequestType', $requestType);
        if(!in_array($requestType, ['GetCountries','GetCities', 'GetHotels', 'GetHotelDetails', 'HotelSearch'])){
            throw new \Exception("Unknown request type: $requestType" . '. Valid request types are: GetCities, GetHotels, GetHotelDetails, HotelSearch');
        }
        $request = '\vin33t\HotelBooking\Requests\Travellanda\\'.$requestType.'Request';
        $bodyXml = $request::create($body);
        $this->addXmlBody($xml, $bodyXml);

        return $xml->asXML();
    }

    private function addXmlBody(\SimpleXMLElement $xml, \SimpleXMLElement $bodyXml = null)
    {
        $bodyNode = $xml->addChild('Body');
        if (!$bodyXml) {
            return;
        }
        foreach ($bodyXml->children() as $child) {
            $this->addChildNode($bodyNode, $child);
        }
    }

    private function addChildNode(\SimpleXMLElement $parent, \SimpleXMLElement $child)
    {
        $newChild = $parent->addChild($child->getName(), (string) $child);
        foreach ($child->children() as $subChild) {
            $this->addChildNode($newChild, $subChild);
        }
    }
}
