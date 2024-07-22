<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use vin33t\HotelBooking\Services\TravellandaService;

class TravellandaSeedCommand extends Command
{
    protected $signature = 'travellanda:seed';

    protected $description = 'Command description';

    public function handle(): void
    {
        Country::query()->delete();

        $this->info('Seeding data for Travellanda...');
        $travellandaService = new TravellandaService();
        $this->info('Seeding data for Countries and Cities...');
        $countries = $travellandaService->getCountries();
        $this->info('All countries fetched!');
        foreach ($countries as $country) {
            $this->info('Seeding Cities for ' . $country['country_name']);
            $country = Country::create($country);
            $country->cities()->createMany($travellandaService->getCities($country->country_code));
        }
        $this->info('Seeding completed!');
    }
}
