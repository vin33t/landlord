<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedAirlinesCommand extends Command
{
    protected $signature = 'airlines:seeder';

    protected $description = 'Seed airlines data into the database';

    public function handle(): void
    {
        $this->info('Seeding Airlines...');

        $fileUrl = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airlines.dat';
        $fileContent = file($fileUrl);

        $totalRecords = count($fileContent);

        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        $file = fopen($fileUrl, 'r');
        while (($record = fgetcsv($file)) !== false) {
            \App\Models\Airline::create([
                'name' => $record[1],
                'alias' => $record[2] == '\\N' ? null : $record[2],
                'iata' => $record[3] == '\\N' ? null : $record[3],
                'icao' => $record[4] == '\\\\' ? null : $record[4],
                'callsign' => $record[5] == '\\N' ? null : $record[5],
                'country' => $record[6] == '\\N' ? null : $record[6],
                'active' => $record[7],
            ]);
            $bar->advance();
        }
        fclose($file);

        $bar->finish();

        $this->info('Airlines data seeded successfully.');
    }
}
