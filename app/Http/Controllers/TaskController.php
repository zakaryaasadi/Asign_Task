<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tookan\Dependency\Singleton;
use Tookan\Http\Api;
use Tookan\Service\QueueService;

class TaskController extends Controller
{
  private $queueService;

  public function __construct()
  {
    $this->queueService = Singleton::Create(QueueService::class);
  }

    public function createTask(Request $request){

      $str = $request->getContent();
      $body = json_decode( $str, true);
      $body['geofence'] = 1;
      $res = Api::request('create_task', $body);
      if($res->getStatusCode() == 200){
        $job = Api::getResponseAsArray($res)['data'];
        $job['job_pickup_datetime'] = $body['job_pickup_datetime'];
        $this->queueService->addToQueue($job);
      }

      return Api::getResponseAsArray($res);
 
    }


}


