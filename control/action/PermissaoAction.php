<?

	class PermissaoAction {
	
		function PermissaoAction() {
			session_start();
			
			/**
			* Restrição do Acesso apenas a Usuários do Tipo Gerente
			*/
			if ($_SESSION["tipo_usuario_logado"] != '1') {
				echo "<script language=Javascript>alert('Você não tem permissão para acessar esta tela.');</script>";
				echo "<script language=Javascript>history.back(1);</script> ";
				exit;
			}
			
			/**
			* Ação do Botão Nova Permissão
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadPermissao.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Permissoes ou Tela Inicial do Projeto
			*/
			if (isset($_POST['BTBack'])) {
				if ($_SESSION['origem'])
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consPermissoes.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../projeto.php\">";
			}
			
			/**
			* Cadastro ou Atualização de Permissões
			*/
			if (isset($_POST['BTNew'])) {
				$permissao = new Permissao;
				if($permissao->get($_GET['id']) == 0){
					unset($_POST['id']);
				}
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				
				if ($_POST['integra'] == 'on')
					$_POST['integra'] = true;
				else
					$_POST['integra'] = false;
					
				$permissao->setFrom($_POST);
				$permissao->save();
				if ($_SESSION['origem'])
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consPermissoes.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../projeto.php\">";
			}
			
			/**
			* Ação do Botão Deletar Permissão
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$permissao = new Permissao;
					$permissao
						->where("id_projeto=".$_SESSION['id_projeto_logado']." and id_usuario=".$_POST['BTDel']."")
						->delete(true);
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Impossível deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}

		}
		
	}
	
	$controle = new PermissaoAction();
	
?>