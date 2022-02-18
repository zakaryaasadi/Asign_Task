<?php

namespace Tookan\Service;

class GeofenceService{

    public function isExistsGeofenceByJob($job){
        $geofenceDetails = $job['geofence_details'];
        return isset($geofenceDetails) && count($geofenceDetails) > 0;
    }
}