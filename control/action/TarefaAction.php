<?
	
	class TarefaAction {
	
		function TarefaAction() {
		
			/**
			* Ação do Botão Nova Tarefa
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadTarefa.php\">";
				exit;
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Tarefa ou Tela Inicial do Projeto
			*/
			if (isset($_POST['BTBack'])) {
				if ($_SESSION['origem'])		
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consTarefa.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../projeto.php\">";
			}			
			
			/**
			* Cadastro ou Atualização de Tarefas
			*/
			if (isset($_POST['BTNew'])) {
			
				$tarefa = new Tarefa;
	
				$anx = isset($_FILES['anexo']) ? $_FILES['anexo'] : FALSE;
				
				//se for consulta exclui anexos desmarcados
				if (!empty($_SESSION['upd_tarefa'])) {
					if (count($_POST["anexados"])>0){
						$diretorio = "../anexos/".$_POST['pasta'];
						$handle = opendir($diretorio);
						$i=0;
						while ($file = readdir($handle)) {
							$arquivos[$i] = $file;
							$i++;
						}
						closedir($handle);
						
						$excluir = array_diff($arquivos,$_POST["anexados"]);
						
						foreach($excluir as $ex){
							if(strlen($ex)>2) {
								$caminho = "../anexos/".$_POST['pasta']."/".$ex;
								unlink($caminho);
							}
						}
					}
					else {
						$diretorio = "../anexos/".$_POST['pasta'];
						$handle = opendir($diretorio);
						$i=0;
						while ($file = readdir($handle)) {
							if(strlen($file)>2) {
								$caminho = "../anexos/".$_POST['pasta']."/".$file;
								unlink($caminho);
							}
						}
						closedir($handle);
					}
				}
				
				//verificar se existe anexo e valida o tamanho/existencia dos arquivos
				if (ExisteAnexo($anx)) {
					if (!ValidaAnexos($anx,$_POST['pasta'])) {
						echo "<script language=\"javascript\">history.back(1);</script> ";
						exit;
					}
				}
				
				$_POST['situacao_atual'] = $_POST["situacao"];
				
				//se for nova entidade desregistra o id e cria a pasta para os arquivos
				if($tarefa->get($_SESSION['upd_tarefa']) == 0){
					unset($_POST['id']);
					$_POST['pasta'] = CriaPastaTarefa($_POST['titulo']);
					$_POST['chave'] = geraChave($_SESSION["id_empresa_logada"]);
					$_POST['situacao'] = 1;
					$_POST['situacao_atual'] = 1;
					$_POST['id_empresa'] = $_SESSION['id_empresa_logada'];
				}
				
				//se não atribuir usuario a tarefa
				if ($_POST['id_usuario_responsavel'] == '0') $_POST['id_usuario_responsavel'] = null;
				
				//pega dados da seção
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				$_POST['id_usuario_cadastro'] = $_SESSION["id_usuario_logado"];
				
				//salva as alterações ou insere novo registro
				$tarefa->setFrom($_POST);
				$tarefa->save();
				
				//apos a inserção incrementa a chave
				if (empty($_SESSION['upd_tarefa'])){
					incrementaChave($_SESSION["id_empresa_logada"]);
				}
				
				$_POST['id_usuario'] = $_SESSION["id_usuario_logado"];
				$_POST['id_empresa'] = $_SESSION["id_empresa_logada"];
				$log = new LogTarefa;
				$log->setFrom($_POST);
				$log->save();
				
				//apos atualização, atualiza anexos, se houver
				if (ExisteAnexo($anx)) {
					MultiUpload($anx, $_POST['pasta']);
				}

				if ($_SESSION['origem'])		
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../consulta/consTarefa.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../projeto.php\">";
			}
			
			/**
			* Ação do Botão Atualizar Tarefa
			*/
			if (isset($_POST['BTUpd'])) {
				$_SESSION['upd_tarefa'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadTarefa.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Tarefa
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$tarefa = new Tarefa;
					$tarefa->get($_POST['BTDel']);
					$tarefa->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Impossível deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
			
			/**
			* Ação do Botão Pesquisar Tarefa
			*/
			if (isset($_POST['BTSearch'])) {
				$_SESSION['situacao_log'] = $_POST['situacao'];
				$_SESSION['tipo_log'] = $_POST['tipo'];
				$_SESSION['componente_log'] = $_POST['componente_pesquisa'];
			}
			
			/**
			* Verifica Permissão do Usuário na Tarefa
			*/
			if (
				($_SESSION["tipo_usuario_logado"] == '1') or 
					(
						(!empty($_SESSION['upd_tarefa'])) 
						and (
							($_SESSION["id_usuario_logado"] == $updTarefa['id_usuario_responsavel']) or 
							($_SESSION["id_usuario_logado"] == $updTarefa['id_usuario_requisitante']) or 
							($_SESSION["id_usuario_logado"] == $updTarefa['id_usuario_cadastro'])
						)
					)
				) $_SESSION['permissao_var'] = true; else $$_SESSION['permissao_var'] = false;
			
			/**
			* Carrega Informações Ausentes do Projeto Logado
			*/
			/*
			if (empty($_SESSION['login_projeto_logado']) or empty($_SESSION['nome_projeto_logado'])) {
				$proj = new Projeto;
				$proj->get($_SESSION['id_projeto_logado']);
				$p = $proj->toArray();
				$_SESSION['login_projeto_logado'] = $p['login'];
				$_SESSION['nome_projeto_logado'] = $p['nome'];
				$_SESSION['login_projeto_logado'] = $p['login'];
				$_SESSION['nome_projeto_logado'] = $p['nome'];
			}*/
			
			/**
			* Ação do Botão/JavaScript Visualizar Tarefa
			*/
			if ((!empty($_POST['upd_tarefa'])) and (!empty($_POST['upd_projeto']))){
				session_start();
				$_SESSION['upd_tarefa'] = $_POST['upd_tarefa'];
				$_SESSION['id_projeto_logado'] = $_POST['upd_projeto'];
				header("Location: ../../view/cadastro/cadTarefa.php");
				exit;
			}
			
			/**
			* Filtros das Tarefas Pendentes
			*/
			if ($_GET['tarefas']) {
				if ($_GET['tarefas']=='usuario'){
					$_SESSION['t_usuario'] = true;
				}
				if ($_GET['tarefas']=='todas'){
					$_SESSION['t_usuario'] = false;
				}
				if ($_GET['tarefas']=='mais'){
					session_start();
					$_SESSION["situacao_log"] = '0';
					$_SESSION['tipo_log'] = '0';
					$_SESSION['componente_log'] = '0';
					header("Location: consulta/consTarefa.php");
					exit;
				}
			}
		
		}
		
	}
	
	$controle = new TarefaAction();
	
?>