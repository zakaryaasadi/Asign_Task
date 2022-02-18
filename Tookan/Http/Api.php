<?php

namespace Tookan\Http;
use Illuminate\Support\Facades\Http;


use Tookan\DefaultValues\Api as DefaultValuesApi;


class Api{

    public static function request($methodName, $body){
        $body['api_key'] = DefaultValuesApi::apiKey;
        $res = Http::post(DefaultValuesApi::baseUrlTookanApi  . $methodName, $body);
        return $res;
    }
}