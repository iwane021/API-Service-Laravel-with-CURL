<?php

namespace App\Services;

class ApiService
{

    public function __construct()
    {
        $this->header = array(
            // "Authorization: bearer " . session('api_key'),
            // "cache-control: no-cache",
            // "Content-Type:application/x-www-form-urlencoded;charset=UTF-8",
            "Content-Type:application/json;charset=UTF-8",
            "Accept-Charset:UTF-8"
        );
    }

    public function query(array $params) {
        $options = array(
            CURLOPT_URL => $params['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $params['method'],
        );

        if(isset($params['body'])) {
            $options[CURLOPT_POSTFIELDS] = http_build_query($params['body']);
        }

        if(isset($params['header'])) {
            $options[CURLOPT_HTTPHEADER] = array_merge($this->header, $params['header']);
        } else {
            $options[CURLOPT_HTTPHEADER] = $this->header;
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            return $err;
        } else {
            return json_decode($response);
        }
    }

}
