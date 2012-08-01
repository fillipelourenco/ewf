<?
	session_start();
	unset($_SESSION["upd_requisicao"]);
	unset($_SESSION["upd_notificacao"]);
	
	require_once '../conf/config.php';
	require_once '../util/ChecaSessao.php';
	require_once '../util/FormataData.php';
	require_once '../util/Logout.php';
	require_once '../util/Progress.php';
	
	//action controllers
	require_once '../control/action/LogTarefaAction.php';
	
	//controllers
	require_once '../control/ProjetoController.php';
	require_once '../control/LogRequisicaoController.php';
	require_once '../control/RequisicaoController.php';
	require_once '../control/NotificacaoController.php';
	require_once '../control/FormularioController.php';
	
	$projetoController = new ProjetoController();
	$logRequisicaoController = new LogRequisicaoController();
	$requisicaoController = new RequisicaoController();
	$notificacaoController = new NotificacaoController();
	$formularioController = new FormularioController();
	
	$projetoController->get($_SESSION['id_projeto_logado']);
	$atualizacoes = $logRequisicaoController->getAtualizacoes();
	$ultimasRequisicoes = $requisicaoController->getRequisicoes();
	$notificacoes = $notificacaoController->listAllLimite();
	$formularios = $formularioController->listAtivos();
	$formulariosEncerrados = $formularioController->listFechados();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<meta http-equiv="content-language" content="pt-br" />
<title><?=$_SESSION['nome_projeto_logado']?> | <?=$_SESSION['nome_empresa_logada']?></title>
<link type="text/css" href="ci/css/menu.css" rel="stylesheet" />
<link type="text/css" href="ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="ci/css/nucleo.css" rel="stylesheet" />
<link type="text/css" href="ci/css/index.css" rel="stylesheet" />
<link type="image/png" href="ci/imagens/icone.png" rel="icon" />
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
		<li><a class="parent"><img style="margin-top:28px;margin-left:-25px;" src="ci/imagens/logo_snet.png" /></a>
		</li>
		<li><a class="parent"><span>Menu</span></a>
			<div><ul>
				<li><a href="feedback.php"><span>Início</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="gerencia.php"><span>Módulo Gerência</span></a></li>
				<? endif; ?>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Requisição</span></a>
			<div><ul>
				<li><a href="cadastro/cadRequisicao.php"><span>Enviar</span></a></li>
				<li><a href="consulta/consRequisicao.php"><span>Consultar</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="cadastro/cadIntegrado.php"><span>Integrar</span></a></li>
				<li><a href="auxiliares/monitorFeedback.php"><span>Monitor</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span>Avaliações</span></a>
			<div><ul>
				<li><a class="parent"><span>Abertas</span></a>
					<div><ul>
						<form action="../control/action/FormularioAction.php" id="FormFormularios" name="FormFormularios" method="POST" >
						<input type="hidden" name="upd_formulario" value="" />
						<?=$formularioController->printMenu($formularios)?>
						</form>
					</ul></div>
				</li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="cadastro/cadFormulario.php"><span>Cadastrar</span></a></li>
				<? endif; ?>
				<li><a href="consulta/consFormulario.php"><span>Consultar</span></a></li>
			</ul></div>
		</li>
		<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
		<li><a class="parent"><span>Gráficos</span></a>
			<div><ul>
				<li><a href="graficos/feedback.php"><span>Feedback</span></a></li>
				<li><a class="parent"><span>Avaliações</span></a>
					<div><ul>
						<form action="../control/action/FormularioAction.php" id="FormAvaliacoes" name="FormAvaliacoes" method="POST" >
						<input type="hidden" name="read_formulario" value="" />
						<?=$formularioController->printMenuAvaliacoes($formulariosEncerrados)?>
						</form>
					</ul></div>
				</li>
			</ul></div>
		</li>
		<? endif; ?>
	</ul>
</div>
<div class="cabecalho">
	<h4 class="cabecalho"><br>
		<form action="../control/action/UsuarioAction.php" method="post" name="UpdPerfil" id="UpdPerfil">
		<?=$_SESSION['nome_usuario_logado']?>  [<a href="javascript:atualizarPerfil();" title="Atualizar Perfil">Perfil</a>]
		<input type="hidden" name="upd_usuario" value="<?=$_SESSION['id_usuario_logado']?>">
		</form>
	</h4>
