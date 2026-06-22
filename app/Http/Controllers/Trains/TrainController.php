<?php
namespace App\Http\Controllers\Trains;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TrainController extends Controller
{
    public function show($id)
    {
        $train = DB::table('trains')
            ->join('cities as origin', 'trains.origin_id', '=', 'origin.id')
            ->join('cities as destination', 'trains.destination_id', '=', 'destination.id')
            ->where('trains.id', $id)
            ->select(
                'trains.*',
                'origin.name as origin_city',
                'destination.name as destination_city'
            )
            ->first();

        if (!$train) {
            return response()->json(['message' => 'Train not found'], 404);
        }

        $wagons = DB::table('train_wagons')
            ->where('train_id', $id)
            ->get();

        return response()->json([
            'train' => $train,
            'wagons' => $wagons
        ]);
    }
}
