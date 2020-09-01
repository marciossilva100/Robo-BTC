<?php
require_once('Conexao.php');
class Operacao{


	public function getCompraBugada($id_cliente,$criterio){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		$sql = 'SELECT preco_atual,valor_mercado,criterio,hora FROM transacao  WHERE criterio = :criterio  AND id_cliente = :id_cliente ORDER BY id DESC LIMIT 1';

		$query =  $conn->prepare($sql);
		$query -> bindValue(':id_cliente', $id_cliente);		
		$query -> bindValue(':criterio', $criterio);		
		$query -> execute();
		$rows = $query->rowCount();

		return $query;
	}


	public function getCompraCancele($id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		$sql = 'SELECT preco_atual,criterio,hora FROM transacao  WHERE id_cliente = :id_cliente ORDER BY id DESC LIMIT 1';

		$query =  $conn->prepare($sql);
		$query -> bindValue(':id_cliente', $id_cliente);				
		$query -> execute();
		$rows = $query->rowCount();

		return $query;
	}


	public function getPorcentagem($id_cliente,$saldoBRL){
		$conn = new Conexao;
		$conn = $conn->getConexao();
		date_default_timezone_set('America/Sao_Paulo');
		$data = date('Y-m-d');

		
		 $sql = 'SELECT * FROM transacao where criterio = 1 AND id_cliente = :id_cliente AND data = :data ORDER BY id ASC LIMIT 1';
		 $query =  $conn->prepare($sql);
		 $query -> bindValue(':id_cliente', $id_cliente);
		 $query -> bindValue(':data', $data);

		 $query -> execute();

		$tbl = $query->fetch(PDO::FETCH_OBJ);

		 $saldoAnterior = $saldoBRL - $tbl->saldo;
		 $saldoAnterior =  ($saldoAnterior / $tbl->saldo) * 100;

		return number_format($saldoAnterior, 2, '.', '');
	}


	public function getTwoTransactions($id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		$sql = 'SELECT preco_atual,criterio FROM transacao  WHERE id_cliente = :id_cliente ORDER BY id DESC LIMIT 2';

		$query =  $conn->prepare($sql);
		$query -> bindValue(':id_cliente', $id_cliente);				
		$query -> execute();
		$rows = $query->rowCount();

		return $query;
	}

	public function getOperacao(){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		$sql = 'SELECT ativo,criterio,`show` FROM operacao WHERE id = 1';

		$query =  $conn->prepare($sql);
		$query -> bindValue(':id_cliente', $id_cliente);						
		$query -> execute();
		$rows = $query->rowCount();

		return $query;
	}

	public function getCliente($id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		$sql = 'SELECT * FROM cliente WHERE idcliente = :id_cliente';

		$query =  $conn->prepare($sql);
		$query -> bindValue(':id_cliente', $id_cliente);						
		$query -> execute();
		$rows = $query->rowCount();

		return $query;
	}

	public function getLog($id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		$sql = 'SELECT log FROM log WHERE id_cliente = :id_cliente ORDER BY idlog DESC LIMIT 1';

		$query =  $conn->prepare($sql);
		$query -> bindValue(':id_cliente', $id_cliente);				

		$query -> execute();
		$rows = $query->rowCount();

		return $query;
	}

	public function getVariacao(){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		// $sql = 'SELECT * FROM variacao 
		// WHERE id_variacao IN(
		// 	SELECT MAX(id_variacao) FROM variacao GROUP BY sg ORDER BY id_variacao DESC
		// )GROUP BY sg ORDER BY id_variacao DESC LIMIT 12';

		$sql ='SELECT * FROM(
				SELECT * FROM  variacao WHERE preco IN(
							SELECT MIN(preco) FROM variacao WHERE sg IS NOT NULL GROUP BY sg,`data` ORDER BY id_variacao DESC
						)
			UNION
				SELECT * FROM  variacao WHERE preco IN(
						SELECT MAX(preco) FROM variacao WHERE sg IS NOT NULL GROUP BY sg,`data` ORDER BY id_variacao DESC
				)GROUP BY sg,`data` ORDER BY id_variacao DESC 
				)AS preco LIMIT 2,2';

		$query =  $conn->prepare($sql);
		$query -> execute();
		$rows = $query->rowCount();

		while($tbl = $query->fetch(PDO::FETCH_OBJ)){
			$preco_min[] = $tbl->preco;
			$sg[] = $tbl->sg;
		}

		

