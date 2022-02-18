<?php

namespace Tookan\Service;

use App\Models\Queue;
use Tookan\Dependency\Singleton;
use Tookan\DefaultValues\Api as DefaultValuesApi;
use Illuminate\Support\Facades\Log;


class QueueService{
    private $isProcessing;

    private $geofenceService;
    private $agentService;
    private $taskService;


    public function __construct()
    {
        $this->isProcessing = false;
        $this->geofenceService = Singleton::Create(GeofenceService::class);
        $this->agentService = Singleton::Create(AgentService::class);
        $this->taskService = Singleton::Create(TaskService::class);
    }


    public function isProcessing(){
        return $this->isProcessing;
    }


    public function addToQueue($job){
        Log::channel('custom')->info('call addToQueue => '. json_encode($job));
        if(!$this->geofenceService->isExistsGeofenceByJob($job)){
            return;
        }
        $job['geofence_details'] = json_encode($job['geofence_details']);
        Queue::Create($job);
    }


    public function run(){
        Log::channel('custom')->info('call run queue');
        $this->isProcessing = true;
        $jobs = Queue::get();
        Log::channel('custom')->info('Number of jobs is ' . Queue::count());
        foreach($jobs as $job){
            $this->processing($job);
            $job->delete();
        }
        $this->isProcessing = false;
        Log::channel('custom')->info('return run queue');
    }


    private function processing($job){ 
        Log::channel('custom')->info('call processing => ' . json_encode($job));           
        $agents = $this->agentService->getAgentsbyJob($job);
        if(!isset($agents) || count($agents) == 0){
            return;
        }

        $dateTime =  $job['job_pickup_datetime'];
        $startdate = $this->skipWeekend(strtotime(date($dateTime)));
        $enddate=strtotime("+10 days", $startdate);


        while($startdate < $enddate){

            $agentIds = $this->agentService->getAgentIdsByAgentList($agents);

            $tasksAgentByDay = $this->taskService->getTasksByAgentIds($agentIds, $startdate);

            $bestAgentId = $this->taskService->getFleetIdByMinNumberOfTasks($tasksAgentByDay);

            if(count($tasksAgentByDay[$bestAgentId]) <= DefaultValuesApi::number_of_tasks_per_day){

                $res_edit_date_task = $this->taskService->requestEditDateTask($job['job_id'], $startdate);

                if($res_edit_date_task->ok()){
                    $this->taskService->requestAssignAgentToTask($bestAgentId, $job['job_id']);
                    Log::channel('custom')->info('return requestAssignAgentToTask => Best Agent Id = '. $bestAgentId . ' Job id = ' . $job['job_id']);           
                    break;
                }

            }else{
                $startdate = $this->skipWeekend(strtotime("+1 day", $startdate));
            }
        }

        Log::channel('custom')->info('return processing ');           

    }

    private function skipWeekend($date){
        $day = strtolower(date('l', $date));
        if($day == 'saturday'){
            $date = strtotime("+1 day", $date);
        }

        return $date;
    }

}