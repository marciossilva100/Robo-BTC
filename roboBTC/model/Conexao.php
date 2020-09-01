<?php
// session_cache_limiter('private');
// session_save_path('../session/');


class Conexao{

	public function getConexao(){
		try {
			$username = 'root';
			$password = '';

		  $conn = new PDO('mysql:host=localhost;dbname=op2', $username, $password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    return $conn;
		} catch(PDOException $e) {
		    echo 'ERROR: ' . $e->getMessage();
		}

	}
}

// $con = new Conexao;

// $con = $con->getConexao();

// print_r($con);