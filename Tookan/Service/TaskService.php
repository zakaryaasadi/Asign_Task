<?php


namespace Tookan\Service;

use Tookan\Http\Api;
use Tookan\DefaultValues\JobType;
use Tookan\DefaultValues\TaskStatus;
use Tookan\DefaultValues\Api as DefaultValueApi;

class TaskService{

    public function getTasksByAgentIds($agent_ids, $day){
        $arr = [];
        foreach($agent_ids as $i){
            $arr[$i] = $this->getTasksByAgentId($i, $day);
        }

        return $arr;
    }

    private function getTasksByAgentId($agent_id, $day){

        $date = date("Y-m-d", $day);

        $requestBody = array(
            "job_status" => [TaskStatus::Assigned, TaskStatus::InProgress_Arrived, 
                            TaskStatus::Accepted_Acknowledged, TaskStatus::Started,
                            TaskStatus::Failed, TaskStatus::Cancel, 
                            TaskStatus::Decline, TaskStatus::Successful],
            "start_date" => $date,
            "end_date" => $date,
            "fleet_id" => $agent_id
        );

        $res = $this->requestViewTasks($requestBody);
        if($res->ok()){
            $data = $res->json()['data'];
            return $data;
        }
        return [];
    }


    public function getFleetIdByMinNumberOfTasks($list){

        reset($list);


        $fleet_id = key($list);
        $min =  PHP_INT_MAX;

        foreach($list as $key => $tasks){
            $taskCount = count($tasks);
            if($taskCount < $min){
                $fleet_id = $key; 
                $min = $taskCount;
            }
        }

        return $fleet_id;
    }


    public function requestAssignAgentToTask($fleet_id, $job_id){
        $requestBody = [
            "job_id" => $job_id,
            "fleet_id" => $fleet_id
        ];

        return Api::request('assign_task', $requestBody);

    }


    public function requestEditDateTask($job_id, $d){
        $date = date("Y-m-d H:i:s", $d);
        $requestBody = [
            "job_ids" => [$job_id],
            "layout_type" => 0,
            "start_time" => $date,
            "end_time" => $date
            ];

        return Api::request('change_job_date', $requestBody);
    }

    private function requestViewTasks($requestBody){
        if(!isset($requestBody['job_type'])){
            $requestBody['job_type'] = [JobType::PickUp, JobType::Delivery, JobType::Appointment, JobType::FOS];
        }
        
        return Api::request("get_all_tasks", $requestBody);
    }
}