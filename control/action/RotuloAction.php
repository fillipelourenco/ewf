<?

	class RotuloAction {
	
		function RotuloAction() {
			session_start();
			
			/**
			* A��o do Bot�o Novo Rotulo
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadRotulo.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Rotulo
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consRotulo.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Rotulos
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
			* A��o do Bot�o Atualizar Rotulo
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_rotulo'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadRotulo.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Deletar Rotulo
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$rotulo = new Rotulo;
					$rotulo->get($_POST['BTDel']);
					$rotulo->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Imposs�vel deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
		}
		
	}
	
	$controle = new RotuloAction();
	
?>