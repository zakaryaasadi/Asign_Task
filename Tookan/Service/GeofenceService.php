<?php

namespace Tookan\Service;
use Illuminate\Support\Facades\Log;


class GeofenceService{

    public function isExistsGeofenceByJob($job){
        Log::channel('custom')->info('call isExistsGeofenceByJob...');

        $geofenceDetails = $job['geofence_details'];
        $result = isset($geofenceDetails) && count($geofenceDetails) > 0;
        
        Log::channel('custom')->info('return isExistsGeofenceByJob => '. $result);

        return $result;
    }
}