		$sql ='SELECT * FROM variacao WHERE id_variacao IN(
			SELECT MAX(id_variacao) FROM variacao GROUP BY sg ORDER BY id_variacao DESC
		)GROUP BY sg ORDER BY id_variacao DESC LIMIT 1,3';

		$query =  $conn->prepare($sql);
		$query -> execute();
		$rows = $query->rowCount();

		while($tbl = $query->fetch(PDO::FETCH_OBJ)){
			$var_array[] = $tbl->preco;
			$array_hora[] = $tbl->sg;
			$array_hora2[] = $tbl->hora;
		}

		$hora_preco1 = $array_hora[0];
		$hora_preco2 = $array_hora[1];
		$hora_preco3 = $array_hora[2];


		$prosegue1 = $hora_preco1 - $hora_preco2;
		$prosegue2 = $hora_preco2 - $hora_preco3;
		$prosegue3 = date('H:i:s');
		$prosegue3 = explode(':', $prosegue3);
		$prosegue4 = $prosegue3[0];
		$prosegue3 = $prosegue3[0] - $hora_preco1;


		$explode_hora = explode(':',$array_hora2[0]);
		$explode_hora1 = $explode_hora[1];

		

		$teste = $preco_min[0];

		// $teste = array($hora_preco1,$hora_preco2,$hora_preco3);

		if($preco_min[0] > $preco_min[1]):
			$precoMin =  $preco_min[1];
		else:
			$precoMin =  $preco_min[0];
		endif;
		$diferencaPreco = $var_array[0] - $precoMin;


		if($sg[0] == $sg[1] &&  $var_array[0] > $precoMin && $prosegue1 == 1 && $prosegue2 == 1 && $prosegue3 == 1 && $prosegue4 != 0):	

			$melhorPreco = $var_array[0] - $precoMin;
			$melhorPreco = $melhorPreco / 3;
			$melhorPrecoAux = $precoMin + $melhorPreco;
		else:
			$melhorPrecoAux = null;		
		endif;

		return compact('var_array','melhorPrecoAux','explode_hora1','diferencaPreco','precoMin');
	}


	public function updateOP($show,$ativo,$criterio){
		$conn = new Conexao;
		$conn = $conn->getConexao();
 
	    $sql = 'UPDATE operacao SET `show`=:show,criterio=:criterio,ativo=:ativo WHERE id = 1 LIMIT 1';

		$query = $conn->prepare($sql);	
		$query -> bindValue(':show', $show);
		$query -> bindValue(':criterio', $criterio);
		$query -> bindValue(':ativo', $ativo);
		$query -> execute();
	}

	// INSERE AS OPERACOES NO BANCO DE DADOS
	public function insertOP($precoAtual,$preco_calculado,$criterioB,$saldo,$saldoAtual,$valor_mercado,$id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		date_default_timezone_set('America/Sao_Paulo');

				$hora = date('H:i:s');
				$data = date('Y-m-d');

				// $id_cliente = $_SESSION['id_cliente'];
				$sql = 'SELECT * FROM transacao where criterio = 1 and data < :data AND id_cliente = :id_cliente  group by data order by id DESC LIMIT 1';
				$query = $conn->prepare($sql);	
				$query -> bindValue(':id_cliente', $id_cliente);
				$query -> bindValue(':data', $data);								
				$query -> execute();
				$tblCompra = $query->fetch(PDO::FETCH_OBJ);
				$valorAnterior = $tblCompra->saldo;

				if($criterioB == 3 || $criterioB == 4 || $criterioB == 7):
			    	$sql = 'INSERT INTO transacao (hora,data,criterio,preco_atual,preco_calculado,saldo_bitcoin,saldo_anterior,saldo_atual,valor_mercado,id_cliente) VALUES (:hora,:data,:criterioB,:precoAtual,:preco_calculado,:saldo,:valorAnterior,:saldoAtual,:valor_mercado,:id_cliente)';					
				else:
			    	$sql = 'INSERT INTO transacao (hora,data,criterio,preco_atual,preco_calculado,saldo,saldo_anterior,saldo_atual,valor_mercado,id_cliente) VALUES (:hora,:data,:criterioB,:precoAtual,:preco_calculado,:saldo,:valorAnterior,:saldoAtual,:valor_mercado,:id_cliente)';					
				endif;		 

				$query = $conn->prepare($sql);	
				$query -> bindValue(':hora', $hora);
				$query -> bindValue(':data', $data);
				$query -> bindValue(':criterioB', $criterioB);
				$query -> bindValue(':preco_calculado', $preco_calculado);
				$query -> bindValue(':saldo', $saldo);
				$query -> bindValue(':valor_mercado', $valor_mercado);
				$query -> bindValue(':id_cliente', $id_cliente);
				$query -> bindValue(':saldoAtual', $saldoAtual);
				$query -> bindValue(':precoAtual', $precoAtual);
				$query -> bindValue(':valorAnterior', $valorAnterior);
				$query -> execute();

				if($criterioB == 1):

					$criterio = 2;
					$op = $this->updateOP(1,$precoAtual,$criterio);

				elseif($criterioB == 3 || $criterioB == 4 || $criterioB == 7):
					
					$criterio = null;
					$op = $this->updateOP(0,$precoAtual,$criterio);

				endif;

	}

	public function insertVariacao($preco){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		date_default_timezone_set('America/Sao_Paulo');

				$hora = date('H:i:s');
				$data = date('Y-m-d');

				$sg = explode(':',$hora);
				$sg = $sg[0];

				$preco = number_format($preco, 2, '.', '');

				$sql = 'SELECT preco FROM variacao WHERE preco = :preco AND sg = :sg ORDER BY id_variacao DESC LIMIT 1';
				$query = $conn->prepare($sql);	
					$query -> bindValue(':sg', $sg);
				
					$query -> bindValue(':preco', $preco);

				$query -> execute();
				$rows = $query->rowCount();


				if($rows == 0 && $preco > 0):

					$sql = 'INSERT INTO variacao (preco,data,hora,sg) VALUES (:preco,:data,:hora,:sg)';	 

					$query = $conn->prepare($sql);	
					$query -> bindValue(':hora', $hora);
					$query -> bindValue(':data', $data);
					$query -> bindValue(':sg', $sg);
					$query -> bindValue(':preco', $preco);
					
					$query -> execute();
				endif;
					


	}

	// CANCELA AS ORDENS
	public function CancelarOrdem($precoAtual,$criterioB,$saldo,$id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		date_default_timezone_set('America/Sao_Paulo');

				$hora = date('H:i:s');
				$data = date('Y-m-d');


			    	$sql = 'INSERT INTO transacao (hora,data,criterio,preco_atual,saldo,id_cliente) VALUES (:hora,:data,:criterioB,:precoAtual,:saldo,:id_cliente)';
		 

				$query = $conn->prepare($sql);	
				$query -> bindValue(':hora', $hora);
				$query -> bindValue(':data', $data);
				$query -> bindValue(':criterioB', $criterioB);
				$query -> bindValue(':id_cliente', $id_cliente);				
				$query -> bindValue(':saldo', $saldo);
				$query -> bindValue(':precoAtual', $precoAtual);
				$query -> execute();

				$log = $this->log(6,$criterioB,$id_cliente);						


	}

	// GUARDA NO BANCO TODOS OS LOGS
	public function log($cod,$criterio,$id_cliente){
		$conn = new Conexao;
		$conn = $conn->getConexao();

		date_default_timezone_set('America/Sao_Paulo');

				$hora = date('H:i:s');
				$data = date('Y-m-d');
		 
			    $sql = 'INSERT INTO log (hora,data,log,criterio,id_cliente) VALUES (:hora,:data,:cod,:criterio,:id_cliente)';

				$query = $conn->prepare($sql);	
				$query -> bindValue(':hora', $hora);
				$query -> bindValue(':data', $data);
				$query -> bindValue(':cod', $cod);
				$query -> bindValue(':id_cliente', $id_cliente);								
				$query -> bindValue(':criterio', $criterio);
				$query -> execute();

	}

	// AQUI ONDE A GRAÇA ACONTECE
	public function efetuarTransacao($precoAtual,$melhorPreco,$criterioB,$preco_calculado,$saldoREAL,$saldoBTC,$valor_mercado,$oco,$api_key,$api_secret,$id_cliente,$precoAtualBug){


		$request_body1['timestamp'] = time();


				$melhorPrecoCompra = $melhorPreco - 2;
				$calculoCompra = $precoAtual - $melhorPrecoCompra;


		if( $criterioB == 1):

					// if($id_cliente == 53):
					// 	$melhorPreco = $melhorPreco + 10;
					// else:
					// 	$melhorPreco = $melhorPreco + 9.8;
					// endif;

					// $melhorPrecoCompra = $melhorPrecoCompra + 1;	
					$saldoAtual = $saldoREAL;
					$saldoBTC   = $saldoAtual / $melhorPreco;
					// $saldoBTC_aux   = $saldoAtual / $melhorPreco;
					// $saldoAtual = $saldoREAL / 3;
					// $stopLoss = $melhorPrecoCompra - 650;


				    $request_body1['mode']   = 'limit';
				    $request_body1['type']   = 'buy';
				    $request_body1['amount'] = $saldoBTC;
				    // $request_body1['price_stop'] = number_format($stopLoss, 2, '.', '');				    
				    $request_body1['price']  = number_format($melhorPreco, 2, '.', '');
				    // $request_body1['price_oco'] = $oco;


				    // $request_body1['total'] = 0;


				    $request_body1 = http_build_query($request_body1);

				    $api_post['api_key'] = $api_key;
				    $api_post['request_body'] = $request_body1;
				    $api_post['signature'] = hash_hmac('sha256', $request_body1, $api_secret);


				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/order_new');
				    curl_setopt($ch, CURLOPT_POST, true);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				    $result = curl_exec($ch);
				    $ORDEM =  json_decode($result,true);

				    if(is_array ($ORDEM)):
						$insert = $this->insertOP($melhorPreco,$preco_calculado,$criterioB,$saldoREAL,$saldoAtual,$valor_mercado,$id_cliente);
						$log = $this->log(200,$criterioB,$id_cliente);					
					else:
						$log = $this->log($ORDEM,$criterioB,$id_cliente);						
					endif;
					 return true;


		elseif($criterioB == 3 && $saldoBTC > 0): 
			$melhorPrecoVenda = $melhorPreco;
					$stopLoss = $precoAtualBug - 700;


				    $request_body1['mode'] = 'limit';
				    $request_body1['type'] = 'sell';
				    $request_body1['amount'] = $saldoBTC;
				    $request_body1['price_oco'] = number_format($stopLoss, 2, '.', '');				    
				    $request_body1['price'] = number_format($melhorPrecoVenda, 2, '.', '');

				    // $request_body1['amount'] = 0;


				    $request_body1 = http_build_query($request_body1);

				    $api_post['api_key'] = $api_key;
				    $api_post['request_body'] = $request_body1;
				    $api_post['signature'] = hash_hmac('sha256', $request_body1, $api_secret);


				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/order_new');
				    curl_setopt($ch, CURLOPT_POST, true);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				    $result = curl_exec($ch);
				    $ORDEM =  json_decode($result,true);

				    if(is_array ($ORDEM)):

						// $saldoBRL = $this->getSaldo();	
						$saldoAtual = null;

						$insert = $this->insertOP($melhorPrecoVenda,$preco_calculado,$criterioB,$saldoBTC,$saldoAtual,$valor_mercado,$id_cliente);	

						$log = $this->log(200,$criterioB,$id_cliente);						
					else:
						$log = $this->log($ORDEM,$criterioB,$id_cliente);						
					endif;


				// return $saldoBTC;

		elseif($criterioB == 4 || $criterioB == 7):

					$request_body3['timestamp'] = time();

					$request_body3['type'] = 'all';
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


			    if($saldoBTC > 0 && $saldoBTC != null):
					$request_body1['timestamp'] = time();

					$request_body1['mode'] = 'market';
				    $request_body1['type'] = 'sell';
				    $request_body1['amount'] = $saldoBTC;
				    $request_body1 = http_build_query($request_body1);
				    $api_post['api_key'] = $api_key;
				    $api_post['request_body'] = $request_body1;
				    $api_post['signature'] = hash_hmac('sha256', $request_body1, $api_secret);


				    $ch = curl_init();
				    curl_setopt($ch, CURLOPT_URL, 'https://bitnuvem.com/tapi/order_new');
				    curl_setopt($ch, CURLOPT_POST, true);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_post);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				    $result = curl_exec($ch);
				    $ORDEM =  json_decode($result,true);

				    if(is_array ($ORDEM)):

						// $saldoBRL = $this->getSaldo();	
						$saldoAtual = null;

						$insert = $this->insertOP($melhorPreco,$preco_calculado,$criterioB,$saldoBTC,$saldoAtual,$valor_mercado,$id_cliente);	

						$log = $this->log(200,$criterioB,$id_cliente);						
					else:
						$log = $this->log($ORDEM,$criterioB,$id_cliente);						
					endif;
				endif;

		endif;

					 


		
	}
}

// $op = new Operacao;
// $op = $op->getOperacao();

// // echo $op;

// 	$lista = $op->fetch(PDO::FETCH_OBJ);
// 		echo $lista->criterio;	
	
