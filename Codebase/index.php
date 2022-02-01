<?php
  function getCountries(){ 
    $data=[
      "error"=>false,
      "code"=>200,
      "message"=>"",
      "data"=>""
    ];
    $status=false;
    $status_code=200;
    $ch = curl_init();
    $url="https://api.musement.com/api/v3/countries";
    $getUrl = $url;
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
      return $data;
    }
    else
    {
      $data["error"]=false;
      $data["status"]=200;
      $response=json_decode($response,true);
      $data["data"]=$response;
      return $data;
    }  
    curl_close($ch);
  }
  $response=getCountries();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/style.css">
  <script src="/js/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Weather Forecasting For country cities.</h2>
    <div class="form-group">
      <label for="chooseCountry">Please select Country:</label>
      <select class="form-control" id="country">
        <?php foreach($response['data'] as $key=>$country){?>
          <option value="<?=$country['id']?>"><?=$country["name"]?> </option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group" id="cities"></div>
    <div class="loader" id="spinner"></div>
</div>

</body>
</html>
<script>
    function sendAjaxRequest(){
      $.ajax({
            url:"app/Forecast.php",    //the page containing php script
            type: "post",    //request type,
            dataType: 'text',
            data: {status: "success", countryId: $('#country :selected').val()},
            success:function(result){
              $("#spinner").hide();
              var response=JSON.parse(result);
              if(response.message){
                $("#cities").append("No City Found Under this country");
              }
              else{
                response.data.forEach(function(item) {
                  var data="<p>"+item+"</p>";
                  $("#cities").append(data);
                });
              }

            }//end of success function
        });
    }
$( document ).ready(function() {
    $("#country").change(function(){
      $("#spinner").show();
      $("#cities").empty();
      sendAjaxRequest();
    });
    sendAjaxRequest();
});

</script>