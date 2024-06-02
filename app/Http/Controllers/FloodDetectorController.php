<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FloodDetectorController extends Controller
{
    public function detectFlood(Request $request)
    {
        $validated = $request->validate([
            'WaterLevel' => 'required|numeric',
            'Humidity' => 'required|numeric',
            'Temperature' => 'required|numeric',
            'SoilMoisture' => 'required|numeric',
        ]);
        $response = Http::post('http://localhost:8000/floods_detector', [
            'WaterLevel' => $validated['WaterLevel'],
            'Humidity' => $validated['Humidity'],
            'Temperature' => $validated['Temperature'],
            'SoilMoisture' => $validated['SoilMoisture'],
        ]);
        if ($response->successful()) {
            return response()->json([
                'message' => 'Prediction successful',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to get prediction',
                'error' => $response->body(),
            ], $response->status());
        }
    }
}
