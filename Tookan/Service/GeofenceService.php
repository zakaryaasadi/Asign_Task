<?php

namespace Tookan\Service;
use Illuminate\Support\Facades\Log;


class GeofenceService{

    public function isExistsGeofenceByJob($job){
        Log::channel('custom')->info('call isExistsGeofenceByJob...');

        $result = isset($job['geofence_details']) && count($job['geofence_details']) > 0;
        
        Log::channel('custom')->info('return isExistsGeofenceByJob => '. $result);

        return $result;
    }
}