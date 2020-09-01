<?php

ini_set("session.save_path", "../session/");

// ini_set('session.save_path', '/minhas_sessions/');
ini_set('session.gc_maxlifetime', '172800');
ini_set('session.gc_probability', 1);
session_set_cookie_params(172800);

session_start();

require_once('../model/Conexao.php');

$conn = new Conexao;
$conn = $conn->getConexao();

extract($_POST);

// $password1 = '1234';
// $email = 'marciosunico19@gmail.com';

$senha  = md5($password1);

$sql = 'SELECT * FROM cliente WHERE email = :email AND password = :senha';

$query = $conn->prepare($sql);
$query->bindValue(':email',$email);
$query->bindValue(':senha',$senha);
$query->execute();
$rows = $query->rowCount();

$credencial = $query->fetch(PDO::FETCH_OBJ);

// echo $rows;
try{

	if(!empty($rows)):
		$_SESSION['email'] = $credencial->email;
		$_SESSION['api_key'] = $api_key;
		$_SESSION['api_secret'] = $api_secret;
		$_SESSION['id_cliente'] = $credencial->idcliente;
		$_SESSION['ativo'] = $credencial->ativo;

		echo json_encode(compact('rows'));
	else:
		throw new Exception("Email ou senha incorreta");		
	endif;

}catch(Exeption $e){

	$msg_error = $e->getMessage();
	echo json_encode(compact('msg_error'));
}


