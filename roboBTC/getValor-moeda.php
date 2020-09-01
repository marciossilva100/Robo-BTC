<?php
error_reporting(1);
require_once('include/header.php');
require_once('model/Conexao.php');
$conn = new Conexao;
$conn = $conn->getConexao();

$usuario =  array();
array_push($usuario,'Marcios','Marinaldo','Jose');
// $usuario['api_key'] =
// $api_secret['api_secret']

foreach ($usuario as $value) {
	echo $value.'<br>';
}
?>
<div class="row">
<div class="col-md-8">
<div style="max-height: 505px;overflow-x: scroll;">
	<table class="table table-dark">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Cliente</th>
	      <th scope="col">Saldo</th>
	    </tr>
	  </thead>
	  <tbody class="tabela-op">
	  	<?=$html1?>
	  </tbody>
	</table>
</div>
</div>
</div>


