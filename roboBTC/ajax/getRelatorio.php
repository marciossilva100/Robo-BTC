<?php
require_once('../model/Conexao.php');
$conn = new Conexao;
$conn = $conn->getConexao();
$id_cliente = $_POST['id_cliente'];
// $id_cliente = 2;
$html1 = '';

  		$sql = 'SELECT * FROM transacao t 
              INNER JOIN criterio  c ON(c.id_criterio=t.criterio)
              INNER JOIN cliente  cl ON(cl.idcliente = t.id_cliente)
              WHERE t.id_cliente = :id_cliente AND cl.ativo = 1  ORDER BY t.id DESC LIMIT 40';

  		$query = $conn->prepare($sql);
      $query -> bindValue(':id_cliente', $id_cliente);

  		$query->execute();

  		$rows = $query->rowCount();


  			$i = $rows;
  	 while($tbl = $query->fetch(PDO::FETCH_OBJ)){ 


  	 	$data = date("d/m/Y", strtotime($tbl->data));


      if($tbl->criterio == 3 || $tbl->criterio == 4):
        $saldo = $tbl->saldo_bitcoin;
      else:
        $saldo = $tbl->saldo_atual;
      endif;

  	 	if($tbl->criterio_op == 'Compra'):
  	 		$text_op = '<span class="text-secondary">Compra</span>';
  	 		$diferenca = '';
  	 	elseif($tbl->criterio_op == 'Venda'):
  	 		$text_op = '<span class="text-success"><b>Venda</b></span>'; 
  	 		$diferenca = $tbl->preco_calculado;
  	 	elseif($tbl->criterio_op == 'StopLoss venda'):
  	 		$text_op = '<span class="text-danger"><b>StopLoss Venda</b></span>'; 
  	 		$diferenca = $tbl->preco_calculado;
      elseif($tbl->criterio_op == 'Venda a Mercado'):
        $text_op = '<span class="text-danger"><b>Venda a Mercado</b></span>'; 
        $diferenca = $tbl->preco_calculado; 	 			 		
      elseif($tbl->criterio_op == 'Ordem cancelada'):
        $text_op = '<span class="text-danger"><b>Ordem cancelada</b></span>'; 
        $diferenca = null;           
  	 	endif;

	    $html1 .="<tr>
	      <th scope='row'>$i</th>
	      <td>$data</td>
	      <td>$tbl->hora</td>
	      <td>$text_op</td>
        <td>$tbl->preco_atual</td>
        <td>$saldo</td>
	    </tr>";
 $i--; } 




 $sql = 'SELECT * FROM transacao where criterio = 1 AND id_cliente = :id_cliente group by data order by id desc';
        $query =  $conn->prepare($sql);
        $query -> bindValue(':id_cliente', $id_cliente);
        $query -> execute();
        $rows = $query->rowCount();

        $i = $rows; 
        $html2 = '';
        while ($tbl = $query->fetch(PDO::FETCH_OBJ)) {
                // $data = date("d/m/Y", strtotime($tbl->data));
                $data =  date('d/m/Y', strtotime('-1 days', strtotime($tbl->data)));

                $saldoAnterior = $tbl->saldo - $tbl->saldo_anterior;
                $saldoAnterior =  ($saldoAnterior / $tbl->saldo_anterior) * 100;
                if($saldoAnterior < 0):
                 $dif = 'class="text-danger"';
                elseif($saldoAnterior == 0):
                 $dif = 'class="text-warning"';
                else:
                 $dif = 'class="text-success"';                              
                endif;

              $html2 .="<tr>
              <th scope='row'>$i</th>
              <td>$data</td>
              <td><span class='text-light' >$tbl->saldo</span></td>
              <td><span $dif >".number_format($saldoAnterior, 2, '.', '')." %</span></td>
              </tr>";
              $i--;

        }

echo json_encode(compact('html1','html2'));

header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, cachehack=".time());
header("Cache-Control: no-store, must-revalidate");
header("Cache-Control: post-check=-1, pre-check=-1", false);
?>

