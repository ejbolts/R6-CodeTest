<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.weatherbit.key');
        $this->baseUrl = config('services.weatherbit.url');
    }

    /**
     * Get 5-day forecast 
     *
     * @param string 
     * @return array
     * @throws \Exception
     */
    public function getForecast(string $city): array
    {
        try {
            $response = Http::get("{$this->baseUrl}forecast/daily", [
                'city' => $city,
                'key' => $this->apiKey,
                'days' => 5,
            ]);

            $response->throw(); 

            return $this->formatResponse($response->json());
        } catch (RequestException $e) {
            if ($e->response->status() === 404 || $e->response->status() === 204) {
                 throw new \Exception("Could not find weather data for '{$city}'.");
            }
            throw new \Exception('Failed to retrieve weather data from the provider.');
        }
    }

    /**
     * Format the raw API response into a simple structure.
     *
     * @param array $data
     * @return array
     */
    protected function formatResponse(array $apiResponse): array
    {
        $formattedData = [];
        foreach ($apiResponse['data'] as $day) {
            $formattedData[] = [
                'date' => $day['valid_date'],
                'avg_temp' => round($day['temp']),
                'max_temp' => round($day['max_temp']),
                'min_temp' => round($day['min_temp']),
            ];
        }
        return $formattedData;
    }
}