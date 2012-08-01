<?

	class RotuloAction {
	
		function RotuloAction() {
			session_start();
			
			/**
			* Ação do Botão Novo Rotulo
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadRotulo.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Rotulo
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consRotulo.php\">";
			}
			
			/**
			* Cadastro ou Atualização de Rotulos
			*/
			if (isset($_POST['BTNew'])) {
				$rotulo = new Rotulo;
				if($rotulo->get($_SESSION["upd_rotulo"]) == 0){
					unset($_POST['id']);
				}
				$_POST['id_empresa'] = $_SESSION["id_empresa_logada"];
				$rotulo->setFrom($_POST);
				$rotulo->save();
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consRotulo.php\">";
			}
			
			/**
			* Ação do Botão Atualizar Rotulo
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_rotulo'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadRotulo.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Rotulo
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$rotulo = new Rotulo;
					$rotulo->get($_POST['BTDel']);
					$rotulo->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Impossível deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
		}
		
	}
	
	$controle = new RotuloAction();
	
?>