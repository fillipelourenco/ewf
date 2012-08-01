<?

	class LogTarefaAction {
	
		function LogTarefaAction() {
		
			/**
			* Redireciona para a Consulta da Tarefa de Acordo com os Dados da Sessуo
			*/
			if ((!empty($_POST['upd_tarefa'])) and (!empty($_POST['upd_projeto']))){
				session_start();
				$_SESSION['upd_tarefa'] = $_POST['upd_tarefa'];
				$_SESSION['id_projeto_logado'] = $_POST['upd_projeto'];
				header("Location: ../../view/cadastro/cadTarefa.php");
				exit;
			}
			
			/**
			* Aчуo dos Filtros de Aчѕes
			*/
			if ($_GET['acoes']) {
				if ($_GET['acoes']=='usuario'){
					$_SESSION['a_usuario'] = true;
				}
				if ($_GET['acoes']=='todas'){
					$_SESSION['a_usuario'] = false;
				}
				if ($_GET['acoes']=='mais'){
					header("Location: auxiliares/acoesRecentes.php");
					exit;
				}
			}
		
		}
		
	}
	
	$controle = new LogTarefaAction();
	
?>