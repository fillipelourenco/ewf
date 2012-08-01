<?
	require_once '../../conf/config.php';

	class NotificacaoAction {
	
		function NotificacaoAction() {
			
			session_start();
			
			/**
			* Ação do Botão Voltar para Tela Inicial de Feedback
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/feedback.php\">";
			}
			
			/**
			* Ação do Botão Visualizar Notificação
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_notificacao'] = $_POST['BTUpd'];
				header("Location: ../../view/cadastro/cadNotificacao.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Requisição
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$notificacao = new Notificacao;
					$notificacao->get($_POST['BTDel']);
					$notificacao->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Impossível deletar registro!\\n\\nEsta Requisição pode está sendo usado em alguma relação.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consNotificacao.php\">";
			}
			
			/**
			* Ação do Botão/JavaScript Visualizar Notificação
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