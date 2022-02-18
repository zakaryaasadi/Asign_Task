<?php

namespace Tookan\Service;
use Illuminate\Support\Facades\Log;


class GeofenceService{

    public function isExistsGeofenceByJob($job){
        Log::channel('custom')->info('call isExistsGeofenceByJob => ' . json_encode($job));
        $geofenceDetails = $job['geofence_details'];
        return isset($geofenceDetails) && count($geofenceDetails) > 0;
    }
}