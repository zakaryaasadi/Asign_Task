<?php

namespace App\Http\Controllers;

use Tookan\Http\Api;

class TestController extends Controller
{
    var $res;


    public function test(){
        $fields = array (
            'api_key' => '2b997be77e2cc22becfd4c66426ef504',
            'order_id' => '654321',
            'job_description' => 'groceries delivery',
            'job_pickup_phone' => '+1201555555',
            'job_pickup_name' => '7 Eleven Store',
            'job_pickup_email' => '',
            'job_pickup_address' => '114, sansome street, San Francisco',
            'job_pickup_latitude' => '30.7188978',
            'job_pickup_longitude' => '76.810296',
            'job_pickup_datetime' => '2016-08-14 19:00:00',
            'pickup_custom_field_template' => 'Template_1',
            'pickup_meta_data' => 
            array (
              0 => 
              array (
                'label' => 'Price',
                'data' => '100',
              ),
              1 => 
              array (
                'label' => 'Quantity',
                'data' => '100',
              ),
            ),
        );

        return json_encode($fields, true);
    }


    public function getAllTaskPerDay(){
        $allData = [];
        $i = 1;
        $total_page_count = 0;
        
        do{
            $this->res = $this->fetchData($i++);
            while($this->res->getStatusCode() != 200);
            $result = Api::getResponseAsArray($this->res);
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

            $this->res = $this->fetchData($i);
            while($this->res->getStatusCode() != 200);
            $arr = Api::getResponseAsArray($this->res)['data'];


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
