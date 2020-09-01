<?php

session_start();

ini_set("session.save_path", "session/");

// ini_set('session.save_path', '/minhas_sessions/');
ini_set('session.gc_maxlifetime', '172800');
ini_set('session.gc_probability', 1);
session_set_cookie_params(172800);
require_once('controller/precoExato.php');

require_once('controller/getSaldo.php');

require_once('model/Operacao.php');

error_reporting(1);

$op = new Operacao;

$api_key = $_POST['api_key'];
$api_secret = $_POST['api_secret'];
$ativo = $_POST['ativo'];
$id_cliente = $_POST['id_cliente'];

// $api_key = '53d5d9d985a9d1c2adae7a792a18dd6a';
// $api_secret = 'JnNPWu62taULIUcc9uxss5PQTMT0Xs19';
// $ativo = $_POST['ativo'];
// $id_cliente = $_POST['id_cliente'];

	$compraBug = $op->getCompraBugada($id_cliente,1);
	$tblCompraBug = $compraBug->fetch(PDO::FETCH_OBJ);
	$precoAtualBug = $tblCompraBug->preco_atual;
	$valorMercadoBug = $tblCompraBug->valor_mercado;
	$valorCriterioBug = $tblCompraBug->criterio;
	$valorHoraBug = $tblCompraBug->hora;
	$totalBug = $valorMercadoBug - $precoAtualBug;

$log = $op->getLog($id_cliente);

$cod_log = $log->fetch(PDO::FETCH_OBJ);

$log_error = $cod_log->log;

$cliente = $op->getCliente($id_cliente);

$dadosCliente = $cliente->fetch(PDO::FETCH_OBJ);

$dateLimit = date("d/m/Y", strtotime($dadosCliente->data_limite));

$dateLimit2 = $dadosCliente->data_limite;


$lastTwoTransc = $op->getTwoTransactions($id_cliente);


while($twoTbl = $lastTwoTransc->fetch(PDO::FETCH_OBJ)){
	$twoTbl = $twoTbl->criterio;

	if($twoTbl == 3):
		$lastOk = 1;
	else:
		$lastOk = 0;
		break;
	endif;
}






// PEGA O SALDO EM BR E BITCOIN
		$action = 'ticker';
		$request_body['timestamp'] = time();
		// $request_body['status'] = 'all';

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
		// livro de ofertas
		$valorNegocio =  json_decode($result,true);
		$valorNegociado = $valorNegocio['ticker']['last'];
		$melhorCompra = $valorNegocio['ticker']['buy'];
		$melhorVenda = $valorNegocio['ticker']['sell'];

// PEGA O SALDO EM REAIS E BITCOIN
$getSaldo = getSaldo($api_secret,$api_key,$request_body);

$saldoREAL = $getSaldo['saldoREAL'];
$saldoDIp = $getSaldo['saldoDispBTC'];
$saldoDsREAL = $getSaldo['saldoDispREAL'];
$power  = 1;
$saldoBTC  = $getSaldo['saldoBTC'];

$msgw = $saldoREAL;

date_default_timezone_set('America/Sao_Paulo');
$dateNow = date('Y-m-d');
$horaNow = date('H:i:s');
$hora_sg = explode(':', $horaNow);
$hora_sg = $hora_sg[0];

$testedata = $dateNow -1;
$arrayHora1 = explode(':',$valorHoraBug);
$arrayHora2 = explode(':',$horaNow);

$horaCompra   = $arrayHora1[0];
$minutoCompra = $arrayHora1[1];

$horaNowCompra   = $arrayHora2[0];
$minutoNowCompra = $arrayHora2[1];

$calcHora = $minutoNowCompra - $minutoCompra;

if( ($horaNowCompra  > $horaCompra) || ($horaCompra == $horaNowCompra && $calcHora > 40) ):
	$skip = 1;
else:
	$skip = 0;	
endif;

// $testao = $horaCompra.' / '.$minutoCompra.' / '.$horaNowCompra.' / '.$minutoNowCompra;
// if( $saldoREAL > 0 || $saldoBTC > 0 ):

