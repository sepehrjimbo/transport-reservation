<?php

namespace App\Http\Controllers\Flight;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    public function search(Request $request)
    {
        $origin = $request->origin;
        $destination = $request->destination;
        $date = $request->date;

        $flights = DB::table('flights')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->join('cities as origin', 'flights.origin_id', '=', 'origin.id')
            ->join('cities as destination', 'flights.destination_id', '=', 'destination.id')
            ->where('origin.code', $origin)
            ->where('destination.code', $destination)
            ->whereDate('flights.departure', $date)
            ->select(
                'flights.id',
                'flights.flight_number',
                'flights.departure',
                'flights.arrival',
                'flights.price',
                'flights.seats_available',
                'flights.aircraft_type',
                'airlines.name as airline',
                'origin.name as origin_city',
                'destination.name as destination_city'
            )
            ->get();

        return response()->json($flights);
    }
    public function show($id)
    {
        $flight = DB::table('flights')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->join('cities as origin', 'flights.origin_id', '=', 'origin.id')
            ->join('cities as destination', 'flights.destination_id', '=', 'destination.id')
            ->where('flights.id', $id)
            ->select(
                'flights.*',
                'airlines.name as airline',
                'origin.name as origin_city',
                'destination.name as destination_city'
            )
            ->first();

        if (!$flight) {
            return response()->json(['message' => 'Flight not found'], 404);
        }

        return response()->json($flight);
    }

}
