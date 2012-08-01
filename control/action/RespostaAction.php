<?
	require_once '../../conf/config.php';

	class RespostaAction {
	
		function RespostaAction() {
			
			session_start();
			
			/**
			* Cadastra Respostas
			*/
			if (isset($_POST['BTEnviar'])) {
				$query = pg_query('select * from perguntas where id_formulario='.$_SESSION['upd_formulario'].'');
				while($row = pg_fetch_object($query)) {
					pg_query('insert into respostas (id_pergunta, id_usuario, resposta) VALUES ('.$row->id_pergunta.','.$_SESSION['id_usuario_logado'].',\''.$_POST[$row->id_pergunta].'\')');
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consFormulario.php\">";
			}
			
		}
		
	}
	
	$controle = new RespostaAction();
	
?>