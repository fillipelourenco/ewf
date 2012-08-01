<?

	class EmpresaAction {
	
		function EmpresaAction() {
		
			session_start();
			
			/**
			* Atualização de Empresas
			*/
			if (isset($_POST['BTNew'])) {
				$empresa = new Empresa;
				if($empresa->get($_SESSION["id_empresa_logada"]) == 0){
					unset($_POST['id']);
				}
				$empresa->setFrom($_POST);
				$empresa->save();
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/gerencia.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Tela Inicial
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/gerencia.php\">";
			}
		}
		
	}
	
	$controle = new EmpresaAction();
	
?>