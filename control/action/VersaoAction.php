<?

	class VersaoAction {
	
		function VersaoAction() {
			
			session_start();
						
			/**
			* A��o do Bot�o Nova Vers�o
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadVersao.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Vers�o
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consVersao.php\">";
			}
	
			/**
			* Cadastro ou Atualiza��o de Vers�es
			*/
			if (isset($_POST['BTNew'])) {
				$controller = new VersaoController();
				if ($controller->validaNovaVersao($_POST['master_version'],$_POST['great_version'],$_POST['average_version'],$_POST['small_version'])){
					$versao = new Versao;
					if($versao->get($_SESSION['upd_versao']) == 0){
						unset($_POST['id']);
					}
					$versao->setFrom($_POST);
					$versao->save();
					if($versao->get($_SESSION['upd_versao']) == 0){
						$controller = new VersaoController();
						$controller->insereTarefasVersao();
					}
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consVersao.php\">";
					exit;
				}
				else {
					echo "<script language=Javascript>alert('N�mero de Vers�o � Invalido');</script>";
					echo "<script language=Javascript>history.back(1);</script> ";
					exit;
				}
			}
			
			/**
			* A��o do Bot�o Atualizar Vers�o
			*/
			if (isset($_POST['BTUpd'])) {
				$_SESSION['upd_versao'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadVersao.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Deletar Vers�o
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$controller = new VersaoController();
					if ($controller->podeExcluir()) {
						$controller->desvinculaTarefasColetas($_POST['BTDel']);
						$versao = new Versao;
						$versao->get($_POST['BTDel']);
						$versao->delete();
					}
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Imposs�vel deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
		}
	}
	
	$controle = new VersaoAction();
	
?>