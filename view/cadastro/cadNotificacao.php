<?
	session_start();
	
	require_once '../../conf/config.php';
	require_once '../../util/FormataData.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/Logout.php';
	
	//action
	require_once '../../control/action/ProjetoAction.php';
	require_once '../../control/action/NotificacaoAction.php';
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/NotificacaoController.php';
	require_once '../../control/FormularioController.php';
	
	//instanciando controllers
	$projetoController = new ProjetoController();
	$notificacaoController = new NotificacaoController();
	$formularioController = new FormularioController();
	
	//acesso a dados
	$projetos = $projetoController->listAll();
	$updNotificacao = $notificacaoController->get();
	$formularios = $formularioController->listAtivos();
	$formulariosEncerrados = $formularioController->listFechados();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<title><?=$_SESSION['nome_projeto_logado']?> | <?=$_SESSION['nome_empresa_logada']?></title>
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
				<li><a href="../feedback.php"><span>In�cio</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="../gerencia.php"><span>M�dulo Ger�ncia</span></a></li>
				<? endif; ?>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Requisi��es</span></a>
			<div><ul>
				<li><a href="cadRequisicao.php"><span>Enviar</span></a></li>
				<li><a href="../consulta/consRequisicao.php"><span>Consultar</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="cadIntegrado.php"><span>Integrar</span></a></li>
				<li><a href="../auxiliares/monitorFeedback.php"><span>Monitorar</span></a></li>
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
				<li><a href="cadFormulario.php"><span>Cadastrar</span></a></li>
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
	<h3>Notifica��o</h3>
		<fieldset style="width:870px;">
			<legend></legend>
			<form action="<? $PHP_SELF; ?>" method="post">
							
				<p><label for="nome">Titulo:</label>
				<input name="nome" id="nome" size="45" value="<? echo $updNotificacao['titulo']; ?>" type="text" disabled /></p>
							
				<p><label for="descricao">Mensagem:</label>
				<textarea cols="60" rows="10" name="descricao" id="descricao" disabled><? echo $updNotificacao['mensagem']; ?></textarea></p>
				
				<p><label for="login">Momento do Envio:</label>
				<input name="login" id="login" size="45" value="<? echo FormataDataHora($updNotificacao['momento_envio']); ?>" type="text" disabled /></p>
							
				<p style="margin-top:25px;">
				<input name="BTBack" class="formbutton" value="Voltar" type="submit" /></p>
				
            </form>
        </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>
<?$notificacaoController->leu($_SESSION['upd_notificacao'])?>