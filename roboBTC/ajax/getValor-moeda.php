<?php
$api_key = '53d5d9d985a9d1c2adae7a792a18dd6a';
$api_secret = 'JnNPWu62taULIUcc9uxss5PQTMT0Xs19';
$request_body['timestamp'] = time();
$request_body = http_build_query($request_body);

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

	$saldoDispBTC = $saldoDisponivel['BTC'];

	echo $saldoDispBTC;