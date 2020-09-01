<?php
require_once('include/header.php');
require_once('model/Conexao.php');
// echo 'Versão Atual do PHP: ' . phpversion();

$conn = new Conexao;
$conn = $conn->getConexao();
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
<div class="row " style="padding-top: 50px">
	<div class="col-md-2"></div>
	<div class="col-md-8 alerta text-center"></div>
	<div class="col-md-2"></div>
</div>
<div class="row" >
	<div class="col-md-4 " style="padding-top: 50px"></div>
	<div class="col-md-4 " >
		
		  <div class="form-group">
		    <label for="InputNome">Nome df</label>
		    <input type="text" class="form-control" id="InputNome" aria-describedby="nome">
		    <small id="nome" class="form-text text-danger" ></small>
		  </div>
		  <div class="form-group">
		    <label for="InputEmail">Email</label>  
		    <input type="email" class="form-control" id="InputEmail" aria-describedby="email">
		    <small id="email" class="form-text text-danger msg-error" ></small>
		  </div>
<!-- 		  <div class="form-group">
		    <label for="api-key">Chave da API</label>  
		    <input type="text" class="form-control" id="api-key" aria-describedby="api-key">
		    <small id="small-api-key" class="form-text text-danger msg-error" ></small>	    
		  </div>
		  <div class="form-group">
		    <label for="api-secret">Segredo da API</label>  
		    <input type="text" class="form-control" id="api-secret" aria-describedby="api-secret">
		    <small id="small-api-secret" class="form-text text-danger msg-error" ></small>	    

		  </div> -->
		  <div class="form-group">
		    <label for="Password1">Senha de Acesso</label>
		    <input type="password" class="form-control" id="Password1">
		    <small id="small-senha" class="form-text text-danger" ></small>

		  </div>
		  <div class="form-group">
		    <label for="Password2">Confirmação de senha</label>
		    <input type="password" class="form-control" id="Password2">
		    <small id="small-senha2" class="form-text text-danger" ></small>
		  </div>
		  <button type="submit" class="btn btn-primary">Cadastrar</button>
		
	</div>
	<div class="col-md-4"></div>
</div>

</div>
</body>

<?php

require_once('include/footer.php');

?>

<script type="text/javascript">



		$(document).ready(function(){

			$("button").click(function(){
				nome = $("#InputNome").val();
				email = $("#InputEmail").val();
				// api_key = $("#api-key").val();
				// api_secret = $("#api-secret").val();
				password1 = $("#Password1").val();
				password2 = $("#Password2").val();

				
				// console.log(dados);

				if(nome == ""){
					$('#nome').html('Campo Nome não pode estar vazio');				
				}else{
					$('#nome').empty();									
				}

				if(email == ""){
					$('#email').html('Campo Email não pode estar vazio');				
				}else{
					$('#email').empty();									
				}

				// if(api_key == ""){
				// 	$('#small-api-key').html('Campo Chave da API não pode estar vazio');				
				// }else{
				// 	$('#small-api-key').empty();									
				// }

				// if(api_secret == ""){
				// 	$('#small-api-secret').html('Campo Segredo da API não pode estar vazio');					
				// }else{
				// 	$('#small-api-secret').empty();									
				// }

				if(password1 == ""){
					$('#small-senha').html('Campo Senha não pode estar vazio');					
				}else{
					$('#small-senha').empty();								
				}

				if(password2 == ""){
					$('#small-senha2').html('Campo Senha não pode estar vazio');					
				}else{
					$('#small-senha2').empty();									
				}
				// || email == undefined || api_key == undefined || api_secret == undefined){

				// }

				if(nome != "" && email != "" && password1 != "" && password2 != ""){ 

					if(password1 == password2){	

						// dados = 'nome='+nome+'&email='+email+'&api_key='+api_key+'&api_secret='+api_secret+'&password1='+password1+'&password2'+password2;
						dados = 'nome='+nome+'&email='+email+'&password1='+password1+'&password2'+password2;

						$.ajax({
							url:'ajax/cadastro.php',
							data:dados,
							type:'POST',
							dataType:'json',
							success: function(result){
								// $('.alerta-email').remove();
								// alert(result);
								window.location.href = "login.php";

							},
							error: function(result){
								console.log(result.responseText);
								msg = result.responseText;

								if(msg != ""){
								msg = 'Já existe este email cadastrado, escolha outro';
								$('.alerta').html('<div class="alert alert-danger alerta-email" role="alert">'+msg+'</div>');
								$('.alerta-email').fadeOut(5000);
								}
								// alert('error');
							}

						});
					}else{
						$('#small-senha').html('SENHAS NÃO CONFEREM');
					}
				}
			});
				

});
</script>