</div>
<div id="conteudo" class="column">
	<div id="coluna-1">
		<h3>Área de Feedback</h3>
		<fieldset>
		<legend></legend>
		<div align="left">
		<p>
		<div style="float:left;margin-bottom:10px;width:400px;margin-left:7px;">
			<div style="margin-top:15px;">
				<img title="Enviar Novo Relato de Erro, Melhoria ou Sugestão" src="ci/imagens/ico_novo.png">
			</div>
			<div class="tarefas-titulo" style="text-align:left;margin-left:27px;"><a href="cadastro/cadRequisicao.php">Enviar Nova Requisição</a></div>
		</div>
		<div style="float:left;margin-bottom:10px;width:400px;margin-left:7px;">
			<div style="margin-top:15px;">
				<img title="Consultar os Feedbacks Enviados" src="ci/imagens/ico_pesquisar.png">
			</div>
			<div class="tarefas-titulo" style="text-align:left;margin-left:27px;"><a href="consulta/consRequisicao.php">Consultar Status de Requisições</a></div>
		</div>
<!--
		<div style="float:left;margin-bottom:10px;width:400px;margin-left:7px;">
			<div style="margin-top:15px;">
				<img title="Responder Avaliações do Projeto Logado" src="ci/imagens/ico_sim.png">
			</div>
			<div class="tarefas-titulo" style="text-align:left;margin-left:27px;"><a href="consulta/consFormulario.php">Responder Formularios de Avaliação</a></div>
		</div>
		<? //if($_SESSION['tipo_usuario_logado'] != '3') : ?>
		<div style="float:left;margin-bottom:10px;width:400px;margin-left:7px;">
			<div style="margin-top:15px;">
				<img title="Visualizar Gráficos de Avaliações ou Proporção de Feedback" src="ci/imagens/ico_grafico.png">
			</div>
			<div class="tarefas-titulo" style="text-align:left;margin-left:27px;"><a href="graficos/feedback.php">Visualizar Gráficos de Feedback</a></div>
		</div>
		<div style="float:left;margin-bottom:10px;width:400px;margin-left:7px;">
			<div style="margin-top:15px;">
				<img title="Cadastrar Novo Formulário de Avaliação (Usabilidade ou Aceitação)" src="ci/imagens/ico_share.png">
			</div>
			<div class="tarefas-titulo" style="text-align:left;margin-left:27px;"><a href="cadastro/cadFormulario.php">Cadastrar Novo Formulário de Teste</a></div>
		</div>
		<? //endif; ?>
-->
		<div style="float:left;margin-bottom:10px;width:400px;margin-left:7px;">
			<div style="margin-top:15px;">
				<img title="Monitorar os Feedbacks Recebidos" src="ci/imagens/ico_olho.png">
			</div>
			<div class="tarefas-titulo" style="text-align:left;margin-left:27px;"><a href="auxiliares/monitorFeedback.php">Monitor de Requisição</a></div>
		</div>
		<? //endif; ?>
		</div>
		</fieldset>
	</div>
	<div id="coluna-1" style="height:330px;">
		<h3>Notificações</h3>
		<fieldset>
		<legend></legend>
		<?=$notificacaoController->printFiltros()?>
		<div align="left">
		<p>
		<form action="../control/action/NotificacaoAction.php" method="post" name="FormNotificacoes" id="FormNotificacoes">
		<?=$notificacaoController->printNotificacoes($notificacoes)?>
		<input type="hidden" name="upd_notificacao" value="" />
		</form>
		</div>
		</fieldset>
	</div>
	<div id="coluna-1">
		<h3>Requisições Atualizadas</h3>
		<fieldset>
		<legend></legend>
		<div style="margin-bottom:20px;">
			<?=$logRequisicaoController->printFeedbacksAtualizados($atualizacoes)?>
		</div>
		</fieldset>
	</div>
	<div id="coluna-1">
		<h3>Últimas Requisições</h3>
		<fieldset>
		<legend></legend>
		<div>					
		<? echo($requisicaoController->printUltimasRequisicoes($ultimasRequisicoes)); ?>
		</div>
		<p>
		</fieldset>
	</div>
</div>
<? include('rodape.php'); ?>
<script type="text/javascript" src="ci/js/modalUnder.js"></script>
</body>
</html>
