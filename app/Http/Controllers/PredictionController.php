<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station_Data;
use Illuminate\Support\Facades\Storage;

class PredictionController extends Controller
{
    public function predict(Request $request)
    {
        // Load the machine learning model
        $model = joblib_load(Storage::path('linear_regression_model.pkl'));

        // Get the array of station IDs from the request
        $stationIDs = $request->station_ids;

        // Initialize an array to store prediction results for each station
        $predictions = [];

        // Loop through each station ID
        foreach ($stationIDs as $stationID) {
            // Retrieve the latest station data for the current station ID
            $stationData = Station_Data::where('station_id', $stationID)->latest()->first();

            // Check if station data is available
            if (!$stationData) {
                // If station data is not found, store an error message for the current station ID
                $predictions[$stationID] = ['error' => 'Station data not found'];
                continue; // Skip to the next station ID
            }

            // Extract features from the retrieved data
            $features = [
                'water_level' => $stationData->water_level,
                'temperature' => $stationData->temperature,
                'humidity' => $stationData->humidity,
                'soil_moisture' => $stationData->soil_moisture,
            ];

            // Perform prediction using the model
            $prediction = $model->predict([$features]);

            // Store the prediction result for the current station ID
            $predictions[$stationID] = $prediction;
        }

        // Return the prediction results for all stations
        return view('predictions', ['predictions' => $predictions]);
    }
}
