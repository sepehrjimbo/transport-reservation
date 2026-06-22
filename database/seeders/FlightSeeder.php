<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/flights.json');

        if (!File::exists($path)) {
            $this->command->error('flights.json not found.');
            return;
        }

        $flights = json_decode(File::get($path), true);

        if (!is_array($flights)) {
            $this->command->error('Invalid flights.json format.');
            return;
        }

        foreach ($flights as $flight) {

            $airline = DB::table('airlines')
                ->where('code', $flight['airline_code'])
                ->first();

            $origin = DB::table('cities')
                ->where('code', $flight['origin_code'])
                ->first();

            $destination = DB::table('cities')
                ->where('code', $flight['destination_code'])
                ->first();

            if (!$airline || !$origin || !$destination) {
                $this->command->warn("Skipping flight {$flight['flight_number']} بسبب missing foreign key.");
                continue;
            }

            DB::table('flights')->insert([
                'flight_number' => $flight['flight_number'],
                'airline_id' => $airline->id,
                'origin_id' => $origin->id,
                'destination_id' => $destination->id,
                'departure' => $flight['departure'],
                'arrival' => $flight['arrival'],
                'price' => $flight['price'],
                'seats_available' => $flight['seats_available'],
                'aircraft_type' => $flight['aircraft_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Flights seeded successfully.');
    }
}
