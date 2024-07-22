<?php
/*
SNN65 - restricted senior age (65 and older)
DP30 - 30 percent discount off of the base fare.
ADT: adult
CHD: child
INF: infant without a seat
INS: infant with a seat
UNN: unaccompanied child
*/
//dd($routesArr['route']);
$message = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <air:LowFareSearchReq MaxResults="2" TraceId="'.$trace_id.'" AuthorizedBy="'.$user.'" SolutionResult="true" TargetBranch="'.$target_branch.'" xmlns:air="http://www.Travelport.com/schema/air_v42_0" xmlns:com="http://www.Travelport.com/schema/common_v42_0">
      <com:BillingPointOfSaleInfo OriginApplication="UAPI" />';
foreach($routesArr['route'] as $route){
    $message .=     '<air:SearchAirLeg>
        <air:SearchOrigin>
          <com:Airport Code="'. $route['origin'].'" />
        </air:SearchOrigin>
        <air:SearchDestination>
          <com:Airport Code="'.$route['destination'].'" />
        </air:SearchDestination>
        <air:SearchDepTime PreferredTime="'.$route['deptime'] .'"></air:SearchDepTime>
        <air:AirLegModifiers>
              <air:PreferredCabins>
              <CabinClass xmlns="http://www.Travelport.com/schema/common_v42_0" Type="'.$routesArr['cabinClass'].'"></CabinClass>
              </air:PreferredCabins></air:AirLegModifiers>
      </air:SearchAirLeg>';
}
$message .= '
      <air:AirSearchModifiers>
        <air:PreferredProviders>
          <com:Provider Code="1G" />
        </air:PreferredProviders>
      </air:AirSearchModifiers>
      <com:SearchPassenger BookingTravelerRef="ADT1" Code="ADT" xmlns:com="http://www.Travelport.com/schema/common_v42_0" />
      <com:SearchPassenger BookingTravelerRef="CNN0" Code="CNN" xmlns:com="http://www.Travelport.com/schema/common_v42_0" />
      <com:SearchPassenger BookingTravelerRef="INF0" Code="INF" xmlns:com="http://www.Travelport.com/schema/common_v42_0" />
      <air:AirPricingModifiers FaresIndicator="PublicFaresOnly" CurrencyType="GBP">
        <air:AccountCodes>
          <AccountCode xmlns="http://www.Travelport.com/schema/common_v42_0" Code="-" />
        </air:AccountCodes>
      </air:AirPricingModifiers>
    </air:LowFareSearchReq>
  </soapenv:Body>
</soapenv:Envelope>';

