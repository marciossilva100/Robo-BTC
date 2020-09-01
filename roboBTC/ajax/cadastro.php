<?php
require_once('../model/Conexao.php');

$conn = new Conexao;
$conn = $conn->getConexao();

extract($_POST);


// $api_key = md5($api_key);
// $api_secret = md5($api_secret);
// $nome ='teste';
// $email ='teste@kjhk';
// $password1 ='123';
$senha = md5($password1);
date_default_timezone_set('America/Sao_Paulo');

$data_limite = date("Y-m-d", strtotime("+15 days"));
$ativo = 1;

$sql = 'INSERT INTO cliente (nome,email,password,ativo,data_limite) VALUES (:nome,:email,:senha,:ativo,:data_limite)';

$query = $conn->prepare($sql);

$query->bindValue(':nome',$nome);
$query->bindValue(':email',$email);
$query->bindValue(':senha',$senha);
$query->bindValue(':ativo',$ativo);
$query->bindValue(':data_limite',$data_limite);

$query->execute();

echo json_encode('Dados inseridos com sucesso');