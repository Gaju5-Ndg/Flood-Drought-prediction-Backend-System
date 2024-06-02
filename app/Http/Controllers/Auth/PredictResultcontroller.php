<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    /**
     * Handle a prediction request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function predict(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'water_level' => 'required|numeric',
            'humidity' => 'required|numeric',
            'temperature' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
        ]);

        // Send sensor data to the prediction API
        $response = Http::post('http://localhost:8001/floods_detector', $validatedData);

        // Check if the request was successful
        if ($response->successful()) {
            // Get the prediction result
            $predictionResult = $response->json();

            // Insert the sensor data and prediction result into the database
            // Assuming you have a 'predictions' table
            $insertedId = \DB::table('predictions')->insertGetId([
                'water_level' => $validatedData['water_level'],
                'humidity' => $validatedData['humidity'],
                'temperature' => $validatedData['temperature'],
                'soil_moisture' => $validatedData['soil_moisture'],
                'prediction' => $predictionResult['prediction'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Data inserted successfully',
                'inserted_id' => $insertedId,
                'prediction' => $predictionResult,
            ]);
        } else {
            // Handle unsuccessful request
            return response()->json(['error' => 'Failed to get prediction from the API'], $response->status());
        }
    }
}
