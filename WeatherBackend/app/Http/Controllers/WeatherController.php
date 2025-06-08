<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\WeatherService;
use Illuminate\Routing\Controller; 

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function show(string $city): JsonResponse
    {
        // A simple validation 
        $validCities = ['Brisbane', 'Gold Coast', 'Sunshine Coast'];
        if (!in_array($city, $validCities)) {
            return response()->json(['error' => 'Invalid city provided.'], 400);
        }

        try {
            $forecast = $this->weatherService->getForecast($city);
            return response()->json($forecast);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
