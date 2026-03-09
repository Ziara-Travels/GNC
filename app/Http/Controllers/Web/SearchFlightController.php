<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ExchangeRateController;

class SearchFlightController extends Controller
{
    public function __invoke () {

        $airports = Airport::pluck('name', 'iata_code')->toArray();
        $exchangeService = new ExchangeRateController(new Client());
        $exchange_rate = $exchangeService->getUsdToKesRate();
        return view('search')->with('exchange_rate', $exchange_rate)->with('currency', 'KES')->with(['flights' => $flights, 'airports' => $airports]);
    }

    public function showPrice(Request $request)
{
    try {
        $data = json_decode($request->flight);
        
        // Format duration from PT2H30M format
        $rawDuration = $data->flightOffers[0]->itineraries[0]->duration ?? null;
        $duration = 'N/A';
        
        if ($rawDuration) {
            // Remove PT prefix and convert to readable format
            $duration = preg_replace(['/PT/', '/H/', '/M/'], ['', 'h ', 'm'], $rawDuration);
        }

        return view('price', [
            'data' => $data,
            'duration' => $duration
        ]);
        
    } catch (\Exception $e) {
        return back()->with('error', 'Unable to process flight details.');
    }
}

 public function index()
    {
        return view('flights.search');
    }


}
