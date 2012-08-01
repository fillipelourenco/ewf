<?
	session_start();
	
	require_once 'conf/config.php';
	require_once 'util/ChecaSessao.php';
	
	if ($_SESSION['tipo_usuario_logado'] == '3'){
		header("Location: view/feedback.php");
	}
	else {
		header("Location: view/gerencia.php");
	}
	
?>