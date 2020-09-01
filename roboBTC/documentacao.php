<!DOCTYPE html>
<html>
<head>
	<title>Documentacão mhp bot</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<meta charset="utf-8">
</head>
<body>
	<div class="container">
		<?php
		ini_set("session.save_path", "session/");

// ini_set('session.save_path', '/minhas_sessions/');
ini_set('session.gc_maxlifetime', '172800');
ini_set('session.gc_probability', 1);
session_set_cookie_params(172800);
session_start();

require_once('include/menu.php');
		?>
<div class="accordion" id="accordionExample"  style="margin-top: 100px">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <b>Funcionamento do robô</b>
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
        O robô de operação se baseia na compra e venda de bitcoin, sou seja, compra na baixa e venda na alta.<br> É possível que o robô realize várias operações em um único dia com ordens a Limite e a Mercado(se for necessário)<br> Para que o robô funcione precisa estar ligado, ou seja funciona somente com um computador(ou celular) ligado e conectado a internet.<br> Ao realizar o cadastro e logo após fazer o login a tela de relatorio precisa estar aberta caso contrario o robô não funcionará.<br>
        Trabalhamos em parceria com a corretora <a href="https://bitnuvem.com/" target="_blank">Bitnuvem</a>, é preciso realizar um cadastro na mesma para o robô funcionar.
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <b>Riscos</b>
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        O bitcoin sofre exatamente esse reflexo no que diz respeito à valorização da moeda. A volatilidade é muito alta, o que expõe ainda mais o risco de investir altos valores nesse tipo de mercado. Assim como em outras moedas os riscos de se investir em bitcoin também são claros.
        O robô também prevê os riscos existentes e toma medidas para que a perda seja a mímina possível com ordens de stop loss.<br> Comparado com alguns tipos de investimentos o <b>MHP BOT</b> possui menor risco.
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <b>Cadastro</b>
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
        Para começar a operar através do <b>MHP BOT</b> deve-se fazer um cadastro na corretora <a href="https://bitnuvem.com/" target="_blank"><b>Bitnuvem</b></a> e seguir os seguintes passos:<br>
        Depois de se cadastrar na corretora e realizar todos os procedimentos de segurança que a corretora pede, deve então criar dentro do painel no menu <u>configurações,</u> duas chaves de segurança.
        
    
        <div class="text-center">
        	<img src="img/img1.png" style="width: 80%;margin-top: 40px">
        	<img src="img/img2.png" style="width: 80%;margin-top: 50px">
        	<img src="img/img3.png" style="width: 80%;margin-top: 50px">
        </div> <br><br>
      
 		
        Depois de seguir todos os passos e criar as duas chaves pode então entrar na página de cadastro da <b>MHP BOT</b> e preencher todos os campos, logo após você será redirecionado para a página de login.<br> Na página de login serão solicitados o email, a senha de acesso da <b>MHP BOT</b>  que você criou e as duas chaves de segurança que foi criado na <a href="https://bitnuvem.com/" target="_blank"><b>Bitnuvem</b></a>.<br>Não guardamos qualquer chave de segurança conosco, a solicitação da chave é justamente para ter acesso ao sistema da <a href="https://bitnuvem.com/" target="_blank"><b>Bitnuvem</b></a> por isso guarde as chaves num local seguro pois sempre serão solicitadas ao fazer o login.<br><br>
        <!-- <img src="img/img1.png" style="width: 200px"> -->
      </div>
    </div>


  </div>

  <div class="card">
    <div class="card-header" id="headingFour">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          <b>Ganhos</b>
        </button>
      </h2>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
      <div class="card-body">
        Não existe valores definidos de ganhos diários pois o robô faz várias operações por dia e vai depender do volume de negociações e volatividade do mercado.
      </div>
    </div>

    
  </div>
</div>
</div>
</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>