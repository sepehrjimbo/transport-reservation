<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\airlines;

class AirlineSeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(database_path('data/airlines.json'));
        $airlines = json_decode($json, true);

        foreach ($airlines as $code => $airline) {

            if (!isset($airline['name']['en'])) {
                continue;
            }

            airlines::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $airline['name']['en'],
                    'alias' => null,
                    'country' => $airline['country'] ?? null,
                    'active' => true
                ]
            );
        }
    }
}
