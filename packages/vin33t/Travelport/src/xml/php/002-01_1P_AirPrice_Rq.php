<?php

$message = '
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
           <air:AirPriceReq AuthorizedBy="'.$user.'" TargetBranch="'.$target_branch.'" TraceId="'.$trace_id.'" FareRuleType="long" xmlns:air="http://www.Travelport.com/schema/air_v42_0">
              <BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.Travelport.com/schema/common_v42_0"/>
              <air:AirItinerary>';
foreach($segment_data['flightSegments'] as $segment){
    $message .= '<air:AirSegment ArrivalTime="'.$segment['arrival'].'" AvailabilitySource="'.$segment['availabilitySource'].'" Carrier="'.$segment['airline'].'" ChangeOfPlane="'.$segment['changeOfPlane'].'" DepartureTime="'.$segment['departure'].'" Destination="'.$segment['destination'].'" Distance="'.$segment['distance'].'" ETicketability="'.$segment['eTicketability'].'" Equipment="'.$segment['equipment'].'" FlightNumber="'.$segment['flight'].'" FlightTime="'.$segment['flightTime'].'" Group="'.$segment['group'].'" Key="'.$segment['key'].'" OptionalServicesIndicator="'.$segment['optionalServicesIndicator'].'" Origin="'.$segment['origin'].'" ParticipantLevel="'.$segment['participantLevel'].'" ProviderCode="1G"/>';
}
$passengers = '';
$travellerCount = 0;
while($segment_data['adult'] > $travellerCount){
    $passengers .= '<com:SearchPassenger xmlns:com="http://www.Travelport.com/schema/common_v42_0" BookingTravelerRef="'.uniqid().'" Code="ADT"/>';
    $travellerCount++;
}
$travellerCount = 0;

while($segment_data['children'] > $travellerCount){
    $passengers .= '<com:SearchPassenger xmlns:com="http://www.Travelport.com/schema/common_v42_0" BookingTravelerRef="'.uniqid().'" Code="CNN"/>';
    $travellerCount++;
}
$travellerCount = 0;
while($segment_data['infant'] > $travellerCount){
    $passengers .= '<com:SearchPassenger xmlns:com="http://www.Travelport.com/schema/common_v42_0" BookingTravelerRef="'.uniqid().'" Code="INF"/>';
    $travellerCount++;
}
             $message .= '</air:AirItinerary>
              <air:AirPricingModifiers FaresIndicator="PublicFaresOnly" CurrencyType="GBP">
            <air:BrandModifiers ModifierType="FareFamilyDisplay" />
            </air:AirPricingModifiers>;
            '.$passengers.'
              <air:AirPricingCommand/>
           </air:AirPriceReq>
        </soap:Body>
     </soap:Envelope>
';



//pr($message);die;
