<?
	require_once '../../conf/config.php';

	class RequisicaoAction {
	
		function RequisicaoAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o Requisi��o Integrada
			*/
			if (isset($_POST['BTGeraLink'])) {
				if (isset($_POST['id_usuario']) and $_POST['id_usuario'] != '0') {
					$link = "http://".$_SERVER["HTTP_HOST"]."/view/cadastro/cadRequisicaoIntegrado.php";
					$link .= "?eps=".$_SESSION['id_empresa_logada'];
					$link .= "&prj=".$_SESSION['id_projeto_logado'];
					$link .= "&cpn=".$_POST['id_componente'];
					$link .= "&usr=".$_POST['id_usuario'];
					$usuario = new Usuario;
					$usuario->get($_POST['id_usuario']);
					$link .= "&clt=".$usuario->id_cliente;
					session_start();
					$_SESSION['link'] = $link;
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadIntegrado.php?l=1\">";
				}
				else {
					echo "<script language=Javascript>alert('Usu�rio Invalido.');</script>";
					echo "<script language=\"javascript\">history.back(1);</script> ";
				}
				
			}
			
			/**
			* A��o do Bot�o Nova Requisi��o
			*/
			if (isset($_POST['BTFiltro'])) {
				session_start();
				$_SESSION['ordem_log'] = $_POST['ordem_log'];
				$_SESSION['cliente_log'] = $_POST['cliente_log'];
				$_SESSION['responsavel_log'] = $_POST['responsavel_log'];
			}
			
			/**
			* A��o do Bot�o Nova Requisi��o
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadRequisicao.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Requisi��o
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/feedback.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Requisi��o
			*/
			if (isset($_POST['BTNew'])) {
				$requisicao = new Requisicao;
				
				$anx = isset($_FILES['anexo']) ? $_FILES['anexo'] : FALSE;
				
				//se for consulta exclui anexos desmarcados
				if (!empty($_SESSION['upd_requisicao'])) {
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
				
				if($requisicao->get($_SESSION['upd_requisicao']) == 0){
					unset($_POST['id']);
					if ($_SESSION['tipo_usuario_logado'] == '3') 
						$_POST['id_usuario_solicitante'] = $_SESSION['id_usuario_logado'];
					$_POST['id_usuario_cadastro'] = $_SESSION['id_usuario_logado'];
					$_POST['chave'] = geraChaveRequisicao($_SESSION['id_empresa_logada']);
					$_POST['pasta'] = CriaPastaTarefa($_POST['titulo']);
					$_POST['situacao'] = '1';
				}
				else {
					$_POST['id_usuario_alteracao'] = $_SESSION['id_usuario_logado'];
					$_POST['momento_alteracao'] = date(DATE_RFC822);
					//gera log apenas para atualiza��es
					$_POST['id_usuario'] = $_SESSION["id_usuario_logado"];
					$_POST['id_empresa'] = $_SESSION["id_empresa_logada"];
					$_POST['situacao_atual'] = $_POST['situacao'];
					$log = new LogRequisicao;
					$log->setFrom($_POST);
					$log->save();
					if ($_SESSION['id_usuario_logado'] != $requisicao->id_usuario_solicitante) {
						$titulo = 'Seu Feedback '.$requisicao->chave.' foi atualizado';
						$mensagem = 'O Feedback '.$requisicao->chave.' foi atualizado. Para acessar v� at� o menu Feedback > Consultar e clique no Bot�o de Atualizar/Visualizar do Feedback '.$requisicao->chave.'';
					
						pg_query('insert into notificacoes (titulo,mensagem,id_usuario_remetente,id_usuario_destinatario) values (\''.$titulo.'\',\''.$mensagem.'\','.$_SESSION['id_usuario_logado'].','.$requisicao->id_usuario_solicitante.')');
					}
				}
				if ($_POST['id_usuario_responsavel']=='0') $_POST['id_usuario_responsavel'] = null;
				if ($_POST['id_componente']=='0') $_POST['id_componente'] = null;
				
				//verifica se o usuario logado � cliente, se n�o for poe dado do requisitante
				if (isset($_SESSION["id_cliente_logado"]))
					$_POST['id_cliente'] = $_SESSION["id_cliente_logado"];
				else {
					$usuario = new Usuario;
					$usuario->get($_POST["id_usuario_solicitante"]);
					$_POST['id_cliente'] = $usuario->id_cliente;
				}
				
				//pega projeto logado e insere/altera requisicao
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				$requisicao->setFrom($_POST);
				$requisicao->save();
				
				//apos a inser��o incrementa a chave
				if (empty($_SESSION['upd_requisicao'])){
					incrementaChaveRequisicao($_SESSION["id_empresa_logada"]);
				}
				
				//apos atualiza��o, atualiza anexos, se houver
				if (ExisteAnexo($anx)) {
					MultiUpload($anx, $_POST['pasta']);
				}
				
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/feedback.php\">";
			}
			
			/**
			* TODO:
			*/
			if (isset($_POST['BTTarefa'])) {
			
				$pastaRequisicao = $_POST['pasta'];
				
				$tarefa = new Tarefa;
				
				$anx = isset($_FILES['anexo']) ? $_FILES['anexo'] : FALSE;
				
				unset($_POST['id']);
				
				//algoritmo do rotulo
				
				if ($_POST['id_componente']=='0') $_POST['id_componente'] = null;
				
				$_POST['situacao'] = '1';
				
				$_POST['situacao_anterior'] = '0';
				
				$_POST['situacao_atual'] = '1';
				
				$_POST['prazo'] = date(DATE_RFC822);
				
				$_POST['chave'] = geraChave($_SESSION['id_empresa_logada']);
				
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				
				$_POST['id_usuario_cadastro'] = $_SESSION['id_usuario_logado'];
				
				$_POST['pasta'] = CriaPastaTarefa($_POST['titulo']);
				
				$pastaTarefa = $_POST['pasta'];
				
				$handle = opendir($pastaRequisicao);
				while ($file = readdir($handle)) {
					if($file == '.' || $file == '..') 
						continue;
					copy($pastaRequisicao.'/'.$file,$pastaTarefa.'/'.$file);
				}
				
				$tarefa->setFrom($_POST);
				$tarefa->save();
				
				$_POST['id_usuario'] = $_SESSION["id_usuario_logado"];
				$_POST['id_empresa'] = $_SESSION["id_empresa_logada"];
				$log = new LogTarefa;
				$log->setFrom($_POST);
				$log->save();
				
				incrementaChave($_SESSION["id_empresa_logada"]);
				
				$query = pg_query('select id_tarefa from tarefas order by id_tarefa desc limit 1');
				$t = pg_fetch_object($query);
				
				$titulo = 'Uma solu��o est� sendo desenvolvida para o Feedback '.$requisicao->chave.'';
				$mensagem = 'O Feedback '.$requisicao->chave.' est� em produ��o para desenvolvimento da solu��o desejada. Acompanhe o andamento do mesmo para saber quando ser� disponibilizado.';
					
				pg_query('insert into notificacoes (titulo,mensagem,id_usuario_remetente,id_usuario_destinatario) values (\''.$titulo.'\',\''.$mensagem.'\','.$_POST['id_usuario_requisitante'].','.$_POST['id_usuario_requisitante'].')');
				
				session_start();
				$_SESSION['upd_tarefa'] = $t->id_tarefa;
				
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadTarefa.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Requisi��o
			*/
			if (isset($_POST['BTInte'])) {
				$requisicao = new Requisicao;
				
				$anx = isset($_FILES['anexo']) ? $_FILES['anexo'] : FALSE;
				
				//verificar se existe anexo e valida o tamanho/existencia dos arquivos
				if (ExisteAnexo($anx)) {
					if (!ValidaAnexos($anx,$_POST['pasta'])) {
						echo "<script language=\"javascript\">history.back(1);</script> ";
						exit;
					}
				}				
				
				if($requisicao->get($_SESSION['upd_requisicao']) == 0){
					unset($_POST['id']);
					$_POST['chave'] = geraChaveRequisicao($_SESSION['id_empresa_logada']);
					$_POST['pasta'] = CriaPastaTarefa($_POST['titulo']);
					$_POST['situacao'] = '1';
				}
				
				if ($_POST['id_componente']=='0') $_POST['id_componente'] = null;
				$requisicao->setFrom($_POST);
				$requisicao->save();
				
				//apos a inser��o incrementa a chave
				if (empty($_SESSION['upd_requisicao'])){
					incrementaChaveRequisicao($_SESSION["id_empresa_logada"]);
				}
				
				//apos atualiza��o, atualiza anexos, se houver
				if (ExisteAnexo($anx)) {
					MultiUpload($anx, $_POST['pasta']);
				}
				
				//fazer tela Obrigado!
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/obrigado.php\">";
			}
			
			/**
			* A��o do Bot�o Atualizar Requisi��o
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_requisicao'] = $_POST['BTUpd'];
				header("Location: ../../view/cadastro/cadRequisicao.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Deletar Requisi��o
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$requisicao = new Requisicao;
					$requisicao->get($_POST['BTDel']);
					$requisicao->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Imposs�vel deletar registro!\\n\\nEsta Requisi��o pode est� sendo usado em alguma rela��o.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consRequisicao.php\">";
			}
			
			/**
			* A��o do Bot�o/JavaScript Visualizar Feed (Requisi��o)
			*/
			if ((!empty($_POST['upd_requisicao'])) and (!empty($_POST['upd_projeto']))){
				session_start();
				$_SESSION['upd_requisicao'] = $_POST['upd_requisicao'];
				$_SESSION['id_projeto_logado'] = $_POST['upd_projeto'];
				header("Location: ../../view/cadastro/cadRequisicao.php");
				exit;
			}
			
		}
		
	}
	
	$controle = new RequisicaoAction();
	
?>
