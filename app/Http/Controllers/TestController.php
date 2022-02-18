<?php

namespace App\Http\Controllers;

use Tookan\Http\Api;

class TestController extends Controller
{

    public function test(){
        $address = "https://maps.google.com/?q=25.225018,55.25914";
        $axis = explode('=', $address)[1];
        $x_y = explode(',', $axis);
        return $x_y;
    }

    public function getAllTaskPerDay(){
        $allData = [];
        $i = 1;
        $total_page_count = 0;
        
        do{
            $res = $this->fetchData($i++);
            while(!$res->ok());
            $result = $res->json();
            $total_page_count = $result['total_page_count'];
            $records_per_page = $result['records_per_page'];
            if($records_per_page > 0){
                $data = (array)$result['data'];
                $allData = array_merge($allData, $data);
            }

        }while($i <= $total_page_count);

        $success = collect($allData)->where('job_status', '=', 2)->count();
        return $success;
    }



    public function getAllTask(){
         $total_page = 23;
        // $res1 = $this->firstFetchData();
        $allData = [];
        
        
        for($i = 1; $i <= $total_page ; $i++){

            $res = $this->fetchData($i);
            while(!$res->ok());
            $arr = $res->json()['data'];


            foreach($arr as $item){
                array_push($allData, (object)[
                    "job_id" => $item['job_id'],
                    "customer_name" => $item['job_pickup_name'],
                    "phone" => $this->getCustomerPhone($item['job_pickup_phone'])
                ]);
            } 
        }

        return $allData;
    }


    private function getCustomerPhone($phone){
        $res = substr($phone, strpos($phone, "5"));
        return $res;
    }


    private function fetchData($page){
        $requestBody = [
            "api_key" => "5b636183f24a0b44541279701610214218e4c6fc2ad57f375b15",
            "start_date" => "2021-12-06",
            "end_date" => "2021-12-06",
            "is_pagination" => 1,
            "job_type" => [0,1,2,3],
            "team_id" => 1105452,
            "requested_page" => $page,
        ];

        return Api::request('get_all_tasks', $requestBody);
    }
}