$percentual_dia = $op->getPorcentagem($id_cliente,$saldoREAL);
// $dif_hora_compra = strtotime($valorHoraBug);
// $dif_hora_compra_now = strtotime($dateNow);

if($percentual_dia > 1 && !empty($percentual_dia)):
	$stopSuccess = 1;
else:
	$stopSuccess = 0;
endif;
								
if( $ativo > 0 && isset($id_cliente) && strtotime($dateLimit2) >  strtotime($dateNow)):
// $msg = 'aqui';

		$preco_atual = $valorNegociado;

		


		// PEGA O VALOR DA ULTIMA COMPRA
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
        $valor_da_Compra = $ultimaOrdem[0]['orders_executed'];
        $valorBuy = $valor_da_Compra[0]['price'];
        $tipoOrdemComplete = $ultimaOrdem[0]['type'];




        // VERIFICA O TIPO DA ULTIMA ORDEM
        $request_body2['timestamp'] = time();
        $request_body2['status'] = 'active';
        $request_body2 = http_build_query($request_body2);
        $api_post['api_key'] = $api_key;
        $api_post['request_body'] = $request_body2;
        $api_post['signature'] = hash_hmac('sha256', $request_body2, $api_secret);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/order_list');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $tipoOrdem =  json_decode($result,true);
        // $valorTipoOrdem = $tipoOrdem[0]['type']; 
        if(is_array($tipoOrdem)):
		    $tipoDaOrdem = $tipoOrdem[0]['type'];  
            $valorDaOrdem = $tipoOrdem[0]['price'];  		    
		else:
		    $tipoDaOrdem = null;
		    $valorDaOrdem = null;
		endif;


		// PEGA A ULTIMA ORDEM
		$ordem = $op->getOperacao();
		$getVariacao = $op->getVariacao();

		$var_array = $getVariacao['var_array'];
		$melhorPrecoAux = $getVariacao['melhorPrecoAux'];
		$explode_hora1 = $getVariacao['explode_hora1'];
		$precoMin = $getVariacao['precoMin'];
		$diferencaPreco = $getVariacao['diferencaPreco'];
		$print_r = print_r($teste6,true);

		// PEGA OS VALORES DA BASE
		$listaOp = $ordem->fetch(PDO::FETCH_OBJ);
		$criterio = $listaOp->criterio;
		$show = $listaOp->show;
		$lastOrdem = $listaOp->ativo;


		$ordemCancele = $op->getCompraCancele($id_cliente);
		// PEGA OS VALORES DA BASE
		$listaOpCancele = $ordemCancele->fetch(PDO::FETCH_OBJ);
		$criterioCancele = $listaOpCancele->criterio;
		$OrdemCancelePreco = $listaOpCancele->preco_atual;
		// $valorUltimaCompra = $listaOp->preco_atual;
		// else:
		// 	$lastOrdem =  $teste[0]['price'];
		// endif;

		// $ordemCompra = $op->getCompra();
		// $ultimaCompra = $ordemCompra->fetch(PDO::FETCH_OBJ);
		// $valorUltimaCompra = $ultimaCompra->preco_atual;



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
		// $count_bids = count($array['bids']);
		$count_bids = count($array['asks']);
		$compra = $array['bids'];
		// $venda  = $array['asks'];

		// print_r($compra).'<br>';


		// $preco_exato = $var[$y][$i] ;

		$stop = $precoAtualBug - $preco_atual;
$calculoBuyStop = $preco_atual - $melhorCompra;

// insere variacao

