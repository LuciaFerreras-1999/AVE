<?php
// app/Services/WeatherService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeather($city)
    {
        $apiKey = config('weather.api_key');
        $baseUrl = config('weather.base_url');

        $response = Http::get("{$baseUrl}?q={$city}&appid={$apiKey}&units=metric");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
