<?php
ini_set("session.save_path", "../session/");

// ini_set('session.save_path', '/minhas_sessions/');
ini_set('session.gc_maxlifetime', '172800');
ini_set('session.gc_probability', 1);
session_set_cookie_params(172800);


session_start();

$_SESSION = array();
session_destroy();

echo json_encode('sair');


