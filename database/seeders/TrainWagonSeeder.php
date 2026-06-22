<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TrainWagonSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/train_details.json');

        if (!File::exists($path)) {
            $this->command->error('train_wagons.json not found.');
            return;
        }

        $data = json_decode(File::get($path), true);

        if (!is_array($data)) {
            $this->command->error('Invalid train_wagons.json format.');
            return;
        }

        foreach ($data as $trainCode => $trainData) {

            $train = DB::table('trains')
                ->where('code', $trainCode)
                ->first();

            if (!$train) {
                $this->command->warn("Train {$trainCode} not found. Skipping wagons.");
                continue;
            }

            foreach ($trainData['wagons'] as $wagon) {

                DB::table('train_details')->insert([
                    'train_id' => $train->id,
                    'wagon_number' => $wagon['number'],
                    'wagon_type' => $wagon['type'],
                    'capacity' => $wagon['capacity'],
                    'price_multiplier' => $wagon['price_multiplier'],
                    'description' => $wagon['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Train wagons seeded successfully.');
    }
}
