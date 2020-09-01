<?php


try{
  $api_key = $_POST['api_key'];
  // $api_key = '3YlbObNjztsFR7xFIa3CHRUTG3CuG3t7';
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://brasilbitcoin.com.br/api/get_balance",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authentication: $api_key"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {

    throw new Exception("Error: $err");
    
  } 

  // echo $response;

  $array = json_decode($response,true);
  echo json_encode($array);

}catch(Exeption $e){
  echo json_encode($e->getMessage());
}