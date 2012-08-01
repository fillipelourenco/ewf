<?
	session_start();
	
	require_once '../../util/ChecaPermissao.php';
	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/FormataData.php';
	require_once '../../util/Logout.php';
	
	//action
	require_once '../../control/action/TarefaAction.php';
	
	//altera dados da sessao
	unset($_SESSION["upd_tarefa"]);
	$_SESSION['origem'] = true;
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/ComponenteController.php';
	require_once '../../control/RotuloController.php';
	require_once '../../control/TarefaController.php';
	
	//instanciando controllers
	$projetoController = new ProjetoController();
	$componenteController = new ComponenteController();
	$rotuloController = new RotuloController();
	$tarefaController = new TarefaController();
	
	//acesso a dados a serem listados
	$projetos = $projetoController->listAll();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<title>Evolution | <?=$_SESSION['nome_empresa_logada']?></title>
<link type="text/css" href="../ci/css/menu.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/nucleo.css" rel="stylesheet" />
<link type="image/png" href="../ci/imagens/favicon.png" rel="icon" />
<script type="text/javascript" src="../ci/js/jquery.js"></script>
<script type="text/javascript" src="../ci/js/menu.js"></script>
<script type="text/javascript" src="../ci/js/acoes.js"></script>
</head>
<script type="text/javascript" src="../ci/js/modalTop.js"></script>
<body>
<div align="center" id="pageIsLoading">
	<img src="../ci/imagens/ajax-loader.gif" alt="Carregando..." />
</div>
<div id="menu">
	<ul class="menu" style="margin-top: -15px;" >
		<li><a class="parent"><span>EwF</span></a>
			<div><ul>
				<li><a href="../gerencia.php"><span>Início</span></a></li>
				<li><a class="parent"><span>Configuração</span></a>
					<div><ul>
						<li><a href="../cadastro/cadEmpresa.php"><span>Empresa</span></a></li>
						<li><a href="consRotulo.php"><span>Rótulos</span></a></li>
					</ul></div>
				</li>
				<li><a href="../feedback.php"><span>Módulo Feedback</span></a></li>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Gerência</span></a>
			<div><ul>
				<li><a class="parent"><span>Projetos</span></a>
					<div><ul>
					<form action="../../control/action/ProjetoAction.php" name="FormProjetosMenu" method="POST" id="FormProjetosMenu">
						<input type="hidden" name="upd_projeto" value="" />
						<? echo($projetoController->listMenu($projetos));	?>
					</form>
					<li><a href="../cadastro/cadProjeto.php"><span>Novo Projeto...</span></a></li>
					</ul></div>
				</li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="consCliente.php"><span>Clientes</span></a></li>
				<li><a href="consUsuario.php"><span>Usuários</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<? if ($_SESSION['id_projeto_logado']) : ?>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span><?=$_SESSION['nome_projeto_logado']?></span></a>
			<div><ul>
				<li><a href="consComponente.php"><span>Componentes</span></a></li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="consPermissoes.php"><span>Permissões</span></a></li>
				<? endif; ?>
				<li><a href="consRisco.php"><span>Riscos</span></a></li>
				<li><a class="parent"><span>Tarefas</span></a>
					<div><ul>
						<li><a href="../cadastro/cadTarefa.php"><span>Nova Tarefa</span></a></li>
						<li><a href="consTarefa.php"><span>Listar Tarefas</span></a></li>
					</ul></div>
				</li>
				<li><a class="parent"><span>Versões</span></a>
					<div><ul>
						<li><a href="../cadastro/cadVersao.php"><span>Nova Versão</span></a></li>
						<li><a href="consVersao.php"><span>Listar Versões</span></a></li>
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
						<li><a href="../relatorios/gerencia/analiseDeRiscos.php"><span>Análise de Riscos</span></a></li>
						<li><a href="../relatorios/gerencia/diagramaDeGantt.php"><span>Diagrama de Gantt</span></a></li>
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
<div class="cabecalho">
	<h4 class="cabecalho"><br>
		<form action="../../control/action/UsuarioAction.php" method="post" name="UpdPerfil" id="UpdPerfil">
		<?=$_SESSION['nome_usuario_logado']?>  [<a href="javascript:atualizarPerfil();" title="Atualizar Perfil">Perfil</a>]
		<input type="hidden" name="upd_usuario" value="<?=$_SESSION['id_usuario_logado']?>">
		</form>
	</h4>
</div>
<div id="conteudo" class="column">

			<h3><?=$_SESSION['nome_projeto_logado']?> / Tarefas</h3>
				<fieldset style="width:850px;">
					<legend></legend>
						<form action="<? $PHP_SELF; ?>" method="post">
							
							<p><label for="situacao">Situação:</label>
							<select size="1" name="situacao" style="width:150px;">
								<option value="0" <? if ($_SESSION["situacao_log"] == '0') echo "selected"; ?>>Exceto Fechadas</option>
								<option value="5" <? if ($_SESSION["situacao_log"] == '5') echo "selected"; ?>>Todas</option>
								<option value="1" <? if ($_SESSION["situacao_log"] == '1') echo "selected"; ?>>Abertas</option>
								<option value="2" <? if ($_SESSION["situacao_log"] == '2') echo "selected"; ?>>Em Andamento</option>
								<option value="3" <? if ($_SESSION["situacao_log"] == '3') echo "selected"; ?>>Pendentes</option>
								<option value="4" <? if ($_SESSION["situacao_log"] == '4') echo "selected"; ?>>Fechadas</option>
							</select></p>
							
							<p><label for="tipo">Tipo:</label>
							<select size="1" name="tipo" style="width:150px;">
								<option value="0">Todos</option>
								<?=$rotuloController->getCombo()?>
							</select></p>
							
							<p><label for="componente">Componente:</label>
							<select size="1" name="componente_pesquisa" style="width:150px;">
								<option value="0">Todos</option>
								<?=$componenteController->getCombo()?>
							</select>
							<input name="BTSearch" class="formbutton" value="Pesquisar" type="submit" style="margin-left:20px;"/></p>
							<p>
							<p>
							<table cellpading="0" cellspacing="5" border="0">
								<tbody>
								<?=$tarefaController->printConsultaPaginada()?>
							<br>
							<div id="coluna-1" style="width:870px;padding: 0px;">
							<input name="BTNovo" class="formbutton" value="Novo" type="submit" /></div>
						</form>
				</fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>