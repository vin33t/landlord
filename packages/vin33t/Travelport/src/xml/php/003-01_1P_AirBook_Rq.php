<?php
//$message = '';
//foreach($segment_data['flightSegments'] as $segment){
//    $message .= '<air:AirSegment ArrivalTime="'.$segment['arrival'].'" AvailabilitySource="'.$segment['availabilitySource'].'" Carrier="'.$segment['airline'].'" ChangeOfPlane="'.$segment['changeOfPlane'].'" DepartureTime="'.$segment['departure'].'" Destination="'.$segment['destination'].'" Distance="'.$segment['distance'].'" ETicketability="'.$segment['eTicketability'].'" Equipment="'.$segment['equipment'].'" FlightNumber="'.$segment['flight'].'" FlightTime="'.$segment['flightTime'].'" Group="'.$segment['group'].'" Key="'.$segment['key'].'" OptionalServicesIndicator="'.$segment['optionalServicesIndicator'].'" Origin="'.$segment['origin'].'" ParticipantLevel="'.$segment['participantLevel'].'" ProviderCode="1G"/>';
//}
$message = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
 <univ:AirCreateReservationReq xmlns="http://www.Travelport.com/schema/universal_v42_0" xmlns:univ="http://www.Travelport.com/schema/universal_v42_0" xmlns:com="http://www.Travelport.com/schema/common_v42_0" xmlns:air="http://www.Travelport.com/schema/air_v42_0" TraceId="eba4083a-9162-4720-b4b3-52f52836a45c" AuthorizedBy="Travelport" TargetBranch="P3710270" ProviderCode="1G" RetainReservation="Both">
