<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tookan\Dependency\Singleton;
use Tookan\Http\Api;
use Tookan\Service\QueueService;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
  private $queueService;
  private $body;

  public function __construct()
  {
    $this->queueService = Singleton::Create(QueueService::class);
  }

    public function createTask(Request $request){
      $str = $request->getContent();
      Log::channel('custom')->info('call createTask => '. json_encode($str));

      $this->body = json_decode( $str, true);
      $this->body['geofence'] = 1;
      $this->isURLAddress();
      $res = Api::request('create_task', $this->body);
      if($res->ok()){
        $job = $res->json()['data'];
        $job['job_pickup_datetime'] = $this->body['job_pickup_datetime'];
        $this->queueService->addToQueue($job);
      }

      Log::channel('custom')->info('return createTask => '. json_encode($res->json()));

      return $res->json();
    }


    private function isURLAddress(){
      $address = $this->body['job_pickup_address'];
      if (filter_var($address, FILTER_VALIDATE_URL)) { 
        $axis = explode('=', $address)[1];
        $point = explode(',', $axis);
        $this->body['job_pickup_latitude'] = $point[0]; 
        $this->body['job_pickup_longitude'] = $point[1]; 
      }
    }


}


