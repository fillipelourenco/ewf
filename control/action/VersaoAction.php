<?

	class VersaoAction {
	
		function VersaoAction() {
			
			session_start();
						
			/**
			* Ação do Botão Nova Versão
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadVersao.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Versão
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consVersao.php\">";
			}
	
			/**
			* Cadastro ou Atualização de Versões
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
					echo "<script language=Javascript>alert('Número de Versão é Invalido');</script>";
					echo "<script language=Javascript>history.back(1);</script> ";
					exit;
				}
			}
			
			/**
			* Ação do Botão Atualizar Versão
			*/
			if (isset($_POST['BTUpd'])) {
				$_SESSION['upd_versao'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadVersao.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Versão
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
					echo "<script language=Javascript>alert('Impossível deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
		}
	}
	
	$controle = new VersaoAction();
	
?>