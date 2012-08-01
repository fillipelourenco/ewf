<?
	session_start();
	unset($_SESSION['upd_tarefa']);
	
	require_once '../../../conf/config.php';
	require_once '../../../util/ChecaSessao.php';
	require_once '../../../util/Logout.php';
	
	//bibliotecas pdf
	include_once('../../../lib/phpjasper/fpdf/fpdf.php');
	include_once('../../../lib/phpjasper/PHPJasperXML.inc');
	include_once('../../../util/FormataData.php');
	
	//controllers
	require_once '../../../control/ProjetoController.php';
	require_once '../../../control/RelatorioController.php';
	
	//actions
	require_once '../../../control/action/RiscoAction.php';
	
	//instanciando controllers
	$projetoController = new ProjetoController();
	
	//acesso a dados a serem listados
	$projetos = $projetoController->listAll();

	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<title>Evolution - <?=$_SESSION["nome_empresa_logada"]?></title>
<link type="text/css" href="../../ci/css/menu.css" rel="stylesheet" />
<link type="text/css" href="../../ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="../../ci/css/nucleo.css" rel="stylesheet" />
<link type="image/png" href="../../ci/imagens/icone.png" rel="icon" />
<script type="text/javascript" src="../../ci/js/jquery.js"></script>
<script type="text/javascript" src="../../ci/js/menu.js"></script>
<script type="text/javascript" src="../../ci/js/acoes.js"></script>
<link type="text/css" rel="stylesheet" href="codebase/dhtmlxgantt.css">
    <script type="text/javascript" language="JavaScript" src="codebase/dhtmlxcommon.js"></script>
    <script type="text/javascript" language="JavaScript" src="codebase/dhtmlxgantt.js"></script>
</head>
<script type="text/javascript" src="../../ci/js/modalTop.js"></script>
<body>
<div align="center" id="pageIsLoading">
	<img src="../../ci/imagens/ajax-loader.gif" alt="Carregando..." />
</div>
<div id="menu">
	<ul class="menu" style="margin-top: -15px;" >
		<li><a class="parent"><img style="margin-top:28px;margin-left:-25px;" src="../../ci/imagens/logo_snet.png" /></a>
		</li>
		<li><a class="parent"><span>Menu</span></a>
			<div><ul>
				<li><a href="../../../index.php"><span>Início</span></a></li>
				<li><a class="parent"><span>Configuração</span></a>
					<div><ul>
						<li><a href="../../cadastro/cadEmpresa.php"><span>Empresa</span></a></li>
						<li><a href="../../consulta/consRotulo.php"><span>Rótulos</span></a></li>
					</ul></div>
				</li>
				<li><a href="#"><span>Módulo Feedback</span></a></li>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Gerência</span></a>
			<div><ul>
				<li><a class="parent"><span>Projetos</span></a>
					<div><ul>
					<form action="../../../control/action/ProjetoAction.php" name="FormProjetosMenu" method="POST" id="FormProjetosMenu">
						<input type="hidden" name="opcao" value="selecionaProjeto" />
						<input type="hidden" name="upd_projeto" value="" />
						<? echo($projetoController->listMenu($projetos));	?>
					</form>
					<li><a href="../../cadastro/cadProjeto.php"><span>Novo Projeto...</span></a></li>
					</ul></div>
				</li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="../../consulta/consUsuario.php"><span>Usuários</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<? if ($_SESSION['id_projeto_logado']) : ?>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span><?=$_SESSION['nome_projeto_logado']?></span></a>
			<div><ul>
				<li><a href="../../consulta/consComponente.php"><span>Componentes</span></a></li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="../../consulta/consPermissoes.php"><span>Permissões</span></a></li>
				<? endif; ?>
				<li><a href="../../consulta/consRisco.php"><span>Riscos</span></a></li>
				<li><a class="parent"><span>Tarefas</span></a>
					<div><ul>
						<li><a href="../../cadastro/cadTarefa.php"><span>Nova Tarefa</span></a></li>
						<li><a href="../../consulta/consTarefa.php"><span>Listar Tarefas</span></a></li>
					</ul></div>
				</li>
				<li><a class="parent"><span>Versões</span></a>
					<div><ul>
						<li><a href="../../cadastro/cadVersao.php"><span>Nova Versão</span></a></li>
						<li><a href="../../consulta/consVersao.php"><span>Listar Versões</span></a></li>
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
						<li><a href="analiseDeRiscos.php"><span>Análise de Riscos</span></a></li>
						<li><a href="diagramaDeGantt.php"><span>Diagrama de Gantt</span></a></li>
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
		<form action="../../../control/action/UsuarioAction.php" method="post" name="UpdPerfil" id="UpdPerfil">
		<?=$_SESSION['nome_usuario_logado']?>  [<a href="javascript:atualizarPerfil();" title="Atualizar Perfil">Perfil</a>]
		<input type="hidden" name="upd_usuario" value="<?=$_SESSION['id_usuario_logado']?>">
		</form>
	</h4>
</div>
<div id="conteudo" class="column">

			<h3><?=$nome_projeto_logado?> / Relatório Análise de Riscos</h3>
				<fieldset style="width:850px;">
					<legend></legend>
						<form action="<? $PHP_SELF; ?>" method="post">
							
							<p><label for="agrupado"><b>Agrupado Por:</b></label>
							<select size="1" name="agrupado" style="width:180px;">
								<option value="">Selecione...</option>
								<option value="Tipo">Tipo</option>
								<option value="Probabilidade">Probabilidade</option>
								<option value="Efeito">Efeito</option>
							</select></p><p>
							<p><label for="agrupado"><b>Filtros</b></label></p><br>
							<p style="margin-left:15px;"><label for="tipo">Tipo:</label>
							<select size="1" name="ftipo" style="width:180px;">
								<option value="Todos">Todos</option>
								<option value="Estimativas">Estimativas</option>
								<option value="Ferramentas">Ferramentas</option>
								<option value="Organizacional">Organizacional</option>
								<option value="Pessoal">Pessoal</option>
								<option value="Requisitos">Requisitos</option>
								<option value="Tecnologia">Tecnologia</option>
							</select></p>
							
							<p style="margin-left:15px;"><label for="probabilidade">Probabilidade:</label>
							<select size="1" name="fprobabilidade" style="width:180px;">
								<option value="Todas">Todas</option>
								<option value="Baixa">Baixa</option>
								<option value="Media">Média</option>
								<option value="Alta">Alta</option>
							</select></p>
							
							<p style="margin-left:15px;"><label for="efeito">Efeito:</label>
							<select size="1" name="fefeito" style="width:180px;">
								<option value="Todos">Todos</option>
								<option value="Toleravel">Tolerável</option>
								<option value="Serio">Sério</option>
								<option value="Catastroficos">Catastróficos</option>
							</select></p>
							</br>
							<input name="BTRel" class="formbutton" value="Gerar Relatório" type="submit"/></p>
							<p>
							<p>
							</form>
				</fieldset>
</div>
<? include('../../rodape.php'); ?>
<script type="text/javascript" src="../../ci/js/modalUnder.js"></script>
</body>
</html>
