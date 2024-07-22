<?php

namespace vin33t\Travelport;


use Carbon\Carbon;
use FluentDOM\Serializer\Json\RabbitFish;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

trait FlightDataTrait
{
    function parseXMLOneWay($response)
    {
        //parse the Search response to get values to use in detail request
        $data = collect();
        $LowFareSearchRsp = $response; //use this if response is not saved anywhere else use above variable
        //echo $LowFareSearchRsp;
        // return $LowFareSearchRsp;
        $xml = simplexml_load_String("$LowFareSearchRsp", null, null, 'SOAP', true);
        // return $xml;
        if (!$xml) {
            trigger_error("Encoding Error!", E_USER_ERROR);
        }

        $Results = $xml->children('SOAP', true);
        foreach ($Results->children('SOAP', true) as $fault) {
            if (strcmp($fault->getName(), 'Fault') == 0) {
                // trigger_error("Error occurred request/response processing!", E_USER_ERROR);
                return $data;
            }
        }

        $count = 0;
        foreach ($Results->children('air', true) as $lowFare) {
            foreach ($lowFare->children('air', true) as $airPriceSol) {
                if (strcmp($airPriceSol->getName(), 'AirPricingSolution') == 0) {
                    foreach ($airPriceSol->children('air', true) as $journey) {
                        if (strcmp($journey->getName(), 'Journey') == 0) {
                            $Journey = collect();
                            $journeydetails = collect();
                            foreach ($journey->children('air', true) as $segmentRef) {
                                if (strcmp($segmentRef->getName(), 'AirSegmentRef') == 0) {
                                    $details = [];
                                    foreach ($segmentRef->attributes() as $a => $b) {
                                        $segment = $this->ListAirSegments($b, $lowFare);

                                        foreach ($segment->attributes() as $c => $d) {
                                            // $details[$c]=$d;
                                            if (strcmp($c, "Key") == 0) {
                                                $details["Key"] = $d;
                                            }
                                            if (strcmp($c, "Group") == 0) {
                                                $details["Group"] = $d;
                                            }
                                            if (strcmp($c, "Origin") == 0) {
                                                $details["From"] = $d;
                                            }
                                            if (strcmp($c, "Destination") == 0) {
                                                $details["To"] = $d;
                                            }
                                            if (strcmp($c, "Carrier") == 0) {
                                                $details["Airline"] = $d;
                                            }
                                            if (strcmp($c, "FlightNumber") == 0) {
                                                $details["Flight"] = $d;
                                            }
                                            if (strcmp($c, "DepartureTime") == 0) {
                                                $details["Depart"] = $d;
                                            }
                                            if (strcmp($c, "ArrivalTime") == 0) {
                                                $details["Arrive"] = $d;
                                            }
                                            if (strcmp($c, "FlightTime") == 0) {
                                                $details["FlightTime"] = $d;
                                            }
                                            if (strcmp($c, "Distance") == 0) {
                                                $details["Distance"] = $d;
                                            }
                                            if (strcmp($c, "AvailabilitySource") == 0) {
                                                $details["AvailabilitySource"] = $d;
                                            }
                                            if (strcmp($c, "ChangeOfPlane") == 0) {
                                                $details["ChangeOfPlane"] = $d;
                                            }
                                            if (strcmp($c, "ETicketability") == 0) {
                                                $details["ETicketability"] = $d;
                                            }
                                            if (strcmp($c, "Equipment") == 0) {
                                                $details["Equipment"] = $d;
                                            }
                                            if (strcmp($c, "OptionalServicesIndicator") == 0) {
                                                $details["OptionalServicesIndicator"] = $d;
                                            }
                                            if (strcmp($c, "ParticipantLevel") == 0) {
                                                $details["ParticipantLevel"] = $d;
                                            }
                                        }
                                    }
                                    $journeydetails->push($details);
                                }
                            }
                            $Journey->push(['journey' => collect($journeydetails)]);
                        }
                    }


                    // Price Details
                    foreach ($airPriceSol->children('air', true) as $priceInfo) {
                        $flightPrice = [];
                        if (strcmp($priceInfo->getName(), 'AirPricingInfo') == 0) {
                            foreach ($priceInfo->attributes() as $e => $f) {
//                                dd($flightPrice);
                                if (strcmp($e, "ApproximateBasePrice") == 0) {
                                    $flightPrice['Approx Base Price'] = $f;
                                }
                                if (strcmp($e, "ApproximateTaxes") == 0) {
                                    $flightPrice['Approx Taxes'] = $f;
                                }
                                if (strcmp($e, "ApproximateTotalPrice") == 0) {
                                    $flightPrice['Approx Total Value'] = $f;
                                }
                                if (strcmp($e, "BasePrice") == 0) {
                                    $flightPrice['Base Price'] = $f;
                                }
                                if (strcmp($e, "Taxes") == 0) {
                                    $flightPrice['Taxes'] = $f;
                                }
                                if (strcmp($e, "TotalPrice") == 0) {
                                    $flightPrice['Total Price'] = $f;
                                }
                            }
                            foreach ($priceInfo->children('air', true) as $bookingInfo) {
                                if (strcmp($bookingInfo->getName(), 'BookingInfo') == 0) {
                                    foreach ($bookingInfo->attributes() as $e => $f) {
                                        if (strcmp($e, "CabinClass") == 0) {
                                            $flightPrice['Cabin Class'] = $f;
                                        }
                                    }
                                }
                                if (strcmp($bookingInfo->getName(), 'CancelPenalty') == 0) {
                                    $flightPrice['Cancel Penalty'] = $bookingInfo;
                                }
                            }
                        }
                        if (count($flightPrice)) {
                            $Journey->push(['price' => $flightPrice]);
                            // $flight['price'] = collect($flightPrice);
                        }
                    }
                    $data->push(['flight' => collect($Journey)]);
                    // file_put_contents($fileName,"\r\n", FILE_APPEND);
                }
            }
        }

        return $data;


    }
    function ListAirSegments($key, $lowFare)
    {
        foreach ($lowFare->children('air', true) as $airSegmentList) {
            if (strcmp($airSegmentList->getName(), 'AirSegmentList') == 0) {
                foreach ($airSegmentList->children('air', true) as $airSegment) {
                    if (strcmp($airSegment->getName(), 'AirSegment') == 0) {
                        foreach ($airSegment->attributes() as $a => $b) {
                            if (strcmp($a, 'Key') == 0) {
                                if (strcmp($b, $key) == 0) {
                                    return $airSegment;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    function formatOneWayFlightData($flightData, $flightSearch)
    {
        $flights = collect();
        foreach ($flightData as $flight) {
            foreach ($flight as $flight_data) {
                $dataType = ['pricePerAdult', 'pricePerChild', 'pricePerInfant'];
                $details = collect();
                $prices = collect();
                $totalPrice = 0;
                foreach ($dataType as $key => $type) {
                    foreach ($flight_data[$key + 1] as $data) {
                        $totalPrice += ((int)str_replace($flightSearch['currency'], '', $this->convertObjectString($data['Total Price']))) * (int)($type == 'pricePerAdult' ? $flightSearch['adult'] : ($type == 'pricePerChild' ? $flightSearch['child'] : $flightSearch['infant']));
                        $temp = [$type => [
                            'totalForSearchedPassengers' => ((int)str_replace($flightSearch['currency'], '', $this->convertObjectString($data['Total Price']))) * (int)($type == 'pricePerAdult' ? $flightSearch['adult'] : ($type == 'pricePerChild' ? $flightSearch['child'] : $flightSearch['infant'])),
                            'totalPrice' => $this->convertObjectString($data['Total Price']),
                            'basePrice' => $this->convertObjectString($data['Base Price']),
                            'approxTotalValue' => $this->convertObjectString($data['Approx Total Value']),
                            'approxBasePrice' => $this->convertObjectString($data['Approx Base Price']),
                            'taxes' => $this->convertObjectString($data['Taxes']),
                            'approxTaxes' => $this->convertObjectString($data['Approx Taxes']),
                            'cabinClass' => $this->convertObjectString($data['Cabin Class']),
//                                'cancelPenalty' => $data['Cancel Penalty'],
                        ]];
                        $prices->push($temp);
                    }
                }
                foreach ($flight_data[0] as $segments) {
                    $journeys = collect();
                    foreach ($segments as $segment) {
                        $temp = [
                            'airline' => $this->convertObjectString($segment['Airline']),
                            'flight' => $this->convertObjectString($segment['Flight']),
                            'origin' => $this->convertObjectString($segment['From']),
                            'destination' => $this->convertObjectString($segment['To']),
                            'departure' => $this->convertObjectString($segment['Depart']),
                            'departureFormatted' => Carbon::parse($this->convertObjectString($segment['Depart']))->format('d F,Y h:i A'),
                            'arrivalFormatted' => Carbon::parse($this->convertObjectString($segment['Arrive']))->format('d F,Y h:i A'),
                            'arrival' => $this->convertObjectString($segment['Arrive']),
                            'flightTime' => $this->convertObjectString($segment['FlightTime']),
                            'flightTimeFormatted' => $this->hoursandmins($this->convertObjectString($segment['FlightTime'])),
                            'distance' => $this->convertObjectString($segment['Distance']),
                            'availabilitySource' => $this->convertObjectString($segment['AvailabilitySource']),
                            'changeOfPlane' => $this->convertObjectString($segment['ChangeOfPlane']),
                            'eTicketability' => $this->convertObjectString($segment['ETicketability']),
                            'equipment' => $this->convertObjectString($segment['Equipment']),
                            'optionalServicesIndicator' => $this->convertObjectString($segment['OptionalServicesIndicator']),
                            'participantLevel' => $this->convertObjectString($segment['ParticipantLevel']),
                            'group' => $this->convertObjectString($segment['Group']),
                            'key' => $this->convertObjectString($segment['Key']),
                        ];
                        $journeys->push($temp);
                    }
                    $details->push([
                        'totalPrice' => $totalPrice,
                        'currency' => $flightSearch['currency'],
                        'origin' => $journeys->first()['origin'],
                        'airline' => $journeys->unique('airline')->first()['airline'],
                        'flight' => $journeys->first()['flight'],
                        'destination' => $journeys->last()['destination'],
                        'departureDateFormatted' => ['raw' => Carbon::parse($this->convertObjectString($journeys->first()['departure']))->format('Y-m-d'), 'formatted' => Carbon::parse($this->convertObjectString($journeys->first()['departure']))->format('D, d F, Y')],
                        'arrivalDateFormatted' => ['raw' => Carbon::parse($this->convertObjectString($journeys->last()['arrival']))->format('Y-m-d'), 'formatted' => Carbon::parse($this->convertObjectString($journeys->last()['arrival']))->format('D, d F, Y')],
                        'departureDate' => Carbon::parse($this->convertObjectString($journeys->first()['departure']))->format('d F,Y'),
                        'departureTime' => Carbon::parse($this->convertObjectString($journeys->first()['departure']))->format('h:i A'),
                        'arrivalDate' => Carbon::parse($this->convertObjectString($journeys->last()['arrival']))->format('d F,Y'),
                        'arrivalTime' => Carbon::parse($this->convertObjectString($journeys->last()['arrival']))->format('h:i A'),
                        'flightDuration' => $this->hoursandmins($journeys->first()['flightTime'] + ($journeys->count() > 1 ? $journeys->last()['flightTime'] : 0), '%02d Hours, %02d Minutes'),
                        'totalFlightTime' => $journeys->first()['flightTime'] + ($journeys->count() > 1 ? $journeys->last()['flightTime'] : 0),
                        'totalTimeTakenFromOriginToDestination'=>$this->hoursandmins($journeys->pluck('flightTime')->sum()),
                        'stops' => $journeys->count(),
                        'adult' => $flightSearch['adult'],
                        'children' => $flightSearch['child'],
                        'infant' => $flightSearch['infant'],
                        'type' => $flightSearch['type'],
                        'flightSegments' => $journeys,
                        'price' => $prices]);
                }

                $flights->push($details[0]);
            }

        }
        return $flights;
    }

    function hoursandmins($time, $format = '%02d hours %02d minutes')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    function convertObjectString($object)
    {
        return ((array)$object)[0];

    }

    public function XMlToJSON($return)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($return);
        $json = new RabbitFish($dom);
        return json_decode($json, true);
    }


    public function parseOneWayFlightDetails($object)
    {
        $data = collect();
        $journey = collect();
        $count = 1;
        foreach ($object as $jsons) {
            foreach ($jsons as $jsons1) {
                // print_r($jsons1);
                // echo "<br/><br/>";
                if (array_key_exists('SOAP:Fault', $jsons1)) {
                    // return "Error No data Found";
                    return $data;
                } else {
                    foreach ($jsons1 as $jsons2) {
                        if (is_array($jsons2)) {
                            // count($jsons1)>1
                            // print_r($jsons2);
                            // echo "<br/><br/>";
                            // print_r(array_key_exists('air:AirItinerary',$jsons2));
                            if (array_key_exists('air:AirItinerary', $jsons2)) {
                                // print_r($jsons2['air:AirItinerary']['air:AirSegment']);
                                // echo "<br/><br/>";
                                $details1 = [];
                                foreach ($jsons2['air:AirItinerary']['air:AirSegment'] as $g => $jsons5) {
                                    //  print_r($jsons5);
                                    //     echo "<br/>";
                                    if (is_string($jsons5)) {
                                        if (strcmp($g, "@Key") == 0) {
                                            $details1["key"] = $jsons5;
                                        }
                                        if (strcmp($g, "@Group") == 0) {
                                            $details1["Group"] = $jsons5;
                                        }
                                        if (strcmp($g, "@Carrier") == 0) {
                                            $details1["Carrier"] = $jsons5;
                                        }
                                        if (strcmp($g, "@FlightNumber") == 0) {
                                            $details1["FlightNumber"] = $jsons5;
                                        }
                                        if (strcmp($g, "@Origin") == 0) {
                                            $details1["Origin"] = $jsons5;
                                        }
                                        if (strcmp($g, "@Destination") == 0) {
                                            $details1["Destination"] = $jsons5;
                                        }
                                        if (strcmp($g, "@DepartureTime") == 0) {
                                            $details1["DepartureTime"] = $jsons5;
                                            $details["DepartureDateFormatted"] = Carbon::parse($jsons5)->format('D, d F,Y');
                                            $details["DepartureTimeFormatted"] = Carbon::parse($jsons5)->format('h:i A');
                                        }
                                        if (strcmp($g, "@ArrivalTime") == 0) {
                                            $details1["ArrivalTime"] = $jsons5;
                                            $details["ArrivalDateFormatted"] = Carbon::parse($jsons5)->format('D, d F,Y');
                                            $details["ArrivalTimeFormatted"] = Carbon::parse($jsons5)->format('h:i A');
                                        }
                                        if (strcmp($g, "@FlightTime") == 0) {
                                            $details1["FlightTime"] = $jsons5;
                                        }
                                        if (strcmp($g, "@TravelTime") == 0) {
                                            $details1["TravelTime"] = $jsons5;
                                        }
                                        if (strcmp($g, "@Distance") == 0) {
                                            $details1["Distance"] = $jsons5;
                                        }
                                        if (strcmp($g, "@ClassOfService") == 0) {
                                            $details1["ClassOfService"] = $jsons5;
                                        }
                                    } else {
                                        $details = [];
                                        foreach ($jsons5 as $k => $jsons6) {
                                            // print_r($jsons6);
                                            // echo "<br/>";
                                            if (is_string($jsons6)) {
                                                if (strcmp($k, "@Key") == 0) {
                                                    $details["key"] = $jsons6;
                                                }
                                                if (strcmp($k, "@Group") == 0) {
                                                    $details["Group"] = $jsons6;
                                                }
                                                if (strcmp($k, "@Carrier") == 0) {
                                                    $details["Carrier"] = $jsons6;
                                                }
                                                if (strcmp($k, "@FlightNumber") == 0) {
                                                    $details["FlightNumber"] = $jsons6;
                                                }
                                                if (strcmp($k, "@Origin") == 0) {
                                                    $details["Origin"] = $jsons6;
                                                }
                                                if (strcmp($k, "@Destination") == 0) {
                                                    $details["Destination"] = $jsons6;
                                                }
                                                if (strcmp($k, "@DepartureTime") == 0) {
                                                    $details["DepartureTime"] = $jsons6;
                                                    $details["DepartureDateFormatted"] = Carbon::parse($jsons6)->format('D, d F,Y');
                                                    $details["DepartureTimeFormatted"] = Carbon::parse($jsons6)->format('h:i A');
                                                }
                                                if (strcmp($k, "@ArrivalTime") == 0) {
                                                    $details["ArrivalTime"] = $jsons6;
                                                    $details["ArrivalDateFormatted"] = Carbon::parse($jsons6)->format('D, d F,Y');
                                                    $details["ArrivalTimeFormatted"] = Carbon::parse($jsons6)->format('h:i A');
                                                }
                                                if (strcmp($k, "@FlightTime") == 0) {
                                                    $details["FlightTime"] = $jsons6;
                                                }
                                                if (strcmp($k, "@TravelTime") == 0) {
                                                    $details["TravelTime"] = $jsons6;
                                                }
                                                if (strcmp($k, "@Distance") == 0) {
                                                    $details["Distance"] = $jsons6;
                                                }
                                                if (strcmp($k, "@ClassOfService") == 0) {
                                                    $details["ClassOfService"] = $jsons6;
                                                }
                                                // $details["changeofplane"] =$jsons6;
                                                // $details["optionalservicesindicator"]=$jsons6;
                                                // $details["availabilitysource"] =$jsons6;
                                                // $details["polledavailabilityoption"] =$jsons6;
                                                // print_r($jsons6);
                                                // echo "<br/>";
                                                // $journey->push($details);
                                                // print_r($k." - ".$jsons6);

                                            }
                                        }
                                        if (empty($details1) && !empty($details)) {
                                            $details['FlightAirTime'] = Carbon::parse($details['ArrivalTime'])->diff(Carbon::parse($details['DepartureTime']))->format('%H Hours %I Minutes');;
                                            $journey->push($details);
//                                            return $details;
                                        }
                                    }
                                }
                                if (!empty($details1)) {
                                    $details1['FlightAirTime'] = Carbon::parse($details1['ArrivalTime'])->diff(Carbon::parse($details1['DepartureTime']))->format('%H:%I:%S');;
                                    $journey->push($details1);
                                }
//                                 return $journey;
                                $data->push(["journey" => collect($journey)]);

                            }
                            if (array_key_exists('air:AirPriceResult', $jsons2)) {
                                // print_r($jsons2['air:AirPriceResult']);
                                // return $jsons2['air:AirPriceResult'];
                                // echo "<br/><br/>";
                                // print_r($jsons2['air:AirPriceResult']['air:AirPricingSolution']);
                                // return count($jsons2['air:AirPriceResult']['air:AirPricingSolution']);

                                // some error on indexing
                                // return $jsons2['air:AirPriceResult']['air:AirPricingSolution'];
                                // return $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0];
                                if (isset($jsons2['air:AirPriceResult']['air:AirPricingSolution'][0])) {

                                    $price = [];
                                    foreach ($jsons2['air:AirPriceResult']['air:AirPricingSolution'][0] as $p => $jsons15) {
                                        if (is_string($jsons15)) {
                                            if (strcmp($p, "@Key") == 0) {
                                                $price["Key"] = $jsons15;
                                            }
                                            if (strcmp($p, "@TotalPrice") == 0) {
                                                $price["TotalPrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@BasePrice") == 0) {
                                                $price["BasePrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@ApproximateTotalPrice") == 0) {
                                                $price["ApproximateTotalPrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@ApproximateBasePrice") == 0) {
                                                $price["ApproximateBasePrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@EquivalentBasePrice") == 0) {
                                                $price["EquivalentBasePrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@Taxes") == 0) {
                                                $price["Taxes"] = $jsons15;
                                            }
                                            if (strcmp($p, "@Fees") == 0) {
                                                $price["Fees"] = $jsons15;
                                            }
                                            if (strcmp($p, "@ApproximateTaxes") == 0) {
                                                $price["ApproximateTaxes"] = $jsons15;
                                            }
                                            if (strcmp($p, "@QuoteDate") == 0) {
                                                $price["QuoteDate"] = $jsons15;
                                            }
                                            if (strcmp($p, "@FareInfoRef") == 0) {
                                                $price["FareInfoRef"] = $jsons15;
                                            }
                                            if (strcmp($p, "@RuleNumber") == 0) {
                                                $price["RuleNumber"] = $jsons15;
                                            }
                                            if (strcmp($p, "@Source") == 0) {
                                                $price["Source"] = $jsons15;
                                            }
                                            if (strcmp($p, "@TariffNumber") == 0) {
                                                $price["TariffNumber"] = $jsons15;
                                            }
                                        }

                                    }
                                    // $data->push(["price"=>collect($price)]);
                                    $AirPricingInfo = collect();
                                    $FareInfo1 = collect();
                                    $FareRuleKey1 = collect();
                                    $BookingInfo1 = collect();
                                    $TaxInfo = collect();

                                    if (array_key_exists('air:AirPricingInfo', $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0])) {
                                        $jsons14 = $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['air:AirPricingInfo'];
                                        // return $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['air:AirPricingInfo'];
                                        $AirPricingInfo1 = [];
                                        foreach ($jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['air:AirPricingInfo'] as $key => $value) {
                                            $AirPricingInfo0 = [];
                                            if (is_string($value)) {
                                                if (strcmp($key, "@Key") == 0) {
                                                    $AirPricingInfo1["Key"] = $value;
                                                }
                                                if (strcmp($key, "@TotalPrice") == 0) {
                                                    $AirPricingInfo1["TotalPrice"] = $value;
                                                }
                                                if (strcmp($key, "@BasePrice") == 0) {
                                                    $AirPricingInfo1["BasePrice"] = $value;
                                                }
                                                if (strcmp($key, "@ApproximateTotalPrice") == 0) {
                                                    $AirPricingInfo1["ApproximateTotalPrice"] = $value;
                                                }
                                                if (strcmp($key, "@ApproximateBasePrice") == 0) {
                                                    $AirPricingInfo1["ApproximateBasePrice"] = $value;
                                                }
                                                if (strcmp($key, "@EquivalentBasePrice") == 0) {
                                                    $AirPricingInfo1["EquivalentBasePrice"] = $value;
                                                }
                                                if (strcmp($key, "@ApproximateTaxes") == 0) {
                                                    $AirPricingInfo1["ApproximateTaxes"] = $value;
                                                }
                                                if (strcmp($key, "@Taxes") == 0) {
                                                    $AirPricingInfo1["Taxes"] = $value;
                                                }
                                                if (strcmp($key, "@LatestTicketingTime") == 0) {
                                                    $AirPricingInfo1["LatestTicketingTime"] = $value;
                                                }
                                                if (strcmp($key, "@PricingMethod") == 0) {
                                                    $AirPricingInfo1["PricingMethod"] = $value;
                                                }
                                                if (strcmp($key, "@Refundable") == 0) {
                                                    $AirPricingInfo1["Refundable"] = $value;
                                                }
                                                if (strcmp($key, "@IncludesVAT") == 0) {
                                                    $AirPricingInfo1["IncludesVAT"] = $value;
                                                }
                                                if (strcmp($key, "@ETicketability") == 0) {
                                                    $AirPricingInfo1["ETicketability"] = $value;
                                                }
                                                if (strcmp($key, "@PlatingCarrier") == 0) {
                                                    $AirPricingInfo1["PlatingCarrier"] = $value;
                                                }
                                                if (strcmp($key, "@ProviderCode") == 0) {
                                                    $AirPricingInfo1["ProviderCode"] = $value;
                                                }
                                            } else {
                                                foreach ($value as $key => $value1) {
                                                    if (is_string($value1)) {
                                                        if (strcmp($key, "@Key") == 0) {
                                                            $AirPricingInfo0["Key"] = $value1;
                                                        }
                                                        if (strcmp($key, "@TotalPrice") == 0) {
                                                            $AirPricingInfo0["TotalPrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@BasePrice") == 0) {
                                                            $AirPricingInfo0["BasePrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ApproximateTotalPrice") == 0) {
                                                            $AirPricingInfo0["ApproximateTotalPrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ApproximateBasePrice") == 0) {
                                                            $AirPricingInfo0["ApproximateBasePrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@EquivalentBasePrice") == 0) {
                                                            $AirPricingInfo0["EquivalentBasePrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ApproximateTaxes") == 0) {
                                                            $AirPricingInfo0["ApproximateTaxes"] = $value1;
                                                        }
                                                        if (strcmp($key, "@Taxes") == 0) {
                                                            $AirPricingInfo0["Taxes"] = $value1;
                                                        }
                                                        if (strcmp($key, "@LatestTicketingTime") == 0) {
                                                            $AirPricingInfo0["LatestTicketingTime"] = $value1;
                                                        }
                                                        if (strcmp($key, "@PricingMethod") == 0) {
                                                            $AirPricingInfo0["PricingMethod"] = $value1;
                                                        }
                                                        if (strcmp($key, "@Refundable") == 0) {
                                                            $AirPricingInfo0["Refundable"] = $value1;
                                                        }
                                                        if (strcmp($key, "@IncludesVAT") == 0) {
                                                            $AirPricingInfo0["IncludesVAT"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ETicketability") == 0) {
                                                            $AirPricingInfo0["ETicketability"] = $value1;
                                                        }
                                                        if (strcmp($key, "@PlatingCarrier") == 0) {
                                                            $AirPricingInfo0["PlatingCarrier"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ProviderCode") == 0) {
                                                            $AirPricingInfo0["ProviderCode"] = $value1;
                                                        }
                                                    }
                                                }

                                                // start multiple travel add adult and child

                                                // return $value;
                                                // print_r($value);
                                                // print_r($value['air:FareInfo']);
                                                // echo "<br/><br/><br/>";

                                                // fareInfo and fareRule key
                                                if (array_key_exists('air:FareInfo', $value)) {
                                                    $FareInfo = [];
                                                    foreach ($value['air:FareInfo'] as $fI => $jsons17) {
                                                        $FareInfo0 = [];
                                                        // echo $count50;
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        // $FareRuleKey1=collect();
                                                        if (is_string($jsons17)) {
                                                            // print_r($fI."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($fI, "@Key") == 0) {
                                                                $FareInfo["Key"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@FareBasis") == 0) {
                                                                $FareInfo["FareBasis"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                                $FareInfo["PassengerTypeCode"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@Origin") == 0) {
                                                                $FareInfo["Origin"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@Destination") == 0) {
                                                                $FareInfo["Destination"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@EffectiveDate") == 0) {
                                                                $FareInfo["EffectiveDate"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@DepartureDate") == 0) {
                                                                $FareInfo["DepartureDate"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@Amount") == 0) {
                                                                $FareInfo["Amount"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                                $FareInfo["NegotiatedFare"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@NotValidBefore") == 0) {
                                                                $FareInfo["NotValidBefore"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@TaxAmount") == 0) {
                                                                $FareInfo["TaxAmount"] = $jsons17;
                                                            }
                                                        } else {
                                                            foreach ($jsons17 as $fI => $jsons18) {
                                                                if (is_string($jsons18)) {
                                                                    // print_r($fI."-".$jsons17);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($fI, "@Key") == 0) {
                                                                        $FareInfo0["Key"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@FareBasis") == 0) {
                                                                        $FareInfo0["FareBasis"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                                        $FareInfo0["PassengerTypeCode"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@Origin") == 0) {
                                                                        $FareInfo0["Origin"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@Destination") == 0) {
                                                                        $FareInfo0["Destination"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@EffectiveDate") == 0) {
                                                                        $FareInfo0["EffectiveDate"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@DepartureDate") == 0) {
                                                                        $FareInfo0["DepartureDate"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@Amount") == 0) {
                                                                        $FareInfo0["Amount"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                                        $FareInfo0["NegotiatedFare"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@NotValidBefore") == 0) {
                                                                        $FareInfo0["NotValidBefore"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@TaxAmount") == 0) {
                                                                        $FareInfo0["TaxAmount"] = $jsons18;
                                                                    }
                                                                }
                                                            }
                                                            // print_r($jsons17['air:FareRuleKey']);
                                                            // echo "<br/><br/>";
                                                            if (array_key_exists('air:FareRuleKey', $jsons17)) {
                                                                $FareRuleKey0 = [];
                                                                foreach ($jsons17['air:FareRuleKey'] as $frk => $jsons19) {
                                                                    if (is_string($jsons19)) {
                                                                        if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                            $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                        }
                                                                        if (strcmp($frk, "@ProviderCode") == 0) {
                                                                            $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                        }
                                                                        if (strcmp($frk, "$") == 0) {
                                                                            $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                        }
                                                                    }
                                                                }
                                                                $FareRuleKey1->push($FareRuleKey0);
                                                            }
                                                        }

                                                        if (empty($FareInfo) && !empty($FareInfo0)) {
                                                            $FareInfo1->push($FareInfo0);
                                                        }
                                                        // $FareRuleKey1=collect();
                                                        if (array_key_exists('air:FareRuleKey', $value['air:FareInfo'])) {
                                                            // print_r($value['air:FareInfo']['air:FareRuleKey']);
                                                            // echo "<br/><br/>";
                                                            $FareRuleKey = [];

                                                            foreach ($value['air:FareInfo']['air:FareRuleKey'] as $frk => $jsons18) {
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                $FareRuleKey0 = [];
                                                                if (is_string($jsons18)) {
                                                                    // print_r($frk." - ".$jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                        $FareRuleKey["FareInfoRef"] = $jsons18;
                                                                    }
                                                                    if (strcmp($frk, "@ProviderCode") == 0) {
                                                                        $FareRuleKey["ProviderCode"] = $jsons18;
                                                                    }
                                                                    if (strcmp($frk, "$") == 0) {
                                                                        $FareRuleKey["FareRuleKeyValue"] = $jsons18;
                                                                    }
                                                                } else {
                                                                    foreach ($jsons18 as $frk => $jsons19) {
                                                                        if (is_string($jsons19)) {
                                                                            // print_r($frk." - ".$jsons18);
                                                                            // echo "<br/><br/><br/>";
                                                                            if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                                $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                            }
                                                                            if (strcmp($frk, "@ProviderCode") == 0) {
                                                                                $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                            }
                                                                            if (strcmp($frk, "$") == 0) {
                                                                                $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                            }
                                                                        }
                                                                    }
                                                                    // if(empty($FareRuleKey) && !empty($FareRuleKey0)){
                                                                    //     $FareRuleKey1->push($FareRuleKey0);
                                                                    // }
                                                                }
                                                            }
                                                            // if(!empty($FareRuleKey)){
                                                            //     $FareRuleKey1->push($FareRuleKey);
                                                            // }
                                                        }
                                                    }
                                                    if (!empty($FareInfo)) {
                                                        $FareInfo1->push($FareInfo);
                                                    }
                                                    if (!empty($FareRuleKey)) {
                                                        $FareRuleKey1->push($FareRuleKey);
                                                    }
                                                }
                                                if (array_key_exists('air:BookingInfo', $value)) {
                                                    // $BookingInfo1=collect();
                                                    $BookingInfo = [];
                                                    foreach ($value['air:BookingInfo'] as $bki => $jsons17) {
                                                        $BookingInfo0 = [];
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if (is_string($jsons17)) {
                                                            // print_r($bki."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($bki, "@BookingCode") == 0) {
                                                                $BookingInfo["BookingCode"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@CabinClass") == 0) {
                                                                $BookingInfo["CabinClass"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@FareInfoRef") == 0) {
                                                                $BookingInfo["FareInfoRef"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@SegmentRef") == 0) {
                                                                $BookingInfo["SegmentRef"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@HostTokenRef") == 0) {
                                                                $BookingInfo["HostTokenRef"] = $jsons17;
                                                            }
                                                        } else {
                                                            foreach ($jsons17 as $bki => $jsons18) {
                                                                if (is_string($jsons18)) {
                                                                    // print_r($bki."-".$jsons17);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($bki, "@BookingCode") == 0) {
                                                                        $BookingInfo0["BookingCode"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@CabinClass") == 0) {
                                                                        $BookingInfo0["CabinClass"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@FareInfoRef") == 0) {
                                                                        $BookingInfo0["FareInfoRef"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@SegmentRef") == 0) {
                                                                        $BookingInfo0["SegmentRef"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@HostTokenRef") == 0) {
                                                                        $BookingInfo0["HostTokenRef"] = $jsons18;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if (empty($BookingInfo) && !empty($BookingInfo0)) {
                                                            $BookingInfo1->push($BookingInfo0);
                                                        }
                                                    }
                                                    // if(empty($BookingInfo) && !empty($BookingInfo0)){
                                                    //     $BookingInfo1->push($BookingInfo0);
                                                    // }
                                                    if (!empty($BookingInfo)) {
                                                        $BookingInfo1->push($BookingInfo);
                                                    }
                                                }
                                                if (array_key_exists('air:TaxInfo', $value)) {
                                                    // $TaxInfo=collect();
                                                    $TaxInfo1 = [];
                                                    if (is_object($value['air:TaxInfo'])) {
                                                        foreach ($value['air:TaxInfo'] as $jsons17) {
                                                            // print_r($jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            foreach ($jsons17 as $tki => $jsons18) {
                                                                if (isset($jsons17)) {
                                                                    if (is_string($jsons18)) {
                                                                        // print_r($tki."-".$jsons18);
                                                                        // echo "<br/><br/><br/>";
                                                                        if (strcmp($tki, "@Category") == 0) {
                                                                            $TaxInfo1["Category"] = $jsons18;
                                                                        }
                                                                        if (strcmp($tki, "@Amount") == 0) {
                                                                            $TaxInfo1["Amount"] = $jsons18;
                                                                        }
                                                                        if (strcmp($tki, "@Key") == 0) {
                                                                            $TaxInfo1["Key"] = $jsons18;
                                                                        }

                                                                    }
                                                                }
                                                            }
                                                            $TaxInfo->push($TaxInfo1);
                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:FareCalc', $value)) {
                                                    $FareCalc = [];
                                                    foreach ($value['air:FareCalc'] as $fcc => $jsons17) {
                                                        // print_r($jsons17);
                                                        if (is_string($jsons17)) {
                                                            if (strcmp($fcc, "$") == 0) {
                                                                $FareCalc["FareCalc"] = $jsons17;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:PassengerType', $value)) {
                                                    // print_r();
                                                    $PassengerType = [];
                                                    foreach ($value['air:PassengerType'] as $pc => $jsons17) {
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if (is_string($jsons17)) {
                                                            if (strcmp($pc, "@Code") == 0) {
                                                                $PassengerType["Code"] = $jsons17;
                                                            }
                                                        }
                                                    }
                                                }
                                                $details4 = [];
                                                if (array_key_exists('air:ChangePenalty', $value)) {
                                                    // $details4=[];
                                                    foreach ($value['air:ChangePenalty'] as $jsons17) {
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        foreach ($jsons17 as $c => $jsons18) {
                                                            if (is_string($jsons18)) {
                                                                if (strcmp($c, "$") == 0) {
                                                                    $details4["changepenalty"] = $jsons18;
                                                                }
                                                                // print_r($c."- " .$jsons18);
                                                                // echo "<br/><br/><br/>";
                                                            }

                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:CancelPenalty', $value)) {

                                                    foreach ($value['air:CancelPenalty'] as $jsons19) {
                                                        // print_r($jsons19);
                                                        // echo "<br/><br/><br/>";
                                                        foreach ($jsons19 as $cc => $jsons20) {
                                                            if (is_string($jsons20)) {
                                                                if (strcmp($cc, "$") == 0) {
                                                                    $details4["cancelpenalty"] = $jsons20;
                                                                }
                                                                // print_r($c."- " .$jsons20);
                                                                // echo "<br/><br/><br/>";
                                                            }

                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:BaggageAllowances', $value)) {
                                                    // print_r($jsons14['air:BaggageAllowances']);
                                                    // echo "<br/><br/>";
                                                    $count17 = 1;
                                                    foreach ($value['air:BaggageAllowances'] as $jsons17) {
                                                        // echo $count17;
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if ($count17 == 2) {
                                                            // print_r($jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            $count18 = 1;
                                                            foreach ($jsons17 as $jsons18) {
                                                                // echo $count18;
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                if ($count18 == 7) {
                                                                    // print_r($jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count19 = 1;
                                                                    foreach ($jsons18 as $jsons19) {
                                                                        // echo $count19;
                                                                        // print_r($jsons19);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count19 == 2) {
                                                                            // print_r($jsons19);
                                                                            // echo "<br/><br/><br/>";
                                                                            $count20 = 1;
                                                                            foreach ($jsons19 as $jsons20) {
                                                                                // print_r($jsons20);
                                                                                // echo "<br/><br/><br/>";
                                                                                if ($count20 == 1) {
                                                                                    // print_r($jsons20);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    foreach ($jsons20 as $bg => $jsons21) {
                                                                                        // print_r($jsons21);
                                                                                        // echo "<br/><br/><br/>";
                                                                                        if (strcmp($bg, "$") == 0) {
                                                                                            $details4["baggageallowanceinfo"] = $jsons21;
                                                                                        }
                                                                                    }
                                                                                }
                                                                                $count20++;
                                                                            }
                                                                        }
                                                                        $count19++;
                                                                    }
                                                                }
                                                                $count18++;
                                                            }
                                                        }
                                                        if ($count17 == 3) {
                                                            // print_r($jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            $count21 = 1;
                                                            foreach ($jsons17 as $jsons18) {
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                // if($count21==5){  //non stop flight
                                                                if ($count21 == 2 && is_array($jsons18)) {
                                                                    // print_r($jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count22 = 1;
                                                                    foreach ($jsons18 as $jsons19) {
                                                                        // echo $count22;
                                                                        // print_r($jsons19);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count22 == 5) {
                                                                            // print_r($jsons19);
                                                                            // echo "<br/><br/><br/>";
                                                                            $count23 = 1;
                                                                            foreach ($jsons19 as $jsons20) {
                                                                                // print_r($jsons20);
                                                                                // echo "<br/><br/><br/>";
                                                                                if ($count23 == 2) {
                                                                                    // print_r($jsons20);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    foreach ($jsons20 as $cbb => $jsons21) {
                                                                                        if (is_string($jsons21)) {
                                                                                            // print_r($cbb."-".$jsons21);
                                                                                            // echo "<br/><br/><br/>";
                                                                                            if (strcmp($cbb, "$") == 0) {
                                                                                                $details4["carryonallowanceinfo"] = $jsons21;
                                                                                            }
                                                                                        }

                                                                                    }
                                                                                }
                                                                                $count23++;
                                                                            }
                                                                        }
                                                                        $count22++;
                                                                    }
                                                                } else {
                                                                    if ($count21 == 5) {
                                                                        // print_r($jsons18);
                                                                        // echo "<br/><br/><br/>";
                                                                        $count25 = 1;
                                                                        foreach ($jsons18 as $jsons19) {
                                                                            // print_r($jsons19);
                                                                            // echo "<br/><br/><br/>";
                                                                            if ($count25 == 2) {
                                                                                foreach ($jsons19 as $cbb => $jsons20) {
                                                                                    // print_r($jsons20);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    if (is_string($jsons20)) {
                                                                                        // print_r($cbb."-".$jsons21);
                                                                                        // echo "<br/><br/><br/>";
                                                                                        if (strcmp($cbb, "$") == 0) {
                                                                                            $details4["carryonallowanceinfo"] = $jsons20;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            $count25++;
                                                                        }
                                                                    }
                                                                }
                                                                $count21++;
                                                            }
                                                        }

                                                        $count17++;
                                                    }
                                                }

                                                // end multiple travel add adult and child

                                            }
                                            if (empty($AirPricingInfo1) && !empty($AirPricingInfo0)) {
                                                $AirPricingInfo->push($AirPricingInfo0);
                                            }
                                        }
                                        if (!empty($AirPricingInfo1)) {
                                            $AirPricingInfo->push($AirPricingInfo1);
                                        }
                                        if (array_key_exists('air:FareInfo', $jsons14)) {
                                            // print_r($jsons14['air:FareInfo']);
                                            // return $jsons14['air:FareInfo'];
                                            // $FareInfo1=collect();
                                            $FareRuleKey1 = collect();
                                            $FareInfo1 = collect();
                                            $FareInfo = [];
                                            foreach ($jsons14['air:FareInfo'] as $fI => $jsons17) {
                                                $FareInfo0 = [];
                                                // echo $count50;
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                // $FareRuleKey1=collect();
                                                if (is_string($jsons17)) {
                                                    // print_r($fI."-".$jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    if (strcmp($fI, "@Key") == 0) {
                                                        $FareInfo["Key"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@FareBasis") == 0) {
                                                        $FareInfo["FareBasis"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                        $FareInfo["PassengerTypeCode"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@Origin") == 0) {
                                                        $FareInfo["Origin"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@Destination") == 0) {
                                                        $FareInfo["Destination"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@EffectiveDate") == 0) {
                                                        $FareInfo["EffectiveDate"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@DepartureDate") == 0) {
                                                        $FareInfo["DepartureDate"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@Amount") == 0) {
                                                        $FareInfo["Amount"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                        $FareInfo["NegotiatedFare"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@NotValidBefore") == 0) {
                                                        $FareInfo["NotValidBefore"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@TaxAmount") == 0) {
                                                        $FareInfo["TaxAmount"] = $jsons17;
                                                    }
                                                } else {
                                                    foreach ($jsons17 as $fI => $jsons18) {
                                                        if (is_string($jsons18)) {
                                                            // print_r($fI."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($fI, "@Key") == 0) {
                                                                $FareInfo0["Key"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@FareBasis") == 0) {
                                                                $FareInfo0["FareBasis"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                                $FareInfo0["PassengerTypeCode"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@Origin") == 0) {
                                                                $FareInfo0["Origin"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@Destination") == 0) {
                                                                $FareInfo0["Destination"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@EffectiveDate") == 0) {
                                                                $FareInfo0["EffectiveDate"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@DepartureDate") == 0) {
                                                                $FareInfo0["DepartureDate"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@Amount") == 0) {
                                                                $FareInfo0["Amount"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                                $FareInfo0["NegotiatedFare"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@NotValidBefore") == 0) {
                                                                $FareInfo0["NotValidBefore"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@TaxAmount") == 0) {
                                                                $FareInfo0["TaxAmount"] = $jsons18;
                                                            }
                                                        }
                                                    }
                                                    // print_r($jsons17['air:FareRuleKey']);
                                                    // echo "<br/><br/>";
                                                    if (array_key_exists('air:FareRuleKey', $jsons17)) {
                                                        $FareRuleKey0 = [];
                                                        foreach ($jsons17['air:FareRuleKey'] as $frk => $jsons19) {
                                                            if (is_string($jsons19)) {
                                                                if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                    $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                }
                                                                if (strcmp($frk, "@ProviderCode") == 0) {
                                                                    $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                }
                                                                if (strcmp($frk, "$") == 0) {
                                                                    $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                }
                                                            }
                                                        }
                                                        $FareRuleKey1->push($FareRuleKey0);
                                                    }
                                                }

                                                if (empty($FareInfo) && !empty($FareInfo0)) {
                                                    $FareInfo1->push($FareInfo0);
                                                }
                                                // $FareRuleKey1=collect();
                                                if (array_key_exists('air:FareRuleKey', $jsons14['air:FareInfo'])) {
                                                    // print_r($jsons14['air:FareInfo']['air:FareRuleKey']);
                                                    $FareRuleKey = [];

                                                    foreach ($jsons14['air:FareInfo']['air:FareRuleKey'] as $frk => $jsons18) {
                                                        // print_r($jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        $FareRuleKey0 = [];
                                                        if (is_string($jsons18)) {
                                                            // print_r($frk." - ".$jsons18);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                $FareRuleKey["FareInfoRef"] = $jsons18;
                                                            }
                                                            if (strcmp($frk, "@ProviderCode") == 0) {
                                                                $FareRuleKey["ProviderCode"] = $jsons18;
                                                            }
                                                            if (strcmp($frk, "$") == 0) {
                                                                $FareRuleKey["FareRuleKeyValue"] = $jsons18;
                                                            }
                                                        } else {
                                                            foreach ($jsons18 as $frk => $jsons19) {
                                                                if (is_string($jsons19)) {
                                                                    // print_r($frk." - ".$jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                        $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                    }
                                                                    if (strcmp($frk, "@ProviderCode") == 0) {
                                                                        $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                    }
                                                                    if (strcmp($frk, "$") == 0) {
                                                                        $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                    }
                                                                }
                                                            }
                                                            // if(empty($FareRuleKey) && !empty($FareRuleKey0)){
                                                            //     $FareRuleKey1->push($FareRuleKey0);
                                                            // }
                                                        }
                                                    }
                                                    // if(!empty($FareRuleKey)){
                                                    //     $FareRuleKey1->push($FareRuleKey);
                                                    // }
                                                }
                                            }
                                            if (!empty($FareInfo)) {
                                                $FareInfo1->push($FareInfo);
                                            }
                                            if (!empty($FareRuleKey)) {
                                                $FareRuleKey1->push($FareRuleKey);
                                            }


                                        }
                                        if (array_key_exists('air:BookingInfo', $jsons14)) {
                                            $BookingInfo1 = collect();
                                            $BookingInfo = [];
                                            foreach ($jsons14['air:BookingInfo'] as $bki => $jsons17) {
                                                $BookingInfo0 = [];
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                if (is_string($jsons17)) {
                                                    // print_r($bki."-".$jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    if (strcmp($bki, "@BookingCode") == 0) {
                                                        $BookingInfo["BookingCode"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@CabinClass") == 0) {
                                                        $BookingInfo["CabinClass"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@FareInfoRef") == 0) {
                                                        $BookingInfo["FareInfoRef"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@SegmentRef") == 0) {
                                                        $BookingInfo["SegmentRef"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@HostTokenRef") == 0) {
                                                        $BookingInfo["HostTokenRef"] = $jsons17;
                                                    }
                                                } else {
                                                    foreach ($jsons17 as $bki => $jsons18) {
                                                        if (is_string($jsons18)) {
                                                            // print_r($bki."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($bki, "@BookingCode") == 0) {
                                                                $BookingInfo0["BookingCode"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@CabinClass") == 0) {
                                                                $BookingInfo0["CabinClass"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@FareInfoRef") == 0) {
                                                                $BookingInfo0["FareInfoRef"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@SegmentRef") == 0) {
                                                                $BookingInfo0["SegmentRef"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@HostTokenRef") == 0) {
                                                                $BookingInfo0["HostTokenRef"] = $jsons18;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (empty($BookingInfo) && !empty($BookingInfo0)) {
                                                    $BookingInfo1->push($BookingInfo0);
                                                }
                                            }
                                            if (!empty($BookingInfo)) {
                                                $BookingInfo1->push($BookingInfo);
                                            }
                                        }
                                        if (array_key_exists('air:TaxInfo', $jsons14)) {
                                            $TaxInfo = collect();
                                            $TaxInfo1 = [];
                                            foreach ($jsons14['air:TaxInfo'] as $jsons17) {
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                foreach ($jsons17 as $tki => $jsons18) {
                                                    if (is_string($jsons18)) {
                                                        // print_r($tki."-".$jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        if (strcmp($tki, "@Category") == 0) {
                                                            $TaxInfo1["Category"] = $jsons18;
                                                        }
                                                        if (strcmp($tki, "@Amount") == 0) {
                                                            $TaxInfo1["Amount"] = $jsons18;
                                                        }
                                                        if (strcmp($tki, "@Key") == 0) {
                                                            $TaxInfo1["Key"] = $jsons18;
                                                        }

                                                    }
                                                }
                                                $TaxInfo->push($TaxInfo1);
                                            }
                                        }
                                        if (array_key_exists('air:FareCalc', $jsons14)) {
                                            $FareCalc = [];
                                            foreach ($jsons14['air:FareCalc'] as $fcc => $jsons17) {
                                                // print_r($jsons17);
                                                if (is_string($jsons17)) {
                                                    if (strcmp($fcc, "$") == 0) {
                                                        $FareCalc["FareCalc"] = $jsons17;
                                                    }
                                                }
                                            }
                                        }
                                        if (array_key_exists('air:PassengerType', $jsons14)) {
                                            // print_r();
                                            $PassengerType = [];
                                            foreach ($jsons14['air:PassengerType'] as $pc => $jsons17) {
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                if (is_string($jsons17)) {
                                                    if (strcmp($pc, "@Code") == 0) {
                                                        $PassengerType["Code"] = $jsons17;
                                                    }
                                                }
                                            }
                                        }
                                        // $details4=[];
                                        if (array_key_exists('air:ChangePenalty', $jsons14)) {
                                            $details4 = [];
                                            foreach ($jsons14['air:ChangePenalty'] as $jsons17) {
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                foreach ($jsons17 as $c => $jsons18) {
                                                    if (is_string($jsons18)) {
                                                        if (strcmp($c, "$") == 0) {
                                                            $details4["changepenalty"] = $jsons18;
                                                        }
                                                        // print_r($c."- " .$jsons18);
                                                        // echo "<br/><br/><br/>";
                                                    }

                                                }
                                            }
                                        }
                                        if (array_key_exists('air:CancelPenalty', $jsons14)) {

                                            foreach ($jsons14['air:CancelPenalty'] as $jsons19) {
                                                // print_r($jsons19);
                                                // echo "<br/><br/><br/>";
                                                foreach ($jsons19 as $cc => $jsons20) {
                                                    if (is_string($jsons20)) {
                                                        if (strcmp($cc, "$") == 0) {
                                                            $details4["cancelpenalty"] = $jsons20;
                                                        }
                                                        // print_r($c."- " .$jsons20);
                                                        // echo "<br/><br/><br/>";
                                                    }

                                                }
                                            }
                                        }
                                        if (array_key_exists('air:BaggageAllowances', $jsons14)) {
                                            // print_r($jsons14['air:BaggageAllowances']);
                                            // echo "<br/><br/>";
                                            $count17 = 1;
                                            foreach ($jsons14['air:BaggageAllowances'] as $jsons17) {
                                                // echo $count17;
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                if ($count17 == 2) {
                                                    // print_r($jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    $count18 = 1;
                                                    foreach ($jsons17 as $jsons18) {
                                                        // echo $count18;
                                                        // print_r($jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        if ($count18 == 7) {
                                                            // print_r($jsons18);
                                                            // echo "<br/><br/><br/>";
                                                            $count19 = 1;
                                                            foreach ($jsons18 as $jsons19) {
                                                                // echo $count19;
                                                                // print_r($jsons19);
                                                                // echo "<br/><br/><br/>";
                                                                if ($count19 == 2) {
                                                                    // print_r($jsons19);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count20 = 1;
                                                                    foreach ($jsons19 as $jsons20) {
                                                                        // print_r($jsons20);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count20 == 1) {
                                                                            // print_r($jsons20);
                                                                            // echo "<br/><br/><br/>";
                                                                            foreach ($jsons20 as $bg => $jsons21) {
                                                                                // print_r($jsons21);
                                                                                // echo "<br/><br/><br/>";
                                                                                if (strcmp($bg, "$") == 0) {
                                                                                    $details4["baggageallowanceinfo"] = $jsons21;
                                                                                }
                                                                            }
                                                                        }
                                                                        $count20++;
                                                                    }
                                                                }
                                                                $count19++;
                                                            }
                                                        }
                                                        $count18++;
                                                    }
                                                }
                                                if ($count17 == 3) {
                                                    // print_r($jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    $count21 = 1;
                                                    foreach ($jsons17 as $jsons18) {
                                                        // print_r($jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        // if($count21==5){  //non stop flight
                                                        if ($count21 == 2 && is_array($jsons18)) {
                                                            // print_r($jsons18);
                                                            // echo "<br/><br/><br/>";
                                                            $count22 = 1;
                                                            foreach ($jsons18 as $jsons19) {
                                                                // echo $count22;
                                                                // print_r($jsons19);
                                                                // echo "<br/><br/><br/>";
                                                                if ($count22 == 5) {
                                                                    // print_r($jsons19);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count23 = 1;
                                                                    foreach ($jsons19 as $jsons20) {
                                                                        // print_r($jsons20);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count23 == 2) {
                                                                            // print_r($jsons20);
                                                                            // echo "<br/><br/><br/>";
                                                                            foreach ($jsons20 as $cbb => $jsons21) {
                                                                                if (is_string($jsons21)) {
                                                                                    // print_r($cbb."-".$jsons21);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    if (strcmp($cbb, "$") == 0) {
                                                                                        $details4["carryonallowanceinfo"] = $jsons21;
                                                                                    }
                                                                                }

                                                                            }
                                                                        }
                                                                        $count23++;
                                                                    }
                                                                }
                                                                $count22++;
                                                            }
                                                        } else {
                                                            if ($count21 == 5) {
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                $count25 = 1;
                                                                foreach ($jsons18 as $jsons19) {
                                                                    // print_r($jsons19);
                                                                    // echo "<br/><br/><br/>";
                                                                    if ($count25 == 2) {
                                                                        foreach ($jsons19 as $cbb => $jsons20) {
                                                                            // print_r($jsons20);
                                                                            // echo "<br/><br/><br/>";
                                                                            if (is_string($jsons20)) {
                                                                                // print_r($cbb."-".$jsons21);
                                                                                // echo "<br/><br/><br/>";
                                                                                if (strcmp($cbb, "$") == 0) {
                                                                                    $details4["carryonallowanceinfo"] = $jsons20;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    $count25++;
                                                                }
                                                            }
                                                        }
                                                        $count21++;
                                                    }
                                                }

                                                $count17++;
                                            }
                                        }
                                        $data->push(["details" => $details4]);
                                    }
                                    $data->push(["price" => collect($price)]);
                                    $data->push(["AirPricingInfo" => collect($AirPricingInfo)]);
                                    $data->push(["FareInfo" => $FareInfo1]);
                                    $data->push(["FareRuleKey" => $FareRuleKey1]);
                                    $data->push(["BookingInfo" => $BookingInfo1]);

                                    $HostToken = collect();
                                    if (array_key_exists('common_v42_0:HostToken', $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0])) {
                                        // return $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['common_v42_0:HostToken'];
                                        $HostToken1 = [];
                                        foreach ($jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['common_v42_0:HostToken'] as $key => $value) {
                                            $HostToken0 = [];
                                            if (is_string($value)) {
                                                if (strcmp($key, "@Key") == 0) {
                                                    $HostToken1["Key"] = $value;
                                                }
                                                if (strcmp($key, "$") == 0) {
                                                    $HostToken1["HostTokenvalue"] = $value;
                                                }
                                            } else {
                                                foreach ($value as $key => $value1) {
                                                    if (is_string($value1)) {
                                                        if (strcmp($key, "@Key") == 0) {
                                                            $HostToken0["Key"] = $value1;
                                                        }
                                                        if (strcmp($key, "$") == 0) {
                                                            $HostToken0["HostTokenvalue"] = $value1;
                                                        }
                                                    }
                                                }
                                            }
                                            if ($HostToken0 != null) {
                                                $HostToken->push($HostToken0);
                                            }
                                        }
                                        if ($HostToken1 != null) {
                                            $HostToken->push($HostToken1);
                                        }
                                    }
                                    $data->push(["HostToken" => collect($HostToken)]);
                                    $data->push(["TaxInfo" => $TaxInfo]);
                                    $data->push(["FareCalc" => $FareCalc]);
                                    $data->push(["PassengerType" => $PassengerType]);

                                } else {
                                    // return "else";


                                    $price = [];
                                    foreach ($jsons2['air:AirPriceResult']['air:AirPricingSolution'] as $p => $jsons15) {
                                        if (is_string($jsons15)) {
                                            if (strcmp($p, "@Key") == 0) {
                                                $price["Key"] = $jsons15;
                                            }
                                            if (strcmp($p, "@TotalPrice") == 0) {
                                                $price["TotalPrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@BasePrice") == 0) {
                                                $price["BasePrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@ApproximateTotalPrice") == 0) {
                                                $price["ApproximateTotalPrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@ApproximateBasePrice") == 0) {
                                                $price["ApproximateBasePrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@EquivalentBasePrice") == 0) {
                                                $price["EquivalentBasePrice"] = $jsons15;
                                            }
                                            if (strcmp($p, "@Taxes") == 0) {
                                                $price["Taxes"] = $jsons15;
                                            }
                                            if (strcmp($p, "@Fees") == 0) {
                                                $price["Fees"] = $jsons15;
                                            }
                                            if (strcmp($p, "@ApproximateTaxes") == 0) {
                                                $price["ApproximateTaxes"] = $jsons15;
                                            }
                                            if (strcmp($p, "@QuoteDate") == 0) {
                                                $price["QuoteDate"] = $jsons15;
                                            }
                                            if (strcmp($p, "@FareInfoRef") == 0) {
                                                $price["FareInfoRef"] = $jsons15;
                                            }
                                            if (strcmp($p, "@RuleNumber") == 0) {
                                                $price["RuleNumber"] = $jsons15;
                                            }
                                            if (strcmp($p, "@Source") == 0) {
                                                $price["Source"] = $jsons15;
                                            }
                                            if (strcmp($p, "@TariffNumber") == 0) {
                                                $price["TariffNumber"] = $jsons15;
                                            }
                                        }

                                    }
                                    // $data->push(["price"=>collect($price)]);
                                    $AirPricingInfo = collect();
                                    $FareInfo1 = collect();
                                    $FareRuleKey1 = collect();
                                    $BookingInfo1 = collect();
                                    $TaxInfo = collect();

                                    if (array_key_exists('air:AirPricingInfo', $jsons2['air:AirPriceResult']['air:AirPricingSolution'])) {
                                        $jsons14 = $jsons2['air:AirPriceResult']['air:AirPricingSolution']['air:AirPricingInfo'];
                                        // return $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['air:AirPricingInfo'];
                                        $AirPricingInfo1 = [];
                                        foreach ($jsons2['air:AirPriceResult']['air:AirPricingSolution']['air:AirPricingInfo'] as $key => $value) {
                                            $AirPricingInfo0 = [];
                                            if (is_string($value)) {
                                                if (strcmp($key, "@Key") == 0) {
                                                    $AirPricingInfo1["Key"] = $value;
                                                }
                                                if (strcmp($key, "@TotalPrice") == 0) {
                                                    $AirPricingInfo1["TotalPrice"] = $value;
                                                }
                                                if (strcmp($key, "@BasePrice") == 0) {
                                                    $AirPricingInfo1["BasePrice"] = $value;
                                                }
                                                if (strcmp($key, "@ApproximateTotalPrice") == 0) {
                                                    $AirPricingInfo1["ApproximateTotalPrice"] = $value;
                                                }
                                                if (strcmp($key, "@ApproximateBasePrice") == 0) {
                                                    $AirPricingInfo1["ApproximateBasePrice"] = $value;
                                                }
                                                if (strcmp($key, "@EquivalentBasePrice") == 0) {
                                                    $AirPricingInfo1["EquivalentBasePrice"] = $value;
                                                }
                                                if (strcmp($key, "@ApproximateTaxes") == 0) {
                                                    $AirPricingInfo1["ApproximateTaxes"] = $value;
                                                }
                                                if (strcmp($key, "@Taxes") == 0) {
                                                    $AirPricingInfo1["Taxes"] = $value;
                                                }
                                                if (strcmp($key, "@LatestTicketingTime") == 0) {
                                                    $AirPricingInfo1["LatestTicketingTime"] = $value;
                                                }
                                                if (strcmp($key, "@PricingMethod") == 0) {
                                                    $AirPricingInfo1["PricingMethod"] = $value;
                                                }
                                                if (strcmp($key, "@Refundable") == 0) {
                                                    $AirPricingInfo1["Refundable"] = $value;
                                                }
                                                if (strcmp($key, "@IncludesVAT") == 0) {
                                                    $AirPricingInfo1["IncludesVAT"] = $value;
                                                }
                                                if (strcmp($key, "@ETicketability") == 0) {
                                                    $AirPricingInfo1["ETicketability"] = $value;
                                                }
                                                if (strcmp($key, "@PlatingCarrier") == 0) {
                                                    $AirPricingInfo1["PlatingCarrier"] = $value;
                                                }
                                                if (strcmp($key, "@ProviderCode") == 0) {
                                                    $AirPricingInfo1["ProviderCode"] = $value;
                                                }
                                            } else {
                                                foreach ($value as $key => $value1) {
                                                    if (is_string($value1)) {
                                                        if (strcmp($key, "@Key") == 0) {
                                                            $AirPricingInfo0["Key"] = $value1;
                                                        }
                                                        if (strcmp($key, "@TotalPrice") == 0) {
                                                            $AirPricingInfo0["TotalPrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@BasePrice") == 0) {
                                                            $AirPricingInfo0["BasePrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ApproximateTotalPrice") == 0) {
                                                            $AirPricingInfo0["ApproximateTotalPrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ApproximateBasePrice") == 0) {
                                                            $AirPricingInfo0["ApproximateBasePrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@EquivalentBasePrice") == 0) {
                                                            $AirPricingInfo0["EquivalentBasePrice"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ApproximateTaxes") == 0) {
                                                            $AirPricingInfo0["ApproximateTaxes"] = $value1;
                                                        }
                                                        if (strcmp($key, "@Taxes") == 0) {
                                                            $AirPricingInfo0["Taxes"] = $value1;
                                                        }
                                                        if (strcmp($key, "@LatestTicketingTime") == 0) {
                                                            $AirPricingInfo0["LatestTicketingTime"] = $value1;
                                                        }
                                                        if (strcmp($key, "@PricingMethod") == 0) {
                                                            $AirPricingInfo0["PricingMethod"] = $value1;
                                                        }
                                                        if (strcmp($key, "@Refundable") == 0) {
                                                            $AirPricingInfo0["Refundable"] = $value1;
                                                        }
                                                        if (strcmp($key, "@IncludesVAT") == 0) {
                                                            $AirPricingInfo0["IncludesVAT"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ETicketability") == 0) {
                                                            $AirPricingInfo0["ETicketability"] = $value1;
                                                        }
                                                        if (strcmp($key, "@PlatingCarrier") == 0) {
                                                            $AirPricingInfo0["PlatingCarrier"] = $value1;
                                                        }
                                                        if (strcmp($key, "@ProviderCode") == 0) {
                                                            $AirPricingInfo0["ProviderCode"] = $value1;
                                                        }
                                                    }
                                                }

                                                // start multiple travel add adult and child

                                                // return $value;
                                                // print_r($value);
                                                // print_r($value['air:FareInfo']);
                                                // echo "<br/><br/><br/>";

                                                // fareInfo and fareRule key
                                                if (array_key_exists('air:FareInfo', $value)) {
                                                    $FareInfo = [];
                                                    foreach ($value['air:FareInfo'] as $fI => $jsons17) {
                                                        $FareInfo0 = [];
                                                        // echo $count50;
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        // $FareRuleKey1=collect();
                                                        if (is_string($jsons17)) {
                                                            // print_r($fI."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($fI, "@Key") == 0) {
                                                                $FareInfo["Key"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@FareBasis") == 0) {
                                                                $FareInfo["FareBasis"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                                $FareInfo["PassengerTypeCode"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@Origin") == 0) {
                                                                $FareInfo["Origin"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@Destination") == 0) {
                                                                $FareInfo["Destination"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@EffectiveDate") == 0) {
                                                                $FareInfo["EffectiveDate"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@DepartureDate") == 0) {
                                                                $FareInfo["DepartureDate"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@Amount") == 0) {
                                                                $FareInfo["Amount"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                                $FareInfo["NegotiatedFare"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@NotValidBefore") == 0) {
                                                                $FareInfo["NotValidBefore"] = $jsons17;
                                                            }
                                                            if (strcmp($fI, "@TaxAmount") == 0) {
                                                                $FareInfo["TaxAmount"] = $jsons17;
                                                            }
                                                        } else {
                                                            foreach ($jsons17 as $fI => $jsons18) {
                                                                if (is_string($jsons18)) {
                                                                    // print_r($fI."-".$jsons17);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($fI, "@Key") == 0) {
                                                                        $FareInfo0["Key"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@FareBasis") == 0) {
                                                                        $FareInfo0["FareBasis"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                                        $FareInfo0["PassengerTypeCode"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@Origin") == 0) {
                                                                        $FareInfo0["Origin"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@Destination") == 0) {
                                                                        $FareInfo0["Destination"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@EffectiveDate") == 0) {
                                                                        $FareInfo0["EffectiveDate"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@DepartureDate") == 0) {
                                                                        $FareInfo0["DepartureDate"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@Amount") == 0) {
                                                                        $FareInfo0["Amount"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                                        $FareInfo0["NegotiatedFare"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@NotValidBefore") == 0) {
                                                                        $FareInfo0["NotValidBefore"] = $jsons18;
                                                                    }
                                                                    if (strcmp($fI, "@TaxAmount") == 0) {
                                                                        $FareInfo0["TaxAmount"] = $jsons18;
                                                                    }
                                                                }
                                                            }
                                                            // print_r($jsons17['air:FareRuleKey']);
                                                            // echo "<br/><br/>";
                                                            if (array_key_exists('air:FareRuleKey', $jsons17)) {
                                                                $FareRuleKey0 = [];
                                                                foreach ($jsons17['air:FareRuleKey'] as $frk => $jsons19) {
                                                                    if (is_string($jsons19)) {
                                                                        if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                            $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                        }
                                                                        if (strcmp($frk, "@ProviderCode") == 0) {
                                                                            $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                        }
                                                                        if (strcmp($frk, "$") == 0) {
                                                                            $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                        }
                                                                    }
                                                                }
                                                                $FareRuleKey1->push($FareRuleKey0);
                                                            }
                                                        }

                                                        if (empty($FareInfo) && !empty($FareInfo0)) {
                                                            $FareInfo1->push($FareInfo0);
                                                        }
                                                        // $FareRuleKey1=collect();
                                                        if (array_key_exists('air:FareRuleKey', $value['air:FareInfo'])) {
                                                            // print_r($value['air:FareInfo']['air:FareRuleKey']);
                                                            // echo "<br/><br/>";
                                                            $FareRuleKey = [];

                                                            foreach ($value['air:FareInfo']['air:FareRuleKey'] as $frk => $jsons18) {
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                $FareRuleKey0 = [];
                                                                if (is_string($jsons18)) {
                                                                    // print_r($frk." - ".$jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                        $FareRuleKey["FareInfoRef"] = $jsons18;
                                                                    }
                                                                    if (strcmp($frk, "@ProviderCode") == 0) {
                                                                        $FareRuleKey["ProviderCode"] = $jsons18;
                                                                    }
                                                                    if (strcmp($frk, "$") == 0) {
                                                                        $FareRuleKey["FareRuleKeyValue"] = $jsons18;
                                                                    }
                                                                } else {
                                                                    foreach ($jsons18 as $frk => $jsons19) {
                                                                        if (is_string($jsons19)) {
                                                                            // print_r($frk." - ".$jsons18);
                                                                            // echo "<br/><br/><br/>";
                                                                            if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                                $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                            }
                                                                            if (strcmp($frk, "@ProviderCode") == 0) {
                                                                                $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                            }
                                                                            if (strcmp($frk, "$") == 0) {
                                                                                $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                            }
                                                                        }
                                                                    }
                                                                    // if(empty($FareRuleKey) && !empty($FareRuleKey0)){
                                                                    //     $FareRuleKey1->push($FareRuleKey0);
                                                                    // }
                                                                }
                                                            }
                                                            // if(!empty($FareRuleKey)){
                                                            //     $FareRuleKey1->push($FareRuleKey);
                                                            // }
                                                        }
                                                    }
                                                    if (!empty($FareInfo)) {
                                                        $FareInfo1->push($FareInfo);
                                                    }
                                                    if (!empty($FareRuleKey)) {
                                                        $FareRuleKey1->push($FareRuleKey);
                                                    }
                                                }
                                                if (array_key_exists('air:BookingInfo', $value)) {
                                                    // $BookingInfo1=collect();
                                                    $BookingInfo = [];
                                                    foreach ($value['air:BookingInfo'] as $bki => $jsons17) {
                                                        $BookingInfo0 = [];
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if (is_string($jsons17)) {
                                                            // print_r($bki."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($bki, "@BookingCode") == 0) {
                                                                $BookingInfo["BookingCode"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@CabinClass") == 0) {
                                                                $BookingInfo["CabinClass"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@FareInfoRef") == 0) {
                                                                $BookingInfo["FareInfoRef"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@SegmentRef") == 0) {
                                                                $BookingInfo["SegmentRef"] = $jsons17;
                                                            }
                                                            if (strcmp($bki, "@HostTokenRef") == 0) {
                                                                $BookingInfo["HostTokenRef"] = $jsons17;
                                                            }
                                                        } else {
                                                            foreach ($jsons17 as $bki => $jsons18) {
                                                                if (is_string($jsons18)) {
                                                                    // print_r($bki."-".$jsons17);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($bki, "@BookingCode") == 0) {
                                                                        $BookingInfo0["BookingCode"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@CabinClass") == 0) {
                                                                        $BookingInfo0["CabinClass"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@FareInfoRef") == 0) {
                                                                        $BookingInfo0["FareInfoRef"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@SegmentRef") == 0) {
                                                                        $BookingInfo0["SegmentRef"] = $jsons18;
                                                                    }
                                                                    if (strcmp($bki, "@HostTokenRef") == 0) {
                                                                        $BookingInfo0["HostTokenRef"] = $jsons18;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if (empty($BookingInfo) && !empty($BookingInfo0)) {
                                                            $BookingInfo1->push($BookingInfo0);
                                                        }
                                                    }
                                                    // if(empty($BookingInfo) && !empty($BookingInfo0)){
                                                    //     $BookingInfo1->push($BookingInfo0);
                                                    // }
                                                    if (!empty($BookingInfo)) {
                                                        $BookingInfo1->push($BookingInfo);
                                                    }
                                                }
                                                if (array_key_exists('air:TaxInfo', $value)) {
                                                    // $TaxInfo=collect();
                                                    $TaxInfo1 = [];
                                                    foreach ($value['air:TaxInfo'] as $jsons17) {
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if (is_array($jsons17)) {
                                                            foreach ($jsons17 as $tki => $jsons18) {
                                                                if (is_string($jsons18)) {
                                                                    // print_r($tki."-".$jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($tki, "@Category") == 0) {
                                                                        $TaxInfo1["Category"] = $jsons18;
                                                                    }
                                                                    if (strcmp($tki, "@Amount") == 0) {
                                                                        $TaxInfo1["Amount"] = $jsons18;
                                                                    }
                                                                    if (strcmp($tki, "@Key") == 0) {
                                                                        $TaxInfo1["Key"] = $jsons18;
                                                                    }

                                                                }
                                                            }

                                                        }
                                                        $TaxInfo->push($TaxInfo1);
                                                    }
                                                }
                                                if (array_key_exists('air:FareCalc', $value)) {
                                                    $FareCalc = [];
                                                    foreach ($value['air:FareCalc'] as $fcc => $jsons17) {
                                                        // print_r($jsons17);
                                                        if (is_string($jsons17)) {
                                                            if (strcmp($fcc, "$") == 0) {
                                                                $FareCalc["FareCalc"] = $jsons17;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:PassengerType', $value)) {
                                                    // print_r();
                                                    $PassengerType = [];
                                                    foreach ($value['air:PassengerType'] as $pc => $jsons17) {
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if (is_string($jsons17)) {
                                                            if (strcmp($pc, "@Code") == 0) {
                                                                $PassengerType["Code"] = $jsons17;
                                                            }
                                                        }
                                                    }
                                                }
                                                $details4 = [];
                                                if (array_key_exists('air:ChangePenalty', $value)) {
                                                    // $details4=[];
                                                    foreach ($value['air:ChangePenalty'] as $jsons17) {
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        foreach ($jsons17 as $c => $jsons18) {
                                                            if (is_string($jsons18)) {
                                                                if (strcmp($c, "$") == 0) {
                                                                    $details4["changepenalty"] = $jsons18;
                                                                }
                                                                // print_r($c."- " .$jsons18);
                                                                // echo "<br/><br/><br/>";
                                                            }

                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:CancelPenalty', $value)) {

                                                    foreach ($value['air:CancelPenalty'] as $jsons19) {
                                                        // print_r($jsons19);
                                                        // echo "<br/><br/><br/>";
                                                        foreach ($jsons19 as $cc => $jsons20) {
                                                            if (is_string($jsons20)) {
                                                                if (strcmp($cc, "$") == 0) {
                                                                    $details4["cancelpenalty"] = $jsons20;
                                                                }
                                                                // print_r($c."- " .$jsons20);
                                                                // echo "<br/><br/><br/>";
                                                            }

                                                        }
                                                    }
                                                }
                                                if (array_key_exists('air:BaggageAllowances', $value)) {
                                                    // print_r($jsons14['air:BaggageAllowances']);
                                                    // echo "<br/><br/>";
                                                    $count17 = 1;
                                                    foreach ($value['air:BaggageAllowances'] as $jsons17) {
                                                        // echo $count17;
                                                        // print_r($jsons17);
                                                        // echo "<br/><br/><br/>";
                                                        if ($count17 == 2) {
                                                            // print_r($jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            $count18 = 1;
                                                            foreach ($jsons17 as $jsons18) {
                                                                // echo $count18;
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                if ($count18 == 7) {
                                                                    // print_r($jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count19 = 1;
                                                                    foreach ($jsons18 as $jsons19) {
                                                                        // echo $count19;
                                                                        // print_r($jsons19);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count19 == 2) {
                                                                            // print_r($jsons19);
                                                                            // echo "<br/><br/><br/>";
                                                                            $count20 = 1;
                                                                            foreach ($jsons19 as $jsons20) {
                                                                                // print_r($jsons20);
                                                                                // echo "<br/><br/><br/>";
                                                                                if ($count20 == 1) {
                                                                                    // print_r($jsons20);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    foreach ($jsons20 as $bg => $jsons21) {
                                                                                        // print_r($jsons21);
                                                                                        // echo "<br/><br/><br/>";
                                                                                        if (strcmp($bg, "$") == 0) {
                                                                                            $details4["baggageallowanceinfo"] = $jsons21;
                                                                                        }
                                                                                    }
                                                                                }
                                                                                $count20++;
                                                                            }
                                                                        }
                                                                        $count19++;
                                                                    }
                                                                }
                                                                $count18++;
                                                            }
                                                        }
                                                        if ($count17 == 3) {
                                                            // print_r($jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            $count21 = 1;
                                                            foreach ($jsons17 as $jsons18) {
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                // if($count21==5){  //non stop flight
                                                                if ($count21 == 2 && is_array($jsons18)) {
                                                                    // print_r($jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count22 = 1;
                                                                    foreach ($jsons18 as $jsons19) {
                                                                        // echo $count22;
                                                                        // print_r($jsons19);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count22 == 5) {
                                                                            // print_r($jsons19);
                                                                            // echo "<br/><br/><br/>";
                                                                            $count23 = 1;
                                                                            foreach ($jsons19 as $jsons20) {
                                                                                // print_r($jsons20);
                                                                                // echo "<br/><br/><br/>";
                                                                                if ($count23 == 2) {
                                                                                    // print_r($jsons20);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    foreach ($jsons20 as $cbb => $jsons21) {
                                                                                        if (is_string($jsons21)) {
                                                                                            // print_r($cbb."-".$jsons21);
                                                                                            // echo "<br/><br/><br/>";
                                                                                            if (strcmp($cbb, "$") == 0) {
                                                                                                $details4["carryonallowanceinfo"] = $jsons21;
                                                                                            }
                                                                                        }

                                                                                    }
                                                                                }
                                                                                $count23++;
                                                                            }
                                                                        }
                                                                        $count22++;
                                                                    }
                                                                } else {
                                                                    if ($count21 == 5) {
                                                                        // print_r($jsons18);
                                                                        // echo "<br/><br/><br/>";
                                                                        $count25 = 1;
                                                                        foreach ($jsons18 as $jsons19) {
                                                                            // print_r($jsons19);
                                                                            // echo "<br/><br/><br/>";
                                                                            if ($count25 == 2) {
                                                                                if (is_array($jsons19)) {
                                                                                    foreach ($jsons19 as $cbb => $jsons20) {
                                                                                        // print_r($jsons20);
                                                                                        // echo "<br/><br/><br/>";
                                                                                        if (is_string($jsons20)) {
                                                                                            // print_r($cbb."-".$jsons21);
                                                                                            // echo "<br/><br/><br/>";
                                                                                            if (strcmp($cbb, "$") == 0) {
                                                                                                $details4["carryonallowanceinfo"] = $jsons20;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            $count25++;
                                                                        }
                                                                    }
                                                                }
                                                                $count21++;
                                                            }
                                                        }

                                                        $count17++;
                                                    }
                                                }

                                                // end multiple travel add adult and child

                                            }
                                            if (empty($AirPricingInfo1) && !empty($AirPricingInfo0)) {
                                                $AirPricingInfo->push($AirPricingInfo0);
                                            }
                                        }
                                        if (!empty($AirPricingInfo1)) {
                                            $AirPricingInfo->push($AirPricingInfo1);
                                        }
                                        if (array_key_exists('air:FareInfo', $jsons14)) {
                                            // print_r($jsons14['air:FareInfo']);
                                            // return $jsons14['air:FareInfo'];
                                            // $FareInfo1=collect();
                                            $FareRuleKey1 = collect();
                                            $FareInfo1 = collect();
                                            $FareInfo = [];
                                            foreach ($jsons14['air:FareInfo'] as $fI => $jsons17) {
                                                $FareInfo0 = [];
                                                // echo $count50;
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                // $FareRuleKey1=collect();
                                                if (is_string($jsons17)) {
                                                    // print_r($fI."-".$jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    if (strcmp($fI, "@Key") == 0) {
                                                        $FareInfo["Key"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@FareBasis") == 0) {
                                                        $FareInfo["FareBasis"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                        $FareInfo["PassengerTypeCode"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@Origin") == 0) {
                                                        $FareInfo["Origin"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@Destination") == 0) {
                                                        $FareInfo["Destination"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@EffectiveDate") == 0) {
                                                        $FareInfo["EffectiveDate"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@DepartureDate") == 0) {
                                                        $FareInfo["DepartureDate"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@Amount") == 0) {
                                                        $FareInfo["Amount"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                        $FareInfo["NegotiatedFare"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@NotValidBefore") == 0) {
                                                        $FareInfo["NotValidBefore"] = $jsons17;
                                                    }
                                                    if (strcmp($fI, "@TaxAmount") == 0) {
                                                        $FareInfo["TaxAmount"] = $jsons17;
                                                    }
                                                } else {
                                                    foreach ($jsons17 as $fI => $jsons18) {
                                                        if (is_string($jsons18)) {
                                                            // print_r($fI."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($fI, "@Key") == 0) {
                                                                $FareInfo0["Key"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@FareBasis") == 0) {
                                                                $FareInfo0["FareBasis"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@PassengerTypeCode") == 0) {
                                                                $FareInfo0["PassengerTypeCode"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@Origin") == 0) {
                                                                $FareInfo0["Origin"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@Destination") == 0) {
                                                                $FareInfo0["Destination"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@EffectiveDate") == 0) {
                                                                $FareInfo0["EffectiveDate"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@DepartureDate") == 0) {
                                                                $FareInfo0["DepartureDate"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@Amount") == 0) {
                                                                $FareInfo0["Amount"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@NegotiatedFare") == 0) {
                                                                $FareInfo0["NegotiatedFare"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@NotValidBefore") == 0) {
                                                                $FareInfo0["NotValidBefore"] = $jsons18;
                                                            }
                                                            if (strcmp($fI, "@TaxAmount") == 0) {
                                                                $FareInfo0["TaxAmount"] = $jsons18;
                                                            }
                                                        }
                                                    }
                                                    // print_r($jsons17['air:FareRuleKey']);
                                                    // echo "<br/><br/>";
                                                    if (array_key_exists('air:FareRuleKey', $jsons17)) {
                                                        $FareRuleKey0 = [];
                                                        foreach ($jsons17['air:FareRuleKey'] as $frk => $jsons19) {
                                                            if (is_string($jsons19)) {
                                                                if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                    $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                }
                                                                if (strcmp($frk, "@ProviderCode") == 0) {
                                                                    $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                }
                                                                if (strcmp($frk, "$") == 0) {
                                                                    $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                }
                                                            }
                                                        }
                                                        $FareRuleKey1->push($FareRuleKey0);
                                                    }
                                                }

                                                if (empty($FareInfo) && !empty($FareInfo0)) {
                                                    $FareInfo1->push($FareInfo0);
                                                }
                                                // $FareRuleKey1=collect();
                                                if (array_key_exists('air:FareRuleKey', $jsons14['air:FareInfo'])) {
                                                    // print_r($jsons14['air:FareInfo']['air:FareRuleKey']);
                                                    $FareRuleKey = [];

                                                    foreach ($jsons14['air:FareInfo']['air:FareRuleKey'] as $frk => $jsons18) {
                                                        // print_r($jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        $FareRuleKey0 = [];
                                                        if (is_string($jsons18)) {
                                                            // print_r($frk." - ".$jsons18);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                $FareRuleKey["FareInfoRef"] = $jsons18;
                                                            }
                                                            if (strcmp($frk, "@ProviderCode") == 0) {
                                                                $FareRuleKey["ProviderCode"] = $jsons18;
                                                            }
                                                            if (strcmp($frk, "$") == 0) {
                                                                $FareRuleKey["FareRuleKeyValue"] = $jsons18;
                                                            }
                                                        } else {
                                                            foreach ($jsons18 as $frk => $jsons19) {
                                                                if (is_string($jsons19)) {
                                                                    // print_r($frk." - ".$jsons18);
                                                                    // echo "<br/><br/><br/>";
                                                                    if (strcmp($frk, "@FareInfoRef") == 0) {
                                                                        $FareRuleKey0["FareInfoRef"] = $jsons19;
                                                                    }
                                                                    if (strcmp($frk, "@ProviderCode") == 0) {
                                                                        $FareRuleKey0["ProviderCode"] = $jsons19;
                                                                    }
                                                                    if (strcmp($frk, "$") == 0) {
                                                                        $FareRuleKey0["FareRuleKeyValue"] = $jsons19;
                                                                    }
                                                                }
                                                            }
                                                            // if(empty($FareRuleKey) && !empty($FareRuleKey0)){
                                                            //     $FareRuleKey1->push($FareRuleKey0);
                                                            // }
                                                        }
                                                    }
                                                    // if(!empty($FareRuleKey)){
                                                    //     $FareRuleKey1->push($FareRuleKey);
                                                    // }
                                                }
                                            }
                                            if (!empty($FareInfo)) {
                                                $FareInfo1->push($FareInfo);
                                            }
                                            if (!empty($FareRuleKey)) {
                                                $FareRuleKey1->push($FareRuleKey);
                                            }


                                        }
                                        if (array_key_exists('air:BookingInfo', $jsons14)) {
                                            $BookingInfo1 = collect();
                                            $BookingInfo = [];
                                            foreach ($jsons14['air:BookingInfo'] as $bki => $jsons17) {
                                                $BookingInfo0 = [];
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                if (is_string($jsons17)) {
                                                    // print_r($bki."-".$jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    if (strcmp($bki, "@BookingCode") == 0) {
                                                        $BookingInfo["BookingCode"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@CabinClass") == 0) {
                                                        $BookingInfo["CabinClass"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@FareInfoRef") == 0) {
                                                        $BookingInfo["FareInfoRef"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@SegmentRef") == 0) {
                                                        $BookingInfo["SegmentRef"] = $jsons17;
                                                    }
                                                    if (strcmp($bki, "@HostTokenRef") == 0) {
                                                        $BookingInfo["HostTokenRef"] = $jsons17;
                                                    }
                                                } else {
                                                    foreach ($jsons17 as $bki => $jsons18) {
                                                        if (is_string($jsons18)) {
                                                            // print_r($bki."-".$jsons17);
                                                            // echo "<br/><br/><br/>";
                                                            if (strcmp($bki, "@BookingCode") == 0) {
                                                                $BookingInfo0["BookingCode"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@CabinClass") == 0) {
                                                                $BookingInfo0["CabinClass"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@FareInfoRef") == 0) {
                                                                $BookingInfo0["FareInfoRef"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@SegmentRef") == 0) {
                                                                $BookingInfo0["SegmentRef"] = $jsons18;
                                                            }
                                                            if (strcmp($bki, "@HostTokenRef") == 0) {
                                                                $BookingInfo0["HostTokenRef"] = $jsons18;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (empty($BookingInfo) && !empty($BookingInfo0)) {
                                                    $BookingInfo1->push($BookingInfo0);
                                                }
                                            }
                                            if (!empty($BookingInfo)) {
                                                $BookingInfo1->push($BookingInfo);
                                            }
                                        }
                                        if (array_key_exists('air:TaxInfo', $jsons14)) {
                                            $TaxInfo = collect();
                                            $TaxInfo1 = [];
                                            foreach ($jsons14['air:TaxInfo'] as $jsons17) {
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                foreach ($jsons17 as $tki => $jsons18) {
                                                    if (is_string($jsons18)) {
                                                        // print_r($tki."-".$jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        if (strcmp($tki, "@Category") == 0) {
                                                            $TaxInfo1["Category"] = $jsons18;
                                                        }
                                                        if (strcmp($tki, "@Amount") == 0) {
                                                            $TaxInfo1["Amount"] = $jsons18;
                                                        }
                                                        if (strcmp($tki, "@Key") == 0) {
                                                            $TaxInfo1["Key"] = $jsons18;
                                                        }

                                                    }
                                                }
                                                $TaxInfo->push($TaxInfo1);
                                            }
                                        }
                                        if (array_key_exists('air:FareCalc', $jsons14)) {
                                            $FareCalc = [];
                                            foreach ($jsons14['air:FareCalc'] as $fcc => $jsons17) {
                                                // print_r($jsons17);
                                                if (is_string($jsons17)) {
                                                    if (strcmp($fcc, "$") == 0) {
                                                        $FareCalc["FareCalc"] = $jsons17;
                                                    }
                                                }
                                            }
                                        }
                                        if (array_key_exists('air:PassengerType', $jsons14)) {
                                            // print_r();
                                            $PassengerType = [];
                                            foreach ($jsons14['air:PassengerType'] as $pc => $jsons17) {
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                if (is_string($jsons17)) {
                                                    if (strcmp($pc, "@Code") == 0) {
                                                        $PassengerType["Code"] = $jsons17;
                                                    }
                                                }
                                            }
                                        }
                                        // $details4=[];
                                        if (array_key_exists('air:ChangePenalty', $jsons14)) {
                                            $details4 = [];
                                            foreach ($jsons14['air:ChangePenalty'] as $jsons17) {
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                foreach ($jsons17 as $c => $jsons18) {
                                                    if (is_string($jsons18)) {
                                                        if (strcmp($c, "$") == 0) {
                                                            $details4["changepenalty"] = $jsons18;
                                                        }
                                                        // print_r($c."- " .$jsons18);
                                                        // echo "<br/><br/><br/>";
                                                    }

                                                }
                                            }
                                        }
                                        if (array_key_exists('air:CancelPenalty', $jsons14)) {

                                            foreach ($jsons14['air:CancelPenalty'] as $jsons19) {
                                                // print_r($jsons19);
                                                // echo "<br/><br/><br/>";
                                                foreach ($jsons19 as $cc => $jsons20) {
                                                    if (is_string($jsons20)) {
                                                        if (strcmp($cc, "$") == 0) {
                                                            $details4["cancelpenalty"] = $jsons20;
                                                        }
                                                        // print_r($c."- " .$jsons20);
                                                        // echo "<br/><br/><br/>";
                                                    }

                                                }
                                            }
                                        }
                                        if (array_key_exists('air:BaggageAllowances', $jsons14)) {
                                            // print_r($jsons14['air:BaggageAllowances']);
                                            // echo "<br/><br/>";
                                            $count17 = 1;
                                            foreach ($jsons14['air:BaggageAllowances'] as $jsons17) {
                                                // echo $count17;
                                                // print_r($jsons17);
                                                // echo "<br/><br/><br/>";
                                                if ($count17 == 2) {
                                                    // print_r($jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    $count18 = 1;
                                                    foreach ($jsons17 as $jsons18) {
                                                        // echo $count18;
                                                        // print_r($jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        if ($count18 == 7) {
                                                            // print_r($jsons18);
                                                            // echo "<br/><br/><br/>";
                                                            $count19 = 1;
                                                            foreach ($jsons18 as $jsons19) {
                                                                // echo $count19;
                                                                // print_r($jsons19);
                                                                // echo "<br/><br/><br/>";
                                                                if ($count19 == 2) {
                                                                    // print_r($jsons19);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count20 = 1;
                                                                    foreach ($jsons19 as $jsons20) {
                                                                        // print_r($jsons20);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count20 == 1) {
                                                                            // print_r($jsons20);
                                                                            // echo "<br/><br/><br/>";
                                                                            foreach ($jsons20 as $bg => $jsons21) {
                                                                                // print_r($jsons21);
                                                                                // echo "<br/><br/><br/>";
                                                                                if (strcmp($bg, "$") == 0) {
                                                                                    $details4["baggageallowanceinfo"] = $jsons21;
                                                                                }
                                                                            }
                                                                        }
                                                                        $count20++;
                                                                    }
                                                                }
                                                                $count19++;
                                                            }
                                                        }
                                                        $count18++;
                                                    }
                                                }
                                                if ($count17 == 3) {
                                                    // print_r($jsons17);
                                                    // echo "<br/><br/><br/>";
                                                    $count21 = 1;
                                                    foreach ($jsons17 as $jsons18) {
                                                        // print_r($jsons18);
                                                        // echo "<br/><br/><br/>";
                                                        // if($count21==5){  //non stop flight
                                                        if ($count21 == 2 && is_array($jsons18)) {
                                                            // print_r($jsons18);
                                                            // echo "<br/><br/><br/>";
                                                            $count22 = 1;
                                                            foreach ($jsons18 as $jsons19) {
                                                                // echo $count22;
                                                                // print_r($jsons19);
                                                                // echo "<br/><br/><br/>";
                                                                if ($count22 == 5) {
                                                                    // print_r($jsons19);
                                                                    // echo "<br/><br/><br/>";
                                                                    $count23 = 1;
                                                                    foreach ($jsons19 as $jsons20) {
                                                                        // print_r($jsons20);
                                                                        // echo "<br/><br/><br/>";
                                                                        if ($count23 == 2) {
                                                                            // print_r($jsons20);
                                                                            // echo "<br/><br/><br/>";
                                                                            foreach ($jsons20 as $cbb => $jsons21) {
                                                                                if (is_string($jsons21)) {
                                                                                    // print_r($cbb."-".$jsons21);
                                                                                    // echo "<br/><br/><br/>";
                                                                                    if (strcmp($cbb, "$") == 0) {
                                                                                        $details4["carryonallowanceinfo"] = $jsons21;
                                                                                    }
                                                                                }

                                                                            }
                                                                        }
                                                                        $count23++;
                                                                    }
                                                                }
                                                                $count22++;
                                                            }
                                                        } else {
                                                            if ($count21 == 5) {
                                                                // print_r($jsons18);
                                                                // echo "<br/><br/><br/>";
                                                                $count25 = 1;
                                                                foreach ($jsons18 as $jsons19) {
                                                                    // print_r($jsons19);
                                                                    // echo "<br/><br/><br/>";
                                                                    if ($count25 == 2) {
                                                                        foreach ($jsons19 as $cbb => $jsons20) {
                                                                            // print_r($jsons20);
                                                                            // echo "<br/><br/><br/>";
                                                                            if (is_string($jsons20)) {
                                                                                // print_r($cbb."-".$jsons21);
                                                                                // echo "<br/><br/><br/>";
                                                                                if (strcmp($cbb, "$") == 0) {
                                                                                    $details4["carryonallowanceinfo"] = $jsons20;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    $count25++;
                                                                }
                                                            }
                                                        }
                                                        $count21++;
                                                    }
                                                }

                                                $count17++;
                                            }
                                        }
                                        $data->push(["details" => $details4]);
                                    }
                                    $data->push(["price" => collect($price)]);
                                    $data->push(["AirPricingInfo" => collect($AirPricingInfo)]);
                                    $data->push(["FareInfo" => $FareInfo1]);
                                    $data->push(["FareRuleKey" => $FareRuleKey1]);
                                    $data->push(["BookingInfo" => $BookingInfo1]);

                                    $HostToken = collect();
                                    if (array_key_exists('common_v42_0:HostToken', $jsons2['air:AirPriceResult']['air:AirPricingSolution'])) {
                                        // return $jsons2['air:AirPriceResult']['air:AirPricingSolution'][0]['common_v42_0:HostToken'];
                                        $HostToken1 = [];
                                        foreach ($jsons2['air:AirPriceResult']['air:AirPricingSolution']['common_v42_0:HostToken'] as $key => $value) {
                                            $HostToken0 = [];
                                            if (is_string($value)) {
                                                if (strcmp($key, "@Key") == 0) {
                                                    $HostToken1["Key"] = $value;
                                                }
                                                if (strcmp($key, "$") == 0) {
                                                    $HostToken1["HostTokenvalue"] = $value;
                                                }
                                            } else {
                                                foreach ($value as $key => $value1) {
                                                    if (is_string($value1)) {
                                                        if (strcmp($key, "@Key") == 0) {
                                                            $HostToken0["Key"] = $value1;
                                                        }
                                                        if (strcmp($key, "$") == 0) {
                                                            $HostToken0["HostTokenvalue"] = $value1;
                                                        }
                                                    }
                                                }
                                            }
                                            if ($HostToken0 != null) {
                                                $HostToken->push($HostToken0);
                                            }
                                        }
                                        if ($HostToken1 != null) {
                                            $HostToken->push($HostToken1);
                                        }
                                    }
                                    $data->push(["HostToken" => collect($HostToken)]);
                                    $data->push(["TaxInfo" => $TaxInfo]);
                                    $data->push(["FareCalc" => $FareCalc]);
                                    $data->push(["PassengerType" => $PassengerType]);


                                }

                            }

                        }
                    }

                }
            }
        }
        return $data;
    }


    public function parseXMLRoundTrip($content)
    {
        $data = collect();
        //parse the Search response to get values to use in detail request
        $LowFareSearchRsp = $content; //use this if response is not saved anywhere else use above variable
        //echo $LowFareSearchRsp;
//        dd($LowFareSearchRsp);
        $xml = simplexml_load_String("$LowFareSearchRsp", null, null, 'SOAP', true);

        if (!$xml) {
            trigger_error("Encoding Error!", E_USER_ERROR);
        }

        $Results = $xml->children('SOAP', true);
        foreach ($Results->children('SOAP', true) as $fault) {
            if (strcmp($fault->getName(), 'Fault') == 0) {
                // trigger_error("Error occurred request/response processing!", E_USER_ERROR);
                return $data;
            }
        }


        $count = 0;
        $fileName = public_path('flight/') . "flights.txt";
        if (file_exists($fileName)) {
            file_put_contents($fileName, "");
        }

        // $data = collect();

        foreach ($Results->children('air', true) as $lowFare) {
            foreach ($lowFare->children('air', true) as $airPriceSol) {

                if (strcmp($airPriceSol->getName(), 'AirPricingSolution') == 0) {
                    $count = $count + 1;
                    $Journey = collect();
                    $Journey_Outbound_Inbound = collect();
                    $var_toggle_journey_conunt = 0;
                    foreach ($airPriceSol->children('air', true) as $journey) {
                        if (strcmp($journey->getName(), 'Journey') == 0) {
                            $var_toggle_journey_conunt += 1;
                            $journeydetails = collect();
                            foreach ($journey->children('air', true) as $segmentRef) {

                                if (strcmp($segmentRef->getName(), 'AirSegmentRef') == 0) {
                                    $details = [];
                                    foreach ($segmentRef->attributes() as $a => $b) {

                                        $segment = $this->ListAirSegments($b, $lowFare);
                                        foreach ($segment->attributes() as $c => $d) {
                                            if (strcmp($c, "Key") == 0) {
                                                $details["Key"] = $d;
                                            }
                                            if (strcmp($c, "Group") == 0) {
                                                $details["Group"] = $d;
                                            }
                                            if (strcmp($c, "Origin") == 0) {
                                                // $journeydetails->push(['From'=>$d]);
                                                $details["From"] = $d;
                                            }
                                            if (strcmp($c, "Destination") == 0) {
                                                // $journeydetails->push(['To'=>$d]);
                                                $details["To"] = $d;
                                            }
                                            if (strcmp($c, "Carrier") == 0) {
                                                // $journeydetails->push(['Airline'=>$d]);
                                                $details["Airline"] = $d;
                                            }
                                            if (strcmp($c, "FlightNumber") == 0) {
                                                // $journeydetails->push(['flight'=>$d]);
                                                $details["Flight"] = $d;
                                            }
                                            if (strcmp($c, "DepartureTime") == 0) {
                                                // $journeydetails->push(['Depart'=>$d]);
                                                $details["Depart"] = $d;
                                            }
                                            if (strcmp($c, "ArrivalTime") == 0) {
                                                // $journeydetails->push(['Arrive'=>$d]);
                                                $details["Arrive"] = $d;
                                            }
                                            if (strcmp($c, "FlightTime") == 0) {
                                                $details["FlightTime"] = $d;
                                            }
                                            if (strcmp($c, "Distance") == 0) {
                                                $details["Distance"] = $d;
                                            }

                                        }

                                    }
                                    $journeydetails->push($details);
                                }
                            }


                            if ($var_toggle_journey_conunt == 1) {
                                $Journey_Outbound_Inbound->push(['outbound' => collect($journeydetails)]);
                            } else if ($var_toggle_journey_conunt == 2) {
                                $Journey_Outbound_Inbound->push(['inbound' => collect($journeydetails)]);
                            }

                        }
                    }
                    $Journey->push(['journey' => collect($Journey_Outbound_Inbound)]);
                    // Price Details
                    foreach ($airPriceSol->children('air', true) as $priceInfo) {
                        $flightPrice = [];
                        if (strcmp($priceInfo->getName(), 'AirPricingInfo') == 0) {
                            foreach ($priceInfo->attributes() as $e => $f) {
                                if (strcmp($e, "ApproximateBasePrice") == 0) {
                                    $flightPrice['Approx Base Price'] = $f;
                                }
                                if (strcmp($e, "ApproximateTaxes") == 0) {
                                    $flightPrice['Approx Taxes'] = $f;
                                }
                                if (strcmp($e, "ApproximateTotalPrice") == 0) {
                                    $flightPrice['Approx Total Value'] = $f;
                                }
                                if (strcmp($e, "BasePrice") == 0) {
                                    $flightPrice['Base Price'] = $f;
                                }
                                if (strcmp($e, "Taxes") == 0) {
                                    $flightPrice['Taxes'] = $f;
                                }
                                if (strcmp($e, "TotalPrice") == 0) {
                                    $flightPrice['Total Price'] = $f;
                                }

                            }
                            foreach ($priceInfo->children('air', true) as $bookingInfo) {
                                if (strcmp($bookingInfo->getName(), 'BookingInfo') == 0) {
                                    foreach ($bookingInfo->attributes() as $e => $f) {
                                        if (strcmp($e, "CabinClass") == 0) {
                                            $flightPrice['Cabin Class'] = $f;
                                        }
                                    }
                                }
                            }

                        }
                        if (count($flightPrice)) {
                            $Journey->push(['price' => $flightPrice]);
                            // $flight['price'] = collect($flightPrice);
                        }

                    }

                    $data->push(['flight' => collect($Journey)]);
                }
            }
        }

        // print_r($data) ;
        // echo $data;
        return $data;
        // echo "\r\n"."Processing Done. Please check results in files.";

    }
    function formatRoundTripFlightData($flightData, $request)
    {
        $flights = collect();
        foreach ($flightData as $flight) {
            foreach ($flight as $flight_data) {
                $dataType = ['pricePerAdult', 'pricePerChild', 'pricePerInfant'];

                $totalPrice = 0;
                $details = collect();
                $prices = collect();
                foreach ($dataType as $key => $type) {
                    foreach ($flight_data[$key + 1] as $data) {
                        $totalPrice += ((int)str_replace($request['currency'], '', $this->convertObjectString($data['Total Price']))) * (int)($type == 'pricePerAdult' ? $request['adult'] : ($type == 'pricePerChild' ? $request['child'] : $request['infant']));
                        $temp = [$type => [
                            'totalForSearchedPassengers' => ((int)str_replace($request['currency'], '', $this->convertObjectString($data['Total Price']))) * (int)($type == 'pricePerAdult' ? $request['adult'] : ($type == 'pricePerChild' ? $request['child'] : $request['infant'])),
                            'totalPrice' => $this->convertObjectString($data['Total Price']),
                            'basePrice' => $this->convertObjectString($data['Base Price']),
                            'approxTotalValue' => $this->convertObjectString($data['Approx Total Value']),
                            'approxBasePrice' => $this->convertObjectString($data['Approx Base Price']),
                            'taxes' => $this->convertObjectString($data['Taxes']),
                            'approxTaxes' => $this->convertObjectString($data['Approx Taxes']),
                            'cabinClass' => $this->convertObjectString($data['Cabin Class']),
                        ]];
                        $prices->push($temp);
                    }
                }
                foreach ($flight_data[0] as $data) {
                    $outbound = $data[0]['outbound'];
                    $inbound = $data[1]['inbound'];

                    $outSegments = collect();
                    $inSegments = collect();
                    foreach ($outbound as $outSegment) {
                        $temp = [
                            'airline' => $this->convertObjectString($outSegment['Airline']),
                            'flight' => $this->convertObjectString($outSegment['Flight']),
                            'origin' => $this->convertObjectString($outSegment['From']),
                            'destination' => $this->convertObjectString($outSegment['To']),
                            'departure' => $this->convertObjectString($outSegment['Depart']),
                            'arrival' => $this->convertObjectString($outSegment['Arrive']),
                            'departureFormatted' => Carbon::parse($this->convertObjectString($outSegment['Depart']))->format('d F,Y h:i A'),
                            'arrivalFormatted' => Carbon::parse($this->convertObjectString($outSegment['Arrive']))->format('d F,Y h:i A'),

                            'flightTime' => $this->convertObjectString($outSegment['FlightTime']),
                            'flightTimeFormatted' => hoursandmins($this->convertObjectString($outSegment['FlightTime'])),
                            'distance' => $this->convertObjectString($outSegment['Distance']),
                            'group' => $this->convertObjectString($outSegment['Group']),
                            'key' => $this->convertObjectString($outSegment['Key']),
                        ];
                        $outSegments->push($temp);
                    }
                    foreach ($inbound as $inSegment) {
                        $temp = [
                            'airline' => $this->convertObjectString($inSegment['Airline']),
                            'flight' => $this->convertObjectString($inSegment['Flight']),
                            'origin' => $this->convertObjectString($inSegment['From']),
                            'destination' => $this->convertObjectString($inSegment['To']),
                            'departure' => $this->convertObjectString($inSegment['Depart']),
                            'arrival' => $this->convertObjectString($inSegment['Arrive']),
                            'departureFormatted' => Carbon::parse($this->convertObjectString($inSegment['Depart']))->format('d F,Y h:i A'),
                            'arrivalFormatted' => Carbon::parse($this->convertObjectString($inSegment['Arrive']))->format('d F,Y h:i A'),
                            'flightTime' => $this->convertObjectString($inSegment['FlightTime']),
                            'flightTimeFormatted' => hoursandmins($this->convertObjectString($inSegment['FlightTime'])),
                            'distance' => $this->convertObjectString($inSegment['Distance']),
                            'group' => $this->convertObjectString($inSegment['Group']),
                            'key' => $this->convertObjectString($inSegment['Key']),
                        ];
                        $inSegments->push($temp);
                    }

                    $details->push([
                        'adult' => $request['adult'],
                        'children' => $request['child'],
                        'infant' => $request['infant'],
                        'type' => $request['tripType'],
                        'outboundDepartureDate' => ['raw' => Carbon::parse($outSegments->first()['departure'])->format('Y-m-d'), 'formatted' => Carbon::parse($outSegments->first()['departure'])->format('D, d F, Y')],
                        'outboundArrivalDate' => ['raw' => Carbon::parse($outSegments->first()['arrival'])->format('Y-m-d'), 'formatted' => Carbon::parse($outSegments->first()['arrival'])->format('D, d F, Y')],
                        'inboundDepartureDate' => ['raw' => Carbon::parse($inSegments->first()['departure'])->format('Y-m-d'), 'formatted' => Carbon::parse($inSegments->first()['departure'])->format('D, d F, Y')],
                        'inboundArrivalDate' => ['raw' => Carbon::parse($inSegments->last()['arrival'])->format('Y-m-d'), 'formatted' => Carbon::parse($inSegments->last()['arrival'])->format('D, d F, Y')],
                        'outbound' => [
                            'origin' => $outSegments->first()['origin'],
                            'airline' => $outSegments->first()['airline'],
                            'destination' => $outSegments->last()['destination'],
                            'departure' => $outSegments->first()['departure'],
                            'arrival' => $outSegments->last()['arrival'],
                            'totalTimeTakenFromOriginToDestination'=>hoursandmins($outSegments->pluck('flightTime')->sum()),
                            'departureDate' => Carbon::parse($this->convertObjectString($outSegments->first()['departure']))->format('d F,Y'),
                            'departureTime' => Carbon::parse($this->convertObjectString($outSegments->first()['departure']))->format('h:i A'),
                            'arrivalDate' => Carbon::parse($this->convertObjectString($outSegments->last()['arrival']))->format('d F,Y'),
                            'arrivalTime' => Carbon::parse($this->convertObjectString($outSegments->last()['arrival']))->format('h:i A'),

                            'totalFlightTime' => $outSegments->first()['flightTime'] + ($outSegments->count() > 1 ? $outSegments->last()['flightTime'] : 0),
                            'stops' => $outSegments->count(),
                            'flightSegments' => $outSegments
                        ],
                        'inbound' => [
                            'origin' => $inSegments->first()['origin'],
                            'airline' => $inSegments->first()['airline'],
                            'destination' => $inSegments->last()['destination'],
                            'departure' => $inSegments->first()['departure'],
                            'arrival' => $inSegments->last()['arrival'],
                            'totalTimeTakenFromOriginToDestination'=>hoursandmins($inSegments->pluck('flightTime')->sum()),

                            'departureDate' => Carbon::parse($this->convertObjectString($inSegments->first()['departure']))->format('d F,Y'),
                            'departureTime' => Carbon::parse($this->convertObjectString($inSegments->first()['departure']))->format('h:i A'),
                            'arrivalDate' => Carbon::parse($this->convertObjectString($inSegments->last()['arrival']))->format('d F,Y'),
                            'arrivalTime' => Carbon::parse($this->convertObjectString($inSegments->last()['arrival']))->format('h:i A'),

                            'totalFlightTime' => $inSegments->first()['flightTime'] + ($inSegments->count() > 1 ? $inSegments->last()['flightTime'] : 0),
                            'stops' => $inSegments->count(),
                            'flightSegments' => $inSegments
                        ],
                        'totalPrice' => $totalPrice,
                        'currency' => $request['currency'],
                        'price' => $prices
                    ]);

                }
                $flights->push($details[0]);

            }

        }
        return $flights;
    }



}

