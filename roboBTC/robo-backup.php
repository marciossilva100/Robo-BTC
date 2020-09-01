<?php

include('controller/precoExato.php');
include('controller/getSaldo.php');
include('model/Operacao.php');
// error_reporting(0);

$op = new Operacao;

$api_key = '53d5d9d985a9d1c2adae7a792a18dd6a';
$api_secret = 'JnNPWu62taULIUcc9uxss5PQTMT0Xs19';

// PEGA O SALDO EM BR E BITCOIN
		$action = 'ticker';
		$request_body['timestamp'] = time();
		// $request_body['status'] = 'all';

		// ... outros parâmetros se necessário

		// conversão da variável em formato QueryString ( timestamp=123456789&value=120.20&bank_id=231 )
		$request_body = http_build_query($request_body);

		$api_post['api_key'] = $api_key;
		$api_post['request_body'] = $request_body;
		$api_post['signature'] = hash_hmac('sha256', $request_body, $api_secret);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/api/BTC/'.$action);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);

		$valorNegociado =  json_decode($result,true);
		$valorNegociado = $valorNegociado['ticker']['last'];

// PEGA O SALDO EM REAIS E BITCOIN
$getSaldo = getSaldo($api_secret,$api_key,$request_body);

$saldoREAL = $getSaldo['saldoREAL'];
// $saldoBTC  = 0.665;
$saldoBTC  = $getSaldo['saldoBTC'];


if( $saldoREAL > 0 || $saldoBTC > 0 ):

		//PEGA A ULTIMA ORDEM QUE FOI COMPLETADA
		$request_body1['timestamp'] = time();
		$request_body1['status'] = 'completed';

		$request_body1 = http_build_query($request_body1);

		$api_post['api_key'] = $api_key;
		$api_post['request_body'] = $request_body1;
		$api_post['signature'] = hash_hmac('sha256', $request_body1, $api_secret);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/order_list');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		$ultimaOrdem =  json_decode($result,true);

		$teste = $ultimaOrdem[0]['orders_executed'];

		$ordem = $op->getOperacao();


		// PEGA OS VALORES DA BASE
		$listaOp = $ordem->fetch(PDO::FETCH_OBJ);
				$criterio = $lista->criterio;
				$show = $listaOp->show;
		
		// VERIFICA SE FOI INICIADA UMA OPERACAO DO ZERO
		if(empty($show)):
			$lastOrdem = $listaOp->ativo;
		else:
			$lastOrdem =  $teste[0]['price'];
		endif;


		// echo $lastOrdem . '<br>';

		



		// $saldoBTC  = 0.00600;


		// echo '<b>Saldo REAL:</b> ' . $getSaldo['saldoREAL'] . '<br>';
		// echo '<b>Saldo BTC:</b> '  . $getSaldo['saldoBTC'] . '<br>';



		// PEGA O LIVRO DE OFERTAS
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

		// print_r($compra).'<br>';




				// $preco_exato = $var[$y][$i] ;

		$preco_atual = $valorNegociado;

		// echo $preco_atual;

				// SÓ CONTINUA CASO O PRECO TENHA SUBIDO EM RELACAO A OFERTA ATIVA
				if($preco_atual < $lastOrdem):

					$precoCalculado = $lastOrdem - $preco_atual;

					echo $preco_atual.'<br> COMPRA <br>';
					$tipo = 'COMPRA';

					// VERIFICA SE COMEÇOU DO ZERO
					if(empty($show)):

						// PEGA O VALOR DO LIVRO DE OFERTA QUE MAIS SE ADEQUA 
						for($y=0;$y<$count_bids;$y++){
							// for($i=0;$i<2;$i++){

								if($compra[$y][0] <= $preco_atual):

									$lista[][0] = $compra[$y][0];

									// $ordemMaior[] = $var[$y][$i];
								endif;	

							// }
						}

						$key = getOrdemExata($lista);

					// VERIFICA SE O PRECO ATUAL ESTÁ ACIMA DE 1000 EM RELACAO A OFERTA ATIVA
					elseif($precoCalculado > 500 && $criterio != 'compra'):

						// PEGA O VALOR DO LIVRO DE OFERTA QUE MAIS SE ADEQUA 
						for($y=0;$y<$count_bids;$y++){
							// for($i=0;$i<2;$i++){

								if($compra[$y][0] <= $preco_atual):

									$lista[][0] = $compra[$y][0];

									// $ordemMaior[] = $var[$y][$i];
								endif;	

							// }
						}


						$key = getOrdemExata($lista);

						$transacaoCompra = $op->fazerCompra($key);	

					elseif($precoCalculado > 300 && $criterio == 'compra'):
						
						$transacaoCompra = $op->fazerCompra($preco_atual);

					endif;	

				// SÓ CONTINUA CASO O PRECO TENHA DESCIDO EM RELACAO A OFERTA ATIVA

				elseif($preco_atual > $lastOrdem):

					$precoCalculado =  $preco_atual - $lastOrdem;

					// echo '<b>Preço calculado:</b> ' . $precoCalculado.'<br> VENDA <br>';
					$tipo = 'VENDA';


					// VERIFICA SE O PRECO ATUAL ESTÁ ACIMA DE 500 EM RELACAO A OFERTA ATIVA
					if($precoCalculado > 500):

						// PEGA O VALOR DO LIVRO DE OFERTA QUE MAIS SE ADEQUA 
						for($y=0;$y<$count_bids;$y++){
							// for($i=0;$i<2;$i++){

							// echo $venda[$y][0]. '<br>';

								if($venda[$y][0] >= $preco_atual):

									$lista[][0] = $venda[$y][0];

									// $ordemMaior[] = $var[$y][$i];
								endif;	

							// }
						}


						$key = getOrdemExata($lista);

						// echo $key . '<br>';		

					endif;	

				endif;


				if($saldoREAL > 0 || $saldoBTC > 0):

					if(!empty($key)):

						$melhorPreco = $lista[$key][0];
						echo json_encode(compact('melhorPreco','tipo'));
					endif;
				else:
					$msg = 'Você não tem saldo suficiente';
					echo json_encode(compact('msg'));
				endif;

else:



	
	$show = 0;
	$criterio = null;
	$ativo = $valorNegociado;

	$op = $op->updateOP($show,$ativo,$criterio);


endif;




