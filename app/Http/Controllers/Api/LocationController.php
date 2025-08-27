<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
  public function getCountries()
  {
    $apiKey = config('country.api_key');
    $apiUrl = "https://api.countrystatecity.in/v1/countries";

    $response = Http::withHeaders([
      'X-CSCAPI-KEY' => $apiKey
    ])->get($apiUrl);

    return response()->json($response->json());
  }

  public function getStates($iso2)
  {
    $apiKey = config('country.api_key');
    $apiUrl = "https://api.countrystatecity.in/v1/countries/{$iso2}/states";

    $response = Http::withHeaders([
      'X-CSCAPI-KEY' => $apiKey
    ])->get($apiUrl);

    if ($response->failed()) {
      return response()->json([
        'error' => 'Failed to fetch states',
        'status' => $response->status(),
        'message' => $response->body()
      ], $response->status());
    }

    // Decode JSON response
    $states = $response->json();

    // Sort states alphabetically by name
    usort($states, function ($a, $b) {
      return strcmp($a['name'], $b['name']);
    });

    return response()->json($states);
  }
}