<BillingPointOfSaleInfo xmlns="http://www.Travelport.com/schema/common_v42_0" OriginApplication="UAPI"/>
<BookingTraveler xmlns="http://www.Travelport.com/schema/common_v42_0" Key="b1Fla0ZCTXZXZ0ZzdDdFaw==" TravelerType="ADT" Age="40" DOB="1983-05-12" Gender="M" Nationality="US">
<BookingTravelerName Prefix="Mr" First="Vineet" Last="Chauhan"/>
<DeliveryInfo>
<ShippingAddress Key="b1Fla0ZCTXZXZ0ZzdDdFaw==">
<Street>Via Augusta 59 5</Street>
<City>Madrid</City>
<State>IA</State>
<PostalCode>50156</PostalCode>
<Country>US</Country>
</ShippingAddress>
</DeliveryInfo>
<PhoneNumber Location="DEN" CountryCode="1" AreaCode="303" Number="123456789"/>
<Email EmailID="johnsmith@travelportuniversalapidemo.com"/>
<SSR Type="DOCS" FreeText="P/GB/S12345678/GB/20JUL76/M/01JAN16/SMITH/JOHN" Carrier="LH"/>
<Address>
<AddressName>DemoSiteAddress</AddressName>
<Street>Via Augusta 59 5</Street>
<City>Madrid</City>
<State>IA</State>
<PostalCode>50156</PostalCode>
<Country>US</Country>
</Address>
</BookingTraveler>
<FormOfPayment xmlns="http://www.Travelport.com/schema/common_v42_0" Type="Check" Key="1">
<Check RoutingNumber="456" AccountNumber="7890" CheckNumber="1234567"/>
</FormOfPayment>
 <air:AirPricingSolution Key="R9L5Iko3nDKA4HZ3aOAAAA==" TotalPrice="GBP80.50" BasePrice="INR6770" ApproximateTotalPrice="GBP80.50" ApproximateBasePrice="GBP65.00" Taxes="GBP15.50" Fees="GBP0.00" ApproximateTaxes="GBP15.50" QuoteDate="2023-07-05" xmlns:air="http://www.Travelport.com/schema/air_v42_0"> ◀
                    <air:AirSegment Key="R9L5Iko3nDKA2HZ3aOAAAA==" Group="0" Carrier="UK" FlightNumber="652" ProviderCode="1G" Origin="IXC" Destination="BOM" DepartureTime="2023-07-20T19:45:00.000+05:30" ArrivalTime="2023-07-20T22:00:00.000+05:30" FlightTime="135" TravelTime="135" Distance="837" ClassOfService="V" Equipment="E90" ChangeOfPlane="false" OptionalServicesIndicator="false" AvailabilitySource="S" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="O and D cache or polled status used with different local status" AvailabilityDisplayType="Fare Specific Fare Quote Unbooked"> ◀
                <air:CodeshareInfo OperatingCarrier="UK"></air:CodeshareInfo>
                <air:FlightDetails Key="" Origin="IXC" Destination="BOM" DepartureTime="2023-07-20T19:45:00.000+05:30" ArrivalTime="2023-07-20T22:00:00.000+05:30" FlightTime="135" TravelTime="135" Distance="837"/> ◀
                </air:AirSegment>
                <air:AirPricingInfo PricingMethod="Auto" Key="R9L5Iko3nDKA6HZ3aOAAAA==" TotalPrice="GBP80.50" BasePrice="INR6770" ApproximateTotalPrice="GBP80.50" ApproximateBasePrice="GBP65.00" Taxes="GBP15.50" ProviderCode="1G"> ◀
                                <air:FareInfo PromotionalFare="false" Key="R9L5Iko3nDKA/HZ3aOAAAA==" FareFamily="Economy Saver" DepartureDate="2023-07-20" Amount="GBP65.00" EffectiveDate="2023-07-05T10:47:00.000+01:00" Destination="BOM" Origin="IXC" PassengerTypeCode="ADT" FareBasis="VL15PYS"> ◀
                                    <air:FareRuleKey FareInfoRef="R9L5Iko3nDKA/HZ3aOAAAA==" ProviderCode="1G">6UUVoSldxwgnxId4FtcNjsbKj3F8T9EyxsqPcXxP0TLGyo9xfE/RMsuWFfXVd1OAly5qxZ3qLwOXLmrFneovA5cuasWd6i8Dly5qxZ3qLwOXLmrFneovAxSSc2iZATDNNAF/izIfuYdfHMK8e3nzhlVSSeJJY725IMoiLGMnoEdSD5QULEHOHRSn18m5ITpoFwl2rcLWRLFuCSMnv78jjoX+hNfHEXoxUV3b2b+cmv9gFHVWNccKgVF3c9pD+z3Cs85PTXW5CIthdVO31nopIIMN1QPqv5KjoiIdWw6waMMf5iZ8HgtbZJffswFYiUhRy5YV9dV3U4CXLmrFneovA5cuasWd6i8Dly5qxZ3qLwOXLmrFneovAzzD4Wdjal2fHna1H5QsLdGaNi6zOfbTtY770mJHGJ8M5t3ArRIsOp+z1O6htB9YedwXtO9a1rEPW0jZJDMGvkI=</air:FareRuleKey> ◀
                                </air:FareInfo>
                                <air:BookingInfo BookingCode="V" CabinClass="Economy" FareInfoRef="R9L5Iko3nDKA/HZ3aOAAAA==" SegmentRef="R9L5Iko3nDKA2HZ3aOAAAA==" HostTokenRef="R9L5Iko3nDKA5HZ3aOAAAA==" /> ◀
                                <air:PassengerType Code="ADT" BookingTravelerRef="b1Fla0ZCTXZXZ0ZzdDdFaw==" /><air:AirPricingModifiers FaresIndicator="PublicFaresOnly" CurrencyType="GBP"> ◀
            <air:BrandModifiers ModifierType="FareFamilyDisplay" />
            </air:AirPricingModifiers></air:AirPricingInfo><HostToken Key="R9L5Iko3nDKA5HZ3aOAAAA==" xmlns="http://www.Travelport.com/schema/common_v42_0">GFB10101ADT00  01VL15PYS        YS                     010001#GFB200010101NADTV3302IN100020000499I#GFMCEAP302NIN10 UK ADTVL15PYS</HostToken> ◀
                </air:AirPricingSolution>
<ActionStatus xmlns="http://www.Travelport.com/schema/common_v42_0" Type="ACTIVE" TicketDate="T*" ProviderCode="1G"/>
<Payment xmlns="http://www.Travelport.com/schema/common_v42_0" Key="2" Type="Itinerary" FormOfPaymentRef="1" Amount="GBP108.67"/>
</univ:AirCreateReservationReq>
        </soap:Body>
    </soap:Envelope>';
