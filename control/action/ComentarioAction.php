<?
	require_once '../../conf/config.php';

	class ComentarioAction {
	
		function ComentarioAction() {
			
			session_start();
			
			/**
			* Cadastro de Coment�rio
			*/
			if (isset($_POST['BTCom'])) {
				$comentario = new Comentario;
				$_POST['id_tarefa'] = $_SESSION['upd_tarefa'];
				$_POST['id_usuario'] = $_SESSION["id_usuario_logado"];
				$comentario->setFrom($_POST);
				$comentario->save();
			}
			
			/**
			* Exclus�o de Coment�rio
			*/
			if (isset($_POST['BTDelCom'])) {
				try{
					$comentario = new Comentario;
					$comentario->get($_POST['BTDelCom']);
					$comentario->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Imposs�vel deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
			}
			
		}
		
	}
	
	$controle = new ComentarioAction();
	
?>
