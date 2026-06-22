<?php
namespace App\Http\Controllers\Trains;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    /**
     * Get flight detail
     *
     * @group Flights
     */
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

        $wagons = DB::table('train_details')
            ->where('train_id', $id)
            ->get();

        return response()->json([
            'train' => $train,
            'wagons' => $wagons
        ]);
    }
    /**
     * Search train by iata
     *
     * @group trains
     *
     * @queryParam iata string required Example: DXB-AUH
     */
    public function searchByIata(Request $request)
    {
        $iata = $request->query('iata');

        if (!$iata || !str_contains($iata, '-')) {
            return response()->json([
                'message' => 'Invalid iata format. Example: THR-MHD'
            ], 422);
        }

        [$originCode, $destinationCode] = explode('-', $iata);

        $originCode = strtoupper(trim($originCode));
        $destinationCode = strtoupper(trim($destinationCode));

        $trains = DB::table('trains')
            ->join('cities as origin', 'trains.origin_id', '=', 'origin.id')
            ->join('cities as destination', 'trains.destination_id', '=', 'destination.id')
            ->where('origin.code', $originCode)
            ->where('destination.code', $destinationCode)
            ->select(
                'trains.id',
                'trains.code',
                'trains.name',
                'trains.type',
                'trains.departure_time',
                'trains.arrival_time',
                'trains.base_price',
                'origin.code as origin_code',
                'origin.name as origin_city',
                'destination.code as destination_code',
                'destination.name as destination_city'
            )
            ->get();

        return response()->json([
            'iata' => $originCode . '-' . $destinationCode,
            'count' => $trains->count(),
            'data' => $trains
        ]);
    }

}
