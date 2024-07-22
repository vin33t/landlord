<?php

namespace App\Console\Commands;

use App\Models\Airport;
use Illuminate\Console\Command;

class SeedAirportsCommand extends Command
{
    protected $signature = 'airports:seed';

    protected $description = 'Seed Airports from https://raw.githubusercontent.com/jpatokal/openflights/master/data/airports.dat';

    public function handle(): void
    {
        $this->info('Seeding Airports...');

        $fileUrl = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airports.dat';
        $fileContent = file($fileUrl);

        $totalRecords = count($fileContent);

        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        $file = fopen($fileUrl, 'r');

        while (($record = fgetcsv($file)) !== false) {
            Airport::create([
                'name' => $record[1],
                'city' => $record[2] == '\\N' ? null : $record[2],
                'country' => $record[3] == '\\N' ? null : $record[3],
                'iata' => $record[4] == '\\N' ? null : $record[4],
                'icao' => $record[5] == '\\N' ? null : $record[5],
                'latitude' => $record[6] == '\\N' ? null : $record[6],
                'longitude' => $record[7] == '\\N' ? null : $record[7],
                'altitude' => $record[8] == '\\N' ? null : $record[8],
                'timezone' => $record[9] == '\\N' ? null : $record[9],
                'dst' => $record[10] == '\\N' ? null : $record[10],
                'tz_database_timezone' => $record[11] == '\\N' ? null : $record[11],
                'type' => $record[12] == '\\N' ? null : $record[12],
                'source' => $record[13] == '\\N' ? null : $record[13],
            ]);
            $bar->advance();
        }

        fclose($file);

        $bar->finish();

        $this->info('Airports data seeded successfully.');
    }
}
