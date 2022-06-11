<?php

namespace App\Http\Traits;

use GuzzleHttp\Client;

trait ApiRequestTrait
{

    public function request($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client();
        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }
        $response = $client->request(
            $method,
            $requestUrl,
            [
                'form_params' => $formParams,
                'headers' => $headers
            ]
        );
        return $response->getBody()->getContents();
    }
}