$insertVariacao = $op->insertVariacao($preco_atual);

		if(!empty($tipoOrdem) && is_array($tipoOrdem)):
			$calcValorOrdemBuy  = $preco_atual - $precoAtualBug;
			$calcValorOrdemSell  = $valorDaOrdem - $preco_atual;
		endif;

		if(!empty($valorBuy)):
			// $calculoBuy = $preco_atual - $valorBuy;
			$calculoBuy = $melhorCompra - $valorBuy;
			// $stop = $valorBuy - $preco_atual;
			
			$calculoCompra =  $melhorVenda - $preco_atual;		
			// $test1 = $melhorCompra - $valorBuy;
			$previsaoVenda = $valorBuy + 200;
			// $test = $previsaoVenda;
			$previsaoVenda ='<div class="alert alert-primary text-center" role="alert">Previsão de venda: '.$previsaoVenda.'</div>';
		else:
			$calculoBuy = 0;
			$previsaoVenda = '<div class="alert alert-primary text-center" role="alert">Buscando previsão de venda...</div>';
		endif;


		// echo $preco_atual;

		if(empty($saldoDIp)):
			$saldoDIp = $saldoBTC;
		endif;	

		// if(!empty($saldoDIp)):
		// 	$saldoREAL = $saldoDsREAL;
		// endif;	
		$compraCorrige = $preco_atual * $saldoBTC;
		$search_first_compra = $op->getCompraBugada($id_cliente,3);
		$first_compra = $search_first_compra->fetch(PDO::FETCH_OBJ);
		$valorFirstCompra = $first_compra->criterio;

				// SÓ CONTINUA CASO O PRECO TENHA SUBIDO EM RELACAO A OFERTA ATIVA
				if( (empty($tipoOrdem) && $tipoOrdemComplete =='sell' && is_array($tipoOrdem) && $saldoBTC <= 0)
					||(empty($tipoOrdem)  && is_array($tipoOrdem) && $saldoBTC <= 0 && empty($valorFirstCompra) )
					||(empty($tipoOrdem) && $tipoOrdemComplete =='sell' && is_array($tipoOrdem) && $saldoBTC <= 0 && $compraCorrige > 0 && $compraCorrige < 11)):
				// if( $criterioCancele == 6 ):

					if($stopSuccess == 0):

						$precoCalculado = $lastOrdem - $preco_atual;

						// VERIFICA SE COMEÇOU DO ZERO
						// if(empty($show)):

	

								if( $var_array[2] > $var_array[1] && $var_array[0] > $var_array[1] ):

											$calc_negocio = $var_array[0] - $var_array[1];
											// $calc_negocio = $calc_negocio / 2;
											// $melhorPrecoExemplo =  $var_array[0] + $calc_negocio;
											
											if(!isset($_SESSION['preco_compra'])):
												$_SESSION['preco_compra'] = null;
											endif;


											if( $preco_atual > $melhorPrecoAux && $calc_negocio > 100 && $diferencaPreco > 300):	
											// $melhorPreco = $melhorPrecoAux;
											$melhorPreco = $precoMin + 150;

											
											$rt = 'teteere';


												$_SESSION['hora_compra']  = $hora_sg;
												$_SESSION['preco_compra'] = $melhorPreco;
											endif;

								endif;

								
								$stoploss = $lastOrdem - 500;
								$arrayName = 'melhorPreco:'.$melhorPreco.'<br>Melhor preco aux: '.$melhorPrecoAux.'<br>Explode hora: '.$explode_hora1.'<br>preco aux:'.$_SESSION['preco_compra'];

								// $printr = print_r($arrayName,true);

								if( isset($melhorPreco) && !empty($melhorPrecoAux)  && $explode_hora1 >= 45):
									$msg = '<div class="alert alert-primary text-center" role="alert">Próxima ordem para compra: ' . $melhorPreco.'</div>'.$exit;
									// $msg .= '<div class="alert alert-secondary text-center" role="alert">Melhor preço para compra: ' . number_format($melhorVenda, 2, '.', '') .'</div>';
								
									$volumeBitcoin = 0;
									for($i=0;$i < $totalOrdens;$i++){
										$volumeArray[] = $compra[$key][1];
										$volumeBitcoin = $volumeBitcoin + $compra[$key][1];
										$key++;
									}
									

									$criterioB = 1; 
									// $msg .= print_r($volumeArray,true).'<br>';
									// $msg .= $volumeBitcoin;
									$dia = date('D');
									// $msg .= $dia;


									// if($totalOrdens > 4  && $dia != 'Sun'):
										// $volumeBTC = 0.22222222;	
									// elseif($totalOrdens > 4 && $dia == 'Sun'):
										// $volumeBTC = 0.44444444;	
									// endif;

									$msg .= '<div class="alert alert-warning text-center" role="alert">Volume atual em bitcoin: ' . $volumeBitcoin .'</div>';


									if($saldoREAL > 0 ):

										

										$oco = $preco_atual + 400;

									

										// $msg .= 'Efetuar compra';
									$transacao = $op->efetuarTransacao($preco_atual,$melhorPreco,$criterioB,$precoCalculado,$saldoREAL,$saldoBTC,$melhorVenda,$oco,$api_key,$api_secret,$id_cliente,$precoAtualBug);

									endif;
								else:
									// if(!empty($saldoREAL)):


									$msg .= '<div class="alert alert-primary text-center" role="alert">Buscando compradores...</div>'.$precoMin;
									// $msg .= '<div class="alert alert-success text-center" role="alert">Ultima Ordem: ' . $lastOrdem . '</div>';
								// endif;
								endif;
					else:
								
							$msg = '<div class="alert alert-success text-center" role="alert"><h5>Stop por realizar lucro de '.$percentual_dia.'% </h5></div>';
							$exit = 1;

					endif;		
								
					// endif;			

				elseif( $stop >= 320 && $stop <= 650  && $calculoBuyStop  <= 20 ):
						$msg   = '<div class="alert alert-danger text-center" role="alert">Melhor stop loss de venda: ' . $preco_atual . '</div>';
						 $msg .='<div class="alert alert-primary text-center" role="alert">Ultima ordem: ' . $lastOrdem.'</div>';
					

						$melhorPreco = $preco_atual;
						$oco =  null;
						$criterioB = 4;				
						// if($saldoDIp > 0):												
							$transacao = $op->efetuarTransacao($preco_atual,$melhorPreco,$criterioB,$precoCalculado,$saldoREAL,$saldoDIp,$melhorCompra,$oco,$api_key,$api_secret,$id_cliente,$precoAtualBug);
						// endif;		

						

				elseif(( $_SESSION['hora_compra'] != $hora_sg && !empty($tipoOrdem) && is_array($tipoOrdem) &&  $tipoDaOrdem == 'buy' && $criterioCancele != 6) 
					|| ($calcValorOrdemBuy > 380  && !empty($tipoOrdem) && is_array($tipoOrdem) &&  $tipoDaOrdem =='sell' && $criterioCancele != 6) 
					|| (!empty($tipoOrdem) && is_array($tipoOrdem) &&  $tipoDaOrdem =='sell' && $criterioCancele != 6 && $saldoDsREAL < 11 &&  $saldoDsREAL > 0)
					// || (!empty($tipoOrdem) && is_array($tipoOrdem) &&  $tipoDaOrdem =='sell' && $criterioCancele != 6 && $lastOk == 1) 
					):
					// CANCELA ORDENS DE COMPRA SE VALOR ATUAL ESTIVER MUITO ACIMA 
					$request_body3['timestamp'] = time();
			        $request_body3['type'] = 'buy';
			        $request_body3 = http_build_query($request_body3);
			        $api_post['api_key'] = $api_key;
			        $api_post['request_body'] = $request_body3;
			        $api_post['signature'] = hash_hmac('sha256', $request_body3, $api_secret);
			        $ch = curl_init();
			        curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/order_cancel/all');
			        curl_setopt($ch, CURLOPT_POST, true);
			        curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			        $result = curl_exec($ch);
			        $tipoOrdem =  json_decode($result,true);

			        $cancelado = $op->CancelarOrdem($preco_atual,6,$saldoREAL,$id_cliente);
			    elseif( $saldoDIp  > 0 && $saldoDIp  != null && $compraCorrige > 10):

								

						$oco =  null;
						$auxPreco = $preco_atual - $precoAtualBug;

						$skipDown = $melhorVenda;

						if($auxPreco < 590):
							$melhorPreco =  $precoAtualBug + 590;
							$criterioB = 3;	

												
							$transacao = $op->efetuarTransacao($preco_atual,$melhorPreco,$criterioB,$precoCalculado,$saldoREAL,$saldoDIp,$melhorCompra,$oco,$api_key,$api_secret,$id_cliente,$precoAtualBug);
							

						elseif($auxPreco > 590 && $calculoBuyStop <= 100):
							$melhorPreco =  $preco_atual;	
							$criterioB = 7;	

												
							$transacao = $op->efetuarTransacao($preco_atual,$melhorPreco,$criterioB,$precoCalculado,$saldoREAL,$saldoDIp,$melhorCompra,$oco,$api_key,$api_secret,$id_cliente,$precoAtualBug);
							

						endif;

							$msg  = '<div class="alert alert-success text-center" role="alert">Ultima Ordem: ' . $lastOrdem . '</div>';
							// $msg .= '<div class="alert alert-secondary text-center" role="alert">Melhor preço para venda: ' . number_format($melhorPreco, 2, '.', '') .'</div>';

				endif;

				if(!isset($exit) && $exit != 1):

					if(isset($msg)):
						$msg .= '<div class="alert alert-dark text-center" role="alert">Preço atual do bitcoin: ' . number_format($preco_atual, 2, '.', '').'</div>';

						if(isset($transacao)):
							// $msg .='<div class="alert alert-danger text-center" role="alert">'.$transacao.'</div>';
						endif;
					else:

						$msg = '<div class="alert alert-danger text-center" role="alert">Sem previsões no momento, aguarde...</div>'.$_SESSION['hora_compra'];
						// $msg .= filesize("robo.php").'<br>';
							// $msg .='<div class="alert alert-danger text-center" role="alert">'.$transacao.'</div>';

						if(isset($transacao)):
							$msg .='<div class="alert alert-danger text-center" role="alert">'.$transacao.'</div>';
						endif;

						$msg .= '<div class="alert alert-dark text-center" role="alert">Preço atual do bitcoin: ' . number_format($preco_atual, 2, '.', '') .'</div>';
						$msg .= '<div class="alert alert-success text-center" role="alert">Ultima Ordem: ' . $lastOrdem . '</div>';
						// $msg .= '<div class="alert alert-secondary text-center" role="alert">Melhor venda: ' . number_format($melhorCompra, 2, '.', '') . '</div>';
						// $msg .= '<div class="alert alert-primary text-center" role="alert"> Diferença de preço: ' . $calculoBuy . '</div>';


					endif;

				endif;
				// $msg .= filesize("robo.php").'<br>';

				// $msg .= filesize("robo.php");

					echo json_encode(compact('msg','preco_atual','saldoDIp','saldoREAL','dateLimit','ativo'));


					
				// else:
				// 	$msg = 'Sem negociações no momento!' . '<br> Ultima ordem: ' . $lastOrdem;
				// 	$msg .= '<br><span class="text-secondary"> Preço atual: ' . number_format($preco_atual, 2, '.', '') . '</span>';
				// 	echo json_encode(compact('msg'));					
				// endif;
				// endif;

else:

	// $show = 0;
	// $criterio = null;
	// $ativo = null;
	// $op = $op->updateOP($show,$ativo,$criterio);





$_SESSION = array();
session_destroy();
$msg = 1;

	echo json_encode(compact('msg'));
 // header('Location:login.php');

	

endif;
clearstatcache();

header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, cachehack=".time());
header("Cache-Control: no-store, must-revalidate");
header("Cache-Control: post-check=-1, pre-check=-1", false);
// clearstatcache();


// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");

// ($stopCancele >= 600 && $stopCancele <= 800  && $calculoBuyStopCancele <= 50  && $tipoOrdemCancele =='buy' && is_array($cancele) && !empty($cancele))

// || (!empty($tipoOrdem) && is_array($tipoOrdem) &&  $tipoDaOrdem == 'buy' && $criterioCancele != 6 && $skip == 1 ) 