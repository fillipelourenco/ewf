<?
	require_once '../../conf/config.php';

	class NotificacaoAction {
	
		function NotificacaoAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o Voltar para Tela Inicial de Feedback
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/feedback.php\">";
			}
			
			/**
			* A��o do Bot�o Visualizar Notifica��o
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_notificacao'] = $_POST['BTUpd'];
				header("Location: ../../view/cadastro/cadNotificacao.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Deletar Requisi��o
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$notificacao = new Notificacao;
					$notificacao->get($_POST['BTDel']);
					$notificacao->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Imposs�vel deletar registro!\\n\\nEsta Requisi��o pode est� sendo usado em alguma rela��o.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consNotificacao.php\">";
			}
			
			/**
			* A��o do Bot�o/JavaScript Visualizar Notifica��o
			*/
			if (!empty($_POST['upd_notificacao'])){
				session_start();
				$_SESSION['upd_notificacao'] = $_POST['upd_notificacao'];
				header("Location: ../../view/cadastro/cadNotificacao.php");
				exit;
			}
			
		}
		
	}
	
	$controle = new NotificacaoAction();
	
?>