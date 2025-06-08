<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Schedule;

Artisan::command('forecast {cities?*}', function (WeatherService $weatherService) {
    $cities = $this->argument('cities');

    if (empty($cities)) {
        $cities = [$this->choice(
            'Which city would you like to see a forecast for?',
            ['Brisbane', 'Gold Coast', 'Sunshine Coast'],
            'Brisbane'
        )];
    }

    $this->info('Fetching 5-day weather forecast...');

    foreach ($cities as $city) {
        try {
            $forecast = $weatherService->getForecast($city);

            $headers = ['City', 'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
            $rowData = [$city];

            foreach ($forecast as $day) {
                $rowData[] = sprintf(
                    'Avg: %d, Max: %d, Low: %d',
                    $day['avg_temp'],
                    $day['max_temp'],
                    $day['min_temp']
                );
            }
            $this->table($headers, [$rowData]);

        } catch (\Exception $e) {
            $this->error("Error for {$city}: " . $e->getMessage());
        }
    }
})->purpose('Display a 5-day weather forecast for specified cities.');

Schedule::command(
  'forecast',
  ['Brisbane', 'Gold Coast', 'Sunshine Coast']
)
  ->dailyAt('07:00')
  ->appendOutputTo(storage_path('logs/daily-forecast.log'));