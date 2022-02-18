<?php

namespace Tookan\Service;

use Tookan\DefaultValues\Api as DefaultValuesApi;
use Tookan\Http\Api;

class AgentService{

    private $agentsList = [];

    public function getAgentsbyJob($job){
        $geofenceDetails = json_decode($job['geofence_details'], true);
        $this->agentsList = [];

        foreach($geofenceDetails as $i){
            $res = $this->requestViewRegion($i['region_id']);
            
            if($res->ok()){
                $data = $res->json()['data'];
                if(Count($data) > 0)
                    $this->addAgentToList($data[0]['fleets']);
            }
        }

        return $this->agentsList;
    }


    public function getAgentIdsByAgentList($agents){
        return collect($agents)
                ->map(function ($agent){
                    return $agent['fleet_id'];
                });
    }



    private function addAgentToList($agents){
        foreach($agents as $i){
            $isExistsAgentsAtRegion = collect($this->agentsList)
                                        ->where('fleet_id', '=', $i['fleet_id'])
                                        ->count() > 0;
            if(!$isExistsAgentsAtRegion){
                array_push($this->agentsList, $i);
            }
        }
    }




    private function requestViewRegion($region_id){

        $requestBody = array(
            "region_id" => $region_id,
            "user_id" => DefaultValuesApi::user_id
        );

        return Api::request("view_regions", $requestBody);
    }
}