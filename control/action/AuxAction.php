<?
	require_once '../conf/config.php';

	class AuxAction {
	
		function AuxAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o/JavaScript Abrir Avalia��o
			*/
			if (!empty($_POST['read_formulario'])) {
				$_SESSION['read_formulario'] = $_POST['read_formulario'];
				header("Location: ../../view/graficos/avaliacoes.php");
				exit;
			}
						
		}
		
	}
	
	$controle = new AuxAction();
	
?>