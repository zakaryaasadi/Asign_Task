<?php

namespace Tookan\Http;

use GuzzleHttp\Client;
use Tookan\DefaultValues\Api as DefaultValuesApi;


class Api{

    public static function request($methodName, $body){
        $client = new Client();
        $body['api_key'] = DefaultValuesApi::apiKey;
        $params['form_params'] = $body;
        $res = $client->post(DefaultValuesApi::baseUrlTookanApi . $methodName, $params);
        return $res;
    }

    public static function getResponseAsArray($response){
        return json_decode($response->getBody(), true);
    }
}