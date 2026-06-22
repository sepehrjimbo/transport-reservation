<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Cities;

class CitySeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(database_path('data/airports.json'));
        $airports = json_decode($json, true);

        foreach ($airports as $code => $airport) {

            if (!isset($airport['cityName']['en'])) {
                continue;
            }

            Cities::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $airport['cityName']['en'],
                    'country' => $airport['country'] ?? null
                ]
            );
        }
    }
}
