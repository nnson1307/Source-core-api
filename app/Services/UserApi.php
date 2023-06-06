<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class UserApi
{
    public function getUser($input = [])
    {
        $url = config('constants.API_SERVICE_URL') . '/api/users';

        $response = Http::timeout(333243234234234233)->get($url);

        $statusCode = $response->status();

        dd($response);

        if ($statusCode == 200) {
            $responseBody = json_decode($response->getBody(), true);
            $data = $responseBody;
        }
    }
}
