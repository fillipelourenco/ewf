<?

	class RiscoAction {
	
		function RiscoAction() {
		
			session_start();
			
			/**
			* A��o do Bot�o Novo Risco
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadRisco.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Riscos
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consRisco.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Riscos
			*/
			if (isset($_POST['BTNew'])) {
				$risco = new Risco;
				if($risco->get($_SESSION['upd_risco']) == 0){
					unset($_POST['id']);
				}
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				$risco->setFrom($_POST);
				$risco->save();
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consRisco.php\">";
			}
			
			/**
			* A��o do Bot�o Atualizar Risco
			*/
			if (isset($_POST['BTUpd'])) {
				$_SESSION['upd_risco'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadRisco.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Deletar Risco
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$risco = new Risco;
					$risco->get($_POST['BTDel']);
					$risco->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Imposs�vel deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
			
			/**
			* A��o do Bot�o Ordernar Consulta de Riscos
			*/
			if (isset($_POST['BTSearch'])) {
				session_start();
				$_SESSION['ordem_log'] = $_POST['ordem'];
			}
			
			/**
			* A��o do Bot�o Gerar Relat�rio de Riscos
			*/
			if (isset($_POST['BTRel'])) {
				$_SESSION["probabilidade"] = $_POST['fprobabilidade'];
				$_SESSION["efeito"] = $_POST['fefeito'];
				$_SESSION["tipo"] = $_POST['ftipo'];		
				$controller = new RelatorioController;
				if ($_POST['agrupado'] == 'Tipo') {
					$_SESSION["relatorio"] = "Relatorio de Riscos por Tipo.jrxml";
					$_SESSION["ordem"] = " order by tipo";
					$controller->printRelRiscos();
				} else if ($_POST['agrupado'] == 'Probabilidade') {
					$_SESSION["relatorio"] = "Relatorio de Riscos por Probabilidade.jrxml";
					$_SESSION["ordem"] = " order by probabilidade";
					$controller->printRelRiscos();
				} else if ($_POST['agrupado'] == 'Efeito') {
					$_SESSION["relatorio"] = "Relatorio de Riscos por Efeito.jrxml";
					$_SESSION["ordem"] = " order by efeito";
					$controller->printRelRiscos();
				} else {
					echo "<script language=Javascript>alert('Voc� deve selecionar um agrupamento!');</script>";
					echo "<script language=\"javascript\">history.back(1);</script> ";
				}
			}
			
		}
		
	}
	
	$controle = new RiscoAction();
	
?>
