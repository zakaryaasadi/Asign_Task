<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use Tookan\Http\Api;


class DedController extends Controller
{
    
    var $token = "eyJhbGciOiJSUzI1NiIsImtpZCI6IjQ2N0E5NTcwMzU5M0Q0RDVEN0Y1QzlDRjE5NjdBRjI5NDMzQ0ZFMDEiLCJ0eXAiOiJhdCtqd3QiLCJ4NXQiOiJSbnFWY0RXVDFOWFg5Y25QR1dldktVTThfZ0UifQ.eyJuYmYiOjE2NDM3ODkxNjIsImV4cCI6MTY0Mzc5MDA2MiwiaXNzIjoiaHR0cHM6Ly9pZGVudGl0eS5kdWJhaWRlZC5nb3YuYWUiLCJhdWQiOiJSYWZmbGVzX1JldGFpbGVyQVBJIiwiY2xpZW50X2lkIjoiUmFmZmxlX1JldGFpbGVyQ2xpZW50Iiwic2NvcGUiOlsiUmFmZmxlX1JldGFpbGVyU2NvcGUiXX0.C8tynpiHnRylAMQSD-inv37aosmJ72nQkQTWfPqsLsntXeo5t07S2p-j8T8MGNvA2U1fqHFea4xBBQ3GrPc1UkiWV1brpDaxQDkBYk786KOjoYk56qa2TFvD9LvI6KWfNlFKeDUZ0yRqshm_he4sHe34CX5tlT3l7xQPQNgCta8sG4ZdxTs0XpaHmuP7N3kANFn3TJTVWLl84GTTMbsnQ2r1t6PlAJ4g2OHaVNJneQlHdpaVhXvVneayjpKWBuVMdWRGvGCdibOh2olHlAFY-w0r-Yg7lJyFeabYxdTXhXeWbXmt4lHYPhrhUtvq3fiDkZTNXPek3IxCDn9hYstwXQ";
    var $request_secret = "0_1_39zHsI3VZxMWrbfy_1_3ajx5Fm2HLf2i_1_4n4ZQEsaBQWgSD6aboFjsWe_1_3Fp_1_4eZrh0_1_4jQF4eC0jcwRZExAiVFf1EErD6N5edc2tLnQMZDnEerpY1Xauw2n5tWpHgaxV1fBI3v3pB9eGZwYEUbrG2Z_1_3kPBNza3CCTY1jQrg6tmxMMIyIcyi75JNK2KGoDB6nrqQJoBdJk5TcnKQY_1_3tVl0QkE9KEx0DaSLx6tR1UQYBH094bXijtcOUprYog2tvLUxXPXGrBXSQLONnCGbAdPSUiUrZg_1_2_1_2";
    var $campaignId = "QbmxrqeCV_1_4J3oGxXnm5F_1_4g_1_2_1_2";
    var $drawId = "kPm7qBAjlNMtKYFFa02Dcg_1_2_1_2";



    public function requestAllCoupon(){
        set_time_limit(0);
        $arr = $this->getAllTask();

        $results = [];

        foreach($arr as $item){
            $result = $this->generateCoupon($item);
            array_push($results, $result);
        }

        return $results;
    }

    private function generateCoupon($job){
        $requestBody = [
            "campaignId" => $this->campaignId,
            "drawId" => $this->drawId,
            "countryCode" => "971",
            "customerMobileNumber" => $job['phone'],
            "customerName" => $job['customer_name'],
            "numberOfTickets" => 1,
            "transactionId" => $job['job_id']
        ];


        $res = $this->request('GenerateCoupon', $requestBody);
        return $res->ok();
    }

    public function getAllTask(){
        $total_page = 1; // 43
        set_time_limit(0);
       $allData = [];
       
       
       for($i = 1; $i <= $total_page ; $i++){

           $this->res = $this->fetchData($i);
           while($this->res->getStatusCode() != 200);
           $arr = Api::getResponseAsArray($this->res)['data'];

           foreach($arr as $item){
               array_push($allData, [
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
            "start_date" => "2022-02-02",
            "end_date" => "2022-02-02",
            "is_pagination" => 1,
            "job_type" => [0,1,2,3],
            "team_id" => 1105452,
            "requested_page" => $page,
            "job_status" => 2
        ];

        return Api::request('get_all_tasks', $requestBody);
    }


    public function request($methodName, $body){
        $headers = [
            "content-type" => "application/json",
            'accept' => 'application/json',
            "request_secret" => $this->request_secret,
            "authorization" => 'Bearer ' . $this->token,
        ];


        $res = Http::withHeaders($headers)->post('https://apiraffles.dubaided.gov.ae/api/Campaign/' . $methodName, $body);
        return $res;
    }
}
