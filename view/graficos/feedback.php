<?php
	session_start();
	
	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/ConectorBD.php';
	require_once '../../util/Logout.php';
	
	//action
	require_once '../../control/action/FormularioAction.php';
	
	//controllers
	require_once '../../control/ComponenteController.php';
	require_once '../../control/FormularioController.php';
	require_once '../../control/RespostaController.php';
	require_once '../../control/VersaoController.php';
	
	//instanciando controllers
	$componenteController = new ComponenteController();
	$formularioController = new FormularioController();
	$respostaController = new RespostaController();
	$versaoController = new VersaoController();
	
	//acesso a dados a serem listados
	$formularios = $formularioController->listAtivos();
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
		<li><a class="parent"><img style="margin-top:28px;margin-left:-25px;" src="../ci/imagens/logo_snet.png" /></a>
		</li>
		<li><a class="parent"><span>Menu</span></a>
			<div><ul>
				<li><a href="../feedback.php"><span>Início</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="../gerencia.php"><span>Módulo Gerência</span></a></li>
				<? endif; ?>
				<li><a href="?logout"><span>Logout</span></a></li>
			</ul></div>
		</li>
		<li><a href="#" class="parent"><span>Requisição</span></a>
			<div><ul>
				<li><a href="../cadastro/cadRequisicao.php"><span>Enviar</span></a></li>
				<li><a href="../consulta/consRequisicao.php"><span>Consultar</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="../cadastro/cadIntegrado.php"><span>Integrar</span></a></li>
				<li><a href="../auxiliares/monitorFeedback.php"><span>Monitor</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span>Avaliações</span></a>
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
		<li><a class="parent"><span>Gráficos</span></a>
			<div><ul>
				<li><a href="feedback.php"><span>Feedback</span></a></li>
				<li><a class="parent"><span>Avaliações</span></a>
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
	<h3><?=$_SESSION['nome_projeto_logado']?> / Gráficos de Feedback</h3>
    <fieldset style="width:870px;">
    <legend></legend>
	    <form action="<? $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
		
		<p><label for="read_tipo">Tipo:</label>
			<select size="1" name="read_tipo" style="width:180px;"> 
				<option value="1">Erro</option>
				<option value="2">Melhoria</option>
				<option value="3">Nova Funcionalidade</option>
			</select>
		</p>
				
		<p><label for="read_versao">Versão:</label>
            <select size="1" name="read_versao" style="width:180px;"> 
				<option value="0">Todas</option>
				<?=$versaoController->getCombo()?>
			</select>
			<input style="margin-left:30px;" name="BTRelatos" class="formbutton" value="Gerar Gráfico" type="submit" />
		</p>
		<? if (isset($_POST['BTRelatos'])) : ?>
		<p style="margin-left:80px;"><br>
		<iframe src="pizza.php" width="900" height="440" seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true"></iframe></div>
		</p>		
		<? endif; ?>
        </form>
    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>
