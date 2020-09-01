<?php 
function getSaldo($api_secret,$api_key,$request_body){
	$action = 'balance';

	$api_post['api_key'] = $api_key;
	$api_post['request_body'] = $request_body;
	$api_post['signature'] = hash_hmac('sha256', $request_body, $api_secret);


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/'.$action);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($ch);

	$ultimaOrdem =  json_decode($result,true);

	$saldo = $ultimaOrdem['total'];
	$saldoDisponivel = $ultimaOrdem['available'];
	$saldoREAL =  $saldo['REAL'];
	$saldoBTC  =  $saldo['BTC'];

	$saldoDispBTC  = $saldoDisponivel['BTC'];
	$saldoDispREAL = $saldoDisponivel['REAL'];

	return compact('saldoREAL','saldoBTC','saldoDispBTC','saldoDispREAL');
}