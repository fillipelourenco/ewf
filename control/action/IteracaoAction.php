<?
	require_once '../../conf/config.php';

	class IteracaoAction {
	
		function IteracaoAction() {
			
			session_start();
			
			/**
			* Cadastro de Iteração
			*/
			if (isset($_POST['BTRes'])) {
				$iteracao = new Iteracao;
				$_POST['id_requisicao'] = $_SESSION['upd_requisicao'];
				$_POST['id_usuario'] = $_SESSION["id_usuario_logado"];
				$iteracao->setFrom($_POST);
				$iteracao->save();
				if ($_SESSION['id_usuario_logado'] != $_SESSION['obj_requisicao']['id_usuario_cadastro']) {
					$titulo = 'Seu Feedback '.$_SESSION['obj_requisicao']['chave'].' foi respondido';
					$mensagem = 'O Feedback '.$_SESSION['obj_requisicao']['chave'].' foi respondido. Para acessar vá até o menu Feedback > Consultar e clique no Botão de Atualizar/Visualizar do Feedback '.$_SESSION['obj_requisicao']['chave'].'';
					
					pg_query('insert into notificacoes (titulo,mensagem,id_usuario_remetente,id_usuario_destinatario) values (\''.$titulo.'\',\''.$mensagem.'\','.$_SESSION['obj_requisicao']['id_usuario_cadastro'].','.$_SESSION['obj_requisicao']['id_usuario_cadastro'].')');
				}
			}
			
			/**
			* Exclusão de Iteração
			*/
			if (isset($_POST['BTDelRes'])) {
				try{
					$iteracao = new Iteracao;
					$iteracao->get($_POST['BTDelRes']);
					$iteracao->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Impossível deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
			}
			
		}
		
	}
	
	$controle = new IteracaoAction();
	
?>