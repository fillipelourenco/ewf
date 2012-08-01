<?
	session_start();
	
	require_once '../util/ChecaPermissaoHome.php';
	require_once '../conf/config.php';
	require_once '../util/ChecaSessao.php';
	require_once '../util/Logout.php';
	require_once '../util/FormataData.php';
	require_once '../util/Progress.php';
	
	unset($_SESSION['origem']);
	unset($_SESSION['upd_tarefa']);
	unset($_SESSION['upd_projeto']);
	unset($_SESSION['upd_versao']);
	
	//controllers
	require_once '../control/ProjetoController.php';
	require_once '../control/PermissaoController.php';
	require_once '../control/TarefaController.php';
	require_once '../control/LogTarefaController.php';
	
	//action
	require_once '../control/action/ProjetoAction.php';
	require_once '../control/action/TarefaAction.php';
	require_once '../control/action/LogTarefaAction.php';
	
	$projetoController = new ProjetoController();
	$permissaoController = new PermissaoController();
	$tarefaController = new TarefaController();
	$logTarefaController = new LogTarefaController();
	
	$projeto = $projetoController->get($_SESSION['id_projeto_logado']);
	$projetos = $projetoController->listAll();
	$usuariosPermitidos = $permissaoController->loadPermissoes();
	$tarefasPendentes = $tarefaController->getTarefasPendentes();
	$acoesRecentes = $logTarefaController->getAcoesRecentes(); 
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Evolution | <?=$_SESSION['nome_empresa_logada']?></title>
<link type="text/css" href="ci/css/menu.css" rel="stylesheet" />
<link type="text/css" href="ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="ci/css/nucleo.css" rel="stylesheet" />
<link type="text/css" href="ci/css/index.css" rel="stylesheet" />
<link type="image/png" href="ci/imagens/favicon.png" rel="icon" />
<script type="text/javascript" src="ci/js/jquery.js"></script>
<script type="text/javascript" src="ci/js/menu.js"></script>
<script type="text/javascript" src="ci/js/acoes.js"></script>
</head>
<script type="text/javascript" src="ci/js/modalTop.js"></script>
<body>
<div align="center" id="pageIsLoading">
	<img src="ci/imagens/ajax-loader.gif" alt="Carregando..." />
</div>
<div id="menu">
	<ul class="menu" style="margin-top: -15px;" >
		<li><a class="parent"><span>EwF</span></a>
			<div><ul>
				<li><a href="gerencia.php"><span>Início</span></a></li>
				<li><a class="parent"><span>Configuração</span></a>
					<div><ul>
						<li><a href="cadastro/cadEmpresa.php"><span>Empresa</span></a></li>
						<li><a href="consulta/consRotulo.php"><span>Rótulos</span></a></li>
					</ul></div>
				</li>
				<li><a href="feedback.php"><span>Módulo Feedback</span></a></li>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Gerência</span></a>
			<div><ul>
				<li><a class="parent"><span>Projetos</span></a>
					<div><ul>
					<form action="../control/action/ProjetoAction.php" name="FormProjetosMenu" id="FormProjetosMenu" method="POST" >
						<input type="hidden" name="upd_projeto" value="" />
						<? echo($projetoController->listMenu($projetos));	?>
					</form>
					<li><a href="cadastro/cadProjeto.php"><span>Novo Projeto...</span></a></li>
					</ul></div>
				</li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="consulta/consCliente.php"><span>Clientes</span></a></li>
				<li><a href="consulta/consUsuario.php"><span>Usuários</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<? if ($_SESSION['id_projeto_logado']) : ?>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span><?=$_SESSION['nome_projeto_logado']?></span></a>
			<div><ul>
				<li><a href="consulta/consComponente.php"><span>Componentes</span></a></li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="consulta/consPermissoes.php"><span>Permissões</span></a></li>
				<? endif; ?>
				<li><a href="consulta/consRisco.php"><span>Riscos</span></a></li>
				<li><a class="parent"><span>Tarefas</span></a>
					<div><ul>
						<li><a href="cadastro/cadTarefa.php"><span>Nova Tarefa</span></a></li>
						<li><a href="consulta/consTarefa.php"><span>Listar Tarefas</span></a></li>
					</ul></div>
				</li>
				<li><a class="parent"><span>Versões</span></a>
					<div><ul>
						<li><a href="cadastro/cadVersao.php"><span>Nova Versão</span></a></li>
						<li><a href="consulta/consVersao.php"><span>Listar Versões</span></a></li>
					</ul></div>
				</li>
			</ul></div>
		</li>
		<? endif; ?>
		<li><a class="parent"><span>Relatórios</span></a>
			<div><ul>
				<li><a class="parent"><span>Gerência</span></a>
					<div><ul>
						<? if ($_SESSION['id_projeto_logado']) : ?>
						<li><a href="relatorios/gerencia/analiseDeRiscos.php"><span>Análise de Riscos</span></a></li>
						<li><a href="relatorios/gerencia/diagramaDeGantt.php"><span>Diagrama de Gantt</span></a></li>
						<? else : ?>
						<li><a href="#" onClick="javascript:window.alert('Selecione um Projeto!')"><span>Análise de Riscos</span></a></li>
						<li><a href="#" onClick="javascript:window.alert('Selecione um Projeto!')"><span>Diagrama de Gantt</span></a></li>
						<? endif; ?>
					</ul></div>
				</li>
				<li><a class="parent"><span>Feedback</span></a>
					<div><ul>
						<li><a href="#"><span>Notificação de Erros</span></a></li>
						<li><a href="#"><span>Impacto de Erros</span></a></li>
					</ul></div>
				</li>
			</ul></div>
		</li>
	</ul>
</div>
<div style="background:#fff;width:900px;border-top:5px;">
<h4 style="margin-top:-10px;margin-bottom:-20px;text-align:right;"><br>
</h4></div>
<div id="conteudo" class="column">
	<div id="coluna-1">
		<h3><? echo '['.$_SESSION['nome_projeto_logado'].']'; ?></h3>
		<fieldset>
		<legend></legend>
		<? echo($projetoController->loadFiltros()); ?>
		<p style="margin-left:5px;margin-top:35px;"> <?=$projetoController->loadProgresso()?></p><br>
		<p><label style="width:100%;margin-left:5px;"><?=$_SESSION['descricao_projeto_logado']?></label></p>
		</fieldset>
	</div>
	<div id="coluna-1" style="height:300px;">
		<h3>Permissões</h3>
		<fieldset>
		<legend></legend>
		<div id="permissoes-projeto">			
		<?=$permissaoController->printPermissoesProjeto($usuariosPermitidos)?>
		</div>
		</fieldset>
	</div>
	<div id="coluna-1">
		<h3>Ações Recentes</h3>
		<fieldset>
		<legend></legend>
		<? echo($logTarefaController->printMenuAcoesRecentes()); ?>
		<div style="margin-bottom:20px;">
		<? echo($logTarefaController->printAcoesRecentes($acoesRecentes)); ?>
		</div>
		</fieldset>
	</div>
	<div id="coluna-1">
		<h3>Tarefas Pendentes</h3>
		<fieldset>
		<legend></legend>
		<? echo($tarefaController->printMenuTarefasPendentes()); ?>
		<div>					
		<? echo($tarefaController->printTarefasPendentes($tarefasPendentes)); ?> 	
		</div>
		<p>
		</fieldset>
	</div>
</div>
<? include('rodape.php'); ?>
<script type="text/javascript" src="ci/js/modalUnder.js"></script>
</body>
</html>