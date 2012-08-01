<?

	class ProjetoAction {
	
		function ProjetoAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o Voltar para Tela Inicial do Sistema ou do Projeto
			*/
			if (isset($_POST['BTBack'])) {
				if (empty($_SESSION['upd_projeto']))
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/index.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/projeto.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Projetos
			*/
			if (isset($_POST['BTNew'])) {
				$projeto = new Projeto;
				if($projeto->get($_SESSION['upd_projeto']) == 0){
					unset($_POST['id']);
				}
				$_POST['id_empresa'] = $_SESSION['id_empresa_logada'];
				$projeto->setFrom($_POST);
				$projeto->save();
				if (empty($_SESSION['upd_projeto']))
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/gerencia.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/projeto.php\">";
			}
			
			/**
			* A��o do Bot�o/JavaScript Atualizar Projeto
			*/
			if (!empty($_POST['upd_projeto'])) {
				$_SESSION['id_projeto_logado'] = $_POST['upd_projeto'];
				header("Location: ../../view/projeto.php");
				exit;
			}
						
			/**
			* A��o do Sub-Menu de Projeto
			*/
			if ($_GET['projeto']) {
				if ($_GET['projeto']=='permissao'){
					header("Location: cadastro/cadPermissao.php");
					exit;
				}
				if ($_GET['projeto']=='altera'){
					$_SESSION['upd_projeto'] = $_SESSION['id_projeto_logado'];;
					header("Location: cadastro/cadProjeto.php");
					exit;
				}
				if ($_GET['projeto']=='tarefa'){
					header("Location: cadastro/cadTarefa.php");
					exit;
				}
				if ($_GET['projeto']=='exclui'){
					$controller = new ProjetoController;
					$controller->excluirProjeto($_SESSION['id_projeto_logado']);
					unset($_SESSION['id_projeto_logado']);
					unset($_SESSION['nome_projeto_logado']);
					header("Location: gerencia.php");
					exit;
				}
			}
			
		}
		
	}
	
	$controle = new ProjetoAction();
	
?>