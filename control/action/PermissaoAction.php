<?

	class PermissaoAction {
	
		function PermissaoAction() {
			session_start();
			
			/**
			* Restri��o do Acesso apenas a Usu�rios do Tipo Gerente
			*/
			if ($_SESSION["tipo_usuario_logado"] != '1') {
				echo "<script language=Javascript>alert('Voc� n�o tem permiss�o para acessar esta tela.');</script>";
				echo "<script language=Javascript>history.back(1);</script> ";
				exit;
			}
			
			/**
			* A��o do Bot�o Nova Permiss�o
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadPermissao.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Permissoes ou Tela Inicial do Projeto
			*/
			if (isset($_POST['BTBack'])) {
				if ($_SESSION['origem'])
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consPermissoes.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../projeto.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Permiss�es
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
			* A��o do Bot�o Deletar Permiss�o
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$permissao = new Permissao;
					$permissao
						->where("id_projeto=".$_SESSION['id_projeto_logado']." and id_usuario=".$_POST['BTDel']."")
						->delete(true);
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Imposs�vel deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}

		}
		
	}
	
	$controle = new PermissaoAction();
	
?>