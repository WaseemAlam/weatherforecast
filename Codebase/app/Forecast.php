<?php
namespace App;
// required headers
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Forecast{

  public function getCitiesForCountry($country_id){ 
    $data=[
      "error"=>false,
      "code"=>200,
      "message"=>"",
      "data"=>""
    ];
    $status=false;
    $status_code=200;
    $ch = curl_init();
    $getUrl="https://api.musement.com/api/v3/countries/".$country_id."/cities";
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $getUrl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    
    $response = curl_exec($ch);
    
    if(curl_error($ch)){
      $data["error"]=true;
      $data["status"]=400;
      $data["message"]=curl_error($ch);
      curl_close($ch);
      print_r(json_encode($data));
      exit();
    }
    else
    {
      $data["error"]=false;
      $data["status"]=200;
      $response=json_decode($response,true);
      if(!empty($response["message"])){
        $data["message"]=$response["message"];
        unset($data["data"]);
        print_r(json_encode($data));
        exit();
      }
      $forecast=$this->getForecastForCities($response,"b296af8ec99949ae86373440222801");
      $data["data"]=$forecast;
      $res=json_encode($data);
      print_r($res );
      exit();
      curl_close($ch);
    }

  }

  public function getForecastForCities($cities,$key){    
        $count=0;
        $forecast=[];
        $status=false;
        $status_code=200;
        $ch = curl_init();
        foreach($cities as $city){
          $getUrl="http://api.weatherapi.com/v1/forecast.json?key=".$key."&q=".$city["name"]."&days=2";
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_URL, $getUrl);
          curl_setopt($ch, CURLOPT_TIMEOUT, 80);
          
          $response = curl_exec($ch);
          
          if(curl_error($ch)){
          }
          else
          {
            $response=json_decode($response,true);
            if(!empty($response["forecast"])){
              $forecast[$count]="Processed City ".$city["name"]."| ".$response["forecast"]["forecastday"][0]["day"]["condition"]["text"]." - ".$response["forecast"]["forecastday"][1]["day"]["condition"]["text"];
              $count=$count+1;  
            }
          }//end of else  
    
        }//end of foreach
        curl_close($ch);      
        return $forecast;
  }

}
$forecast=new Forecast;

$data = json_decode(file_get_contents('php://input'), true);
if(!empty($data)){
  if($data['status'] == "success" && !empty($data['status']) && !empty($data['countryId'])){
    $forecast->getCitiesForCountry($data['countryId']);
  }else{
      echo "CountryId is Required";
  }  
}

if(!empty($_REQUEST['status']) && !empty($_REQUEST['countryId']) ){
    if($_REQUEST['status'] == "success"){      
      $forecast->getCitiesForCountry($_REQUEST['countryId']);
    }    
}
?>