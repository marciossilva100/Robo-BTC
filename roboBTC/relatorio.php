<?php
ini_set("session.save_path", "session/");

// ini_set('session.save_path', '/minhas_sessions/');
ini_set('session.gc_maxlifetime', '172800');
ini_set('session.gc_probability', 1);
session_set_cookie_params(172800);
session_start();
require_once('include/header.php');
require_once('model/Conexao.php');
// echo 'Versão Atual do PHP: ' . phpversion();

$conn = new Conexao;
$conn = $conn->getConexao();

if(isset($_SESSION['email'])):
	require_once('include/menu.php');

	// echo filesize("relatorio.php");
?>

<br>
<div class="row"><div class="col-md-12 data-limite"></div></div>
<div class="row">
	
		
		<div class="col-md-12 transacao"></div>
	
</div>
<div class="row">
<div class="col-md-8">
<div style="max-height: 505px;overflow-x: scroll;">
	<table class="table table-dark">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Data</th>
	      <th scope="col">Hora</th>
	      <th scope="col">Critério</th>
	      <th scope="col">Ordens</th>
	      <!-- <th scope="col">Valor Mercado</th> -->
	      <!-- <th scope="col">Diferença</th> -->
	      <th scope="col">Valor Negociado</th>
	      <!-- <th scope="col">Saldo</th> -->
	    </tr>
	  </thead>
	  <tbody class="tabela-op"></tbody>
	</table>
</div>
</div>
<div class="col-md-4">
<div style="max-height: 505px;overflow-x: scroll;">
	<table class="table table-dark">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Data</th>
	      <th scope="col">Saldo</th>
	      <th scope="col">%</th>
	      <!-- <th scope="col">Saldo</th> -->
	    </tr>
	  </thead>
	  <tbody class="tabela-op2"></tbody>
	</table>
</div>
</div>
</div>
<input type="hidden" class="id_cliente" value="<?=$_SESSION["id_cliente"]?>">
<input type="hidden" class="ativo" value="<?=$_SESSION["ativo"]?>">
<input type="hidden" class="api_secret" value="<?=$_SESSION["api_secret"]?>">
<input type="hidden" class="api_key" value="<?=$_SESSION["api_key"]?>">

</div>
</body>



<?php

require_once('include/footer.php');

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript">


$(document).ready(function(){
id_cliente= $('.id_cliente').val();
ativo = $('.ativo').val();
api_secret = $('.api_secret').val();
api_key = $('.api_key').val();

			
			function moveItem() {
					dados = 'api_key='+api_key+'&api_secret='+api_secret+'&ativo='+ativo+'&id_cliente='+id_cliente;
						console.log(dados);
				$.ajax({

					url:'robo.php',
					data:dados,
					type:'POST',
					dataType:'json',
					success: function(result){
						$('.mostrar').remove();
						$('.box-btc').remove();
						$('.box-real').remove();
						$('.data-limite-b').remove();
						$('.transacao').append('<div class="text-danger mostrar">'+result['msg']+'</div>');

						if(result['msg'] == 1){
								window.location.href = "login.php";						
						}

						if(result['saldoREAL'] == undefined || result['saldoREAL'] <= 0){
							real = '0.00';
						}else{
							real = result['saldoREAL'];
						}

						if(result['saldoDIp'] == undefined || result['saldoDIp'] == ""){
							saldoBTC = 0.00000000;
						}else{
							saldoBTC = result['saldoDIp'];

						}

						$('.saldoReal').append('<div class="text-light box-real">Saldo em reais:<br> '+real+'</div>');
						$('.saldoBTC').append('<div class="text-light box-btc">Saldo em Bitcoins:<br> '+saldoBTC+'</div>');
						if(result['ativo'] == 1){
							$('.data-limite').append('<b class="text-warning data-limite-b">Seu período de teste expira em: '+result['dateLimit']+'</b>');
						}

					},
					error: function(result){
						// alert('error');
					}

				});

				
						
				}
				setInterval(moveItem,4000);


		$('.sair').click(function(){

			$.ajax({
					url:'ajax/sair.php',
					type:'POST',
					dataType:'json',
					success: function(result){
						
						// $('.tabela-op').html(result['html']);
								window.location.href = "login.php";


					},
					error: function(result){
								msg = result.responseText;
								console.log(result);
						// alert('error');
					}

				});
		});

			function getRelatorio() {
					dados = 'id_cliente='+id_cliente;
						// console.log(dados);
				$.ajax({
					url:'ajax/getRelatorio.php',
					data:dados,
					type:'POST',
					dataType:'json',
					success: function(result){
						
						$('.tabela-op').html(result['html1']);
						$('.tabela-op2').html(result['html2']);

					},
					error: function(result){
						// alert('error');
					}
				});										
				}
				setInterval(getRelatorio,3000);


				 
});
</script>

<?php

else:

	header('Location: login.php');
endif;

header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, cachehack=".time());
header("Cache-Control: no-store, must-revalidate");
header("Cache-Control: post-check=-1, pre-check=-1", false);