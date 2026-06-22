<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TrainSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/trains.json');

        if (!File::exists($path)) {
            $this->command->error('trains.json not found.');
            return;
        }

        $trains = json_decode(File::get($path), true);

        if (!is_array($trains)) {
            $this->command->error('Invalid trains.json format.');
            return;
        }

        foreach ($trains as $train) {

            $origin = DB::table('cities')
                ->where('code', $train['origin_code'])
                ->first();

            $destination = DB::table('cities')
                ->where('code', $train['destination_code'])
                ->first();

            if (!$origin || !$destination) {
                $this->command->warn("Skipping train {$train['code']} بسبب missing city.");
                continue;
            }

            DB::table('trains')->insert([
                'code' => $train['code'],
                'name' => $train['name'],
                'type' => $train['type'],
                'iata_code' => $train['yata_code'],
                'origin_id' => $origin->id,
                'destination_id' => $destination->id,
                'departure_time' => $train['departure_time'],
                'arrival_time' => $train['arrival_time'],
                'base_price' => $train['base_price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Trains seeded successfully.');
    }
}
