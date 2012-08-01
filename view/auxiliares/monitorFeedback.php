<?
	session_start();
	
	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/CriaPastas.php';
	require_once '../../util/ConectorBD.php';
	require_once '../../util/FormataData.php';
	require_once '../../util/FormataString.php';
	require_once '../../util/Logout.php';
	require_once '../../util/MultiUpload.php';
	require_once '../../util/UtilBD.php';
	
	//action
	require_once '../../control/action/RequisicaoAction.php';
	
	//controllers
	require_once '../../control/RequisicaoController.php';
	require_once '../../control/FormularioController.php';
	require_once '../../control/PermissaoController.php';
	require_once '../../control/ClienteController.php';
	
	//instanciando controllers
	$requisicaoController = new RequisicaoController();
	$formularioController = new FormularioController();
	$permissaoController = new PermissaoController();
	$clienteController = new ClienteController();
	
	//acesso a dados a serem listados
	$formularios = $formularioController->listAtivos();
	$ultimasRequisicoes = $requisicaoController->listRecebidas();
	$formulariosEncerrados = $formularioController->listFechados();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="refresh" content="600">
<title><?=$_SESSION['nome_projeto_logado']?> | <?=$_SESSION['nome_empresa_logada']?></title>
<link type="text/css" href="../ci/css/menu.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/nucleo.css" rel="stylesheet" />
<link type="image/png" href="../ci/imagens/icone.png" rel="icon" />
<script type="text/javascript" src="../ci/js/jquery.js"></script>
<script type="text/javascript" src="../ci/js/menu.js"></script>
<script type="text/javascript" src="../ci/js/modalTop.js"></script>
<script type="text/javascript" src="../ci/js/anexos.js"></script>
<script type="text/javascript" src="../ci/js/calendario.js"></script>
<script type="text/javascript" src="../ci/js/acoes.js"></script>
</head>
<body>
<div align="center" id="pageIsLoading">
	<img src="../ci/imagens/ajax-loader.gif" alt="Carregando..." />
</div>
<div id="menu">
	<ul class="menu" style="margin-top: -15px;" >
		<li><a class="parent"><span>Menu</span></a>
			<div><ul>
				<li><a href="../feedback.php"><span>In�cio</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="../gerencia.php"><span>M�dulo Ger�ncia</span></a></li>
				<? endif; ?>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Requisi��o</span></a>
			<div><ul>
				<li><a href="../cadastro/cadRequisicao.php"><span>Enviar</span></a></li>
				<li><a href="../consulta/consRequisicao.php"><span>Consultar</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="../cadastro/cadIntegrado.php"><span>Integrar</span></a></li>
				<li><a href="../auxiliares/monitorFeedback.php"><span>Monitor</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span>Avalia��es</span></a>
			<div><ul>
				<li><a class="parent"><span>Abertas</span></a>
					<div><ul>
						<form action="../../control/action/FormularioAction.php" id="FormFormularios" name="FormFormularios" method="POST" >
						<input type="hidden" name="upd_formulario" value="" />
						<?=$formularioController->printMenu($formularios)?>
						</form>
					</ul></div>
				</li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="../cadastro/cadFormulario.php"><span>Cadastrar</span></a></li>
				<? endif; ?>
				<li><a href="../consulta/consFormulario.php"><span>Consultar</span></a></li>
			</ul></div>
		</li>
		<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
		<li><a class="parent"><span>Gr�ficos</span></a>
			<div><ul>
				<li><a href="../graficos/feedback.php"><span>Feedback</span></a></li>
				<li><a class="parent"><span>Avalia��es</span></a>
					<div><ul>
						<form action="../../control/action/FormularioAction.php" id="FormAvaliacoes" name="FormAvaliacoes" method="POST" >
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
		<form action="../../control/action/UsuarioAction.php" method="post" name="UpdPerfil" id="UpdPerfil">
		<?=$_SESSION['nome_usuario_logado']?>  [<a href="javascript:atualizarPerfil();" title="Atualizar Perfil">Perfil</a>]
		<input type="hidden" name="upd_usuario" value="<?=$_SESSION['id_usuario_logado']?>">
		</form>
	</h4>
</div>
<div id="conteudo" class="column">
	<h3>Monitor de Feedback</h3>
    <fieldset style="width:870px;">
    <legend></legend>
	    <form action="" method="post" enctype="multipart/form-data">
		<p><label for="ordem_log">Ordernar Por:</label>
			<select size="1" name="ordem_log" style="width:150px;">
				<option value="0" <? if ($_SESSION["ordem_log"] == '0') echo "selected"; ?>>Prioridade</option>
				<option value="1" <? if ($_SESSION["ordem_log"] == '1') echo "selected"; ?>>Momento Cadastro</option>
				<option value="2" <? if ($_SESSION["ordem_log"] == '2') echo "selected"; ?>>Momento Altera��o</option>
			</select></p>
					
		<p><label for="cliente_log">Cliente:</label>
			<select size="1" name="cliente_log" style="width:150px;">
				<option value="0">Todos</option>
				<?=$clienteController->getComboTodos()?>
			</select></p>
							
		<p><label for="responsavel_log">Respons�vel:</label>
			<select size="1" name="responsavel_log" style="width:150px;">
				<option value="0">Todos</option>
				<?=$permissaoController->getComboResponsaveis()?>
			</select>
		
		<input name="BTFiltro" class="formbutton" value="Filtrar" type="submit" style="margin-left:20px;"/></p>
		<p>
		<table cellpading="0" cellspacing="5" border="0">
			<tbody>
			<? echo($requisicaoController->printMonitor($ultimasRequisicoes)); ?>
		</table>		
        </form>
    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>
