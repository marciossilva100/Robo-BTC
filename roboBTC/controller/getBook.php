<?php
function getBook($api_secret,$api_key,$request_body){
	$action = 'orderbook';

	$api_post['api_key'] = $api_key;
	$api_post['request_body'] = $request_body;
	$api_post['signature'] = hash_hmac('sha256', $request_body, $api_secret);


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/api/BTC/'.$action);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($ch);

	$array =  json_decode($result,true);

	$count_bids = count($array['bids']);

	$compra = $array['bids'];
	$venda  = $array['asks'];

	return array($compra,$venda);
}