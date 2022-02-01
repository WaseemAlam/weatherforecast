<?php

class ForecastTest extends \PHPUnit\Framework\TestCase {
    public function testForecast(){
        $country_id=2;
        $cityWeather=[0=>"Processed City Durres| Heavy rain - Sunny",1=>"Processed City Tirana| Heavy rain - Sunny"];
        $data=[            
            "error"=> false,
            "code"=> 200,
            "message"=> "",
            "data"=> $cityWeather,
            "status"=> 200
        ];
        $post = [
            'status' => 'success',
            'countryId' => 2
        ];
        $ch = curl_init();
        $host="http://127.0.0.1:80";
        $url = $host.'/app/Forecast.php';
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        curl_close ($ch);
        $this->assertEquals($data,json_decode($response,true));
    }
}

?>