<?php
require_once('include/header.php');
// require_once('model/Conexao.php');
// echo 'Versão Atual do PHP: ' . phpversion();
error_reporting(0);

// $conn = new Conexao;
// $conn = $conn->getConexao();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item active">
        <a class="nav-link" href="documentacao.php" target="_blank"><small><b class="text-danger">Documentacao</b></small></a>
      </li>
    </ul>

  </div>
</nav>

<div class="row" style="padding-top: 200px">
	<div class="col-md-4 " ></div>
	<div class="col-md-4 " >
		
		  <div class="form-group">
		    <label for="InputEmail">Email</label>  
		    <input type="email" class="form-control" id="InputEmail" aria-describedby="email" value="">
		    <small id="email" class="form-text text-danger msg-error" ></small>
		  </div>

		  <div class="form-group">
		    <label for="Password1">Senha de Acesso</label>
		    <input type="password" class="form-control" id="Password1" value="">
		    <small id="small-senha" class="form-text text-danger" ></small>
		  </div>

		  <div class="form-group">
		    <label for="api-key">Chave da API</label>  
		    <input type="text" class="form-control" id="api-key" aria-describedby="api-key" value="">
		    <small id="small-api-key" class="form-text text-danger msg-error" ></small>	    
		  </div>

		  <div class="form-group">
		    <label for="api-secret">Segredo da API</label>  
		    <input type="text" class="form-control" id="api-secret" aria-describedby="api-secret" value="">
		    <small id="small-api-secret" class="form-text text-danger msg-error" ></small>	    
		  </div>

		  <button type="submit" class="btn btn-primary">Entrar</button><br><br>

		  <a href="cadastro.php"><small>Cadastre-se</small></a>
		
	</div>
	<div class="col-md-4"></div>
</div>
<div class="row " style="padding-top: 50px">
	<div class="col-md-2"></div>
	<div class="col-md-8 alerta text-center"></div>
	<div class="col-md-2"></div>
</div>
</div>
</body>

<?php

require_once('include/footer.php');

?>

<script type="text/javascript">



		$(document).ready(function(){

			$("button").click(function(){

				email = $("#InputEmail").val();
				password1 = $("#Password1").val();
				api_key = $("#api-key").val();
				api_secret = $("#api-secret").val();

				
						dados = 'email='+email+'&password1='+password1+'&api_key='+api_key+'&api_secret='+api_secret;

						// console.log(dados);

						if(email == ""){
							$('#email').html('Digite seu email');				
						}else{
							$('#email').empty();									
						}

						if(password1 == ""){
							$('#small-senha').html('Digite a senha');					
						}else{
							$('#small-senha').empty();								
						}

						if(api_key == ""){
							$('#small-api-key').html('Digite a chave api');					
						}else{
							$('#small-api-key').empty();								
						}

						if(api_secret == ""){
							$('#small-api-secret').html('Digite o segredo da chave');					
						}else{
							$('#small-api-secret').empty();								
						}


					if(email != ""  && password1 != "" && api_secret != "" && api_key != ""){ 	

						$.ajax({
							url:'ajax/login.php',
							data:dados,
							type:'POST',
							dataType:'json',
							success: function(result){
								// $('.alerta-email').remove();
								// alert(result['rows']);
								window.location.href = "relatorio.php";

							},
							error: function(result){
								console.log(result);
								msg = result.responseText;
								console.log(msg);
								// msg = 'Dados incorretos';

								if(msg != ""){
								// msg = 'Já existe este email cadastrado, escolha outro';
								$('.alerta').html('<div class="alert alert-danger alerta-email" role="alert">'+msg+'</div>');
								$('.alerta-email').fadeOut(5000);
								}
								// alert('error');
							}
						});
					}
			});
				

});
</script>
