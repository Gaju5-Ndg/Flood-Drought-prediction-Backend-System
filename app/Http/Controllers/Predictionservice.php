<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PredictionController extends Controller
{
    public function getPrediction(Request $request)
    {
        $client = new Client();
        $response = $client->post('http://localhost:8001/floods_detector', [
            'json' => [ 
                'waterlevel' => $request->input('waterlevel'),
                'humidity' => $request->input('humidity'),
                'temperature' => $request->input('temperature'),
                'soilmoisture' => $request->input('soilmoisture'),
            ]
        ]);

        $prediction = json_decode($response->getBody(), true);
        return response()->json($prediction);
    
    }
}