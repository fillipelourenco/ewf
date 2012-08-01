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

	//nome do formulário para feature do calendário
	$for = 'formulario';
	
	//action
	require_once '../../control/action/RequisicaoAction.php';
	require_once '../../control/action/IteracaoAction.php';
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/IteracaoController.php';
	require_once '../../control/ComponenteController.php';
	require_once '../../control/PermissaoController.php';
	require_once '../../control/RequisicaoController.php';
	require_once '../../control/FormularioController.php';
	
	//instanciando controllers
	$projetoController = new ProjetoController();
	$iteracaoController = new IteracaoController();
	$componenteController = new ComponenteController();
	$permissaoController = new PermissaoController();
	$requisicaoController = new RequisicaoController();
	$formularioController = new FormularioController();
	
	//acesso a dados a serem listados
	$projetos = $projetoController->listAll();
	$updRequisicao = $requisicaoController->get();
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
				<li><a href="cadRequisicao.php"><span>Enviar</span></a></li>
				<li><a href="../consulta/consRequisicao.php"><span>Consultar</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="cadIntegrado.php"><span>Integrar</span></a></li>
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
				<li><a href="cadFormulario.php"><span>Cadastrar</span></a></li>
				<? endif; ?>
				<li><a href="../consulta/consFormulario.php"><span>Consultar</span></a></li>
			</ul></div>
		</li>
		<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
		<li><a class="parent"><span>Gráficos</span></a>
			<div><ul>
				<li><a href="../graficos/feedback.php"><span>Feedback</span></a></li>
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
	<h3><?=$_SESSION['nome_projeto_logado']?> / Requisição <?=$_SESSION['obj_requisicao']['chave']?></h3>
    <fieldset style="width:870px;">
    <legend></legend>
	    <form name="<? echo $for; ?>" action="<? $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
			
			<div id="coluna-1" style="width:370px;margin-top:4px;">
				<?=$requisicaoController->getChave()?>
				<p><label for="nome">Tipo:</label>
				<select size="1" name="tipo" style="width:180px;"> 
					<option value="0">Selecione...</option>
					<option value="1" <? if ($updRequisicao['tipo'] == '1') echo "selected"; ?>>Erro</option>
					<option value="2" <? if ($updRequisicao['tipo'] == '2') echo "selected"; ?>>Melhoria</option>
					<option value="3" <? if ($updRequisicao['tipo'] == '3') echo "selected"; ?>>Nova Funcionalidade</option>
				</select></p>
			</div>
			
			<div id="coluna-1">
				<? 	if(isset($_SESSION['upd_requisicao'])) 
						echo $requisicaoController->getSitucao();
					else 
						echo '<input type="hidden" id="situacao" name="situacao" value="1">';
				?>
				<p><label for="nome">Módulo:</label>
                <select size="1" cols="60" name="id_componente" style="width:180px;">
					<option value="0">Selecione...</option>
					<?=$componenteController->getComboFeedback()?>
				</select></p>	
			</div>
			
			<div id="coluna-1" style="width:860px;">
				<p></p>
				<p><label for="titulo">Titulo:</label>
                <input name="titulo" id="titulo" size="89" value="<? echo $updRequisicao['titulo']; ?>" type="text"/></p>
				<p><label for="descricao">Descrição:</label>
				<textarea cols="92" rows="18" name="descricao" id="descricao"><? echo $updRequisicao['descricao']; ?></textarea></p>
			</div><p><p>
			
			<div id="coluna-1" style="width:375px;margin-top:15px;">
				<p><label for="id_usuario_solicitante">Solicitante:</label>
                <select size="1" name="id_usuario_solicitante" style="width:180px;" <? if ($_SESSION['tipo_usuario_logado'] == '3') echo "disabled"; ?>>
					<option value="0">Selecione...</option>
					<?=$permissaoController->getComboUsuarios()?>
				</select></p>
				<? if ($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<p><label for="id_usuario_responsavel">Responsavel:</label>
                <select size="1" name="id_usuario_responsavel" style="width:180px;">
					<option value="0">Selecione...</option>
					<?=$permissaoController->getComboResponsaveis()?>
				</select></p>
				<? endif; ?>
			</div>
			
			<? if ($_SESSION['tipo_usuario_logado'] != '3') : ?>
			<div id="coluna-1" style="width:400px;margin-top:13px;">
				<p><label for="prioridade">Prioridade:</label>
                <select size="1" name="prioridade" style="width:80px;">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select></p>
			</div>
			<? endif; ?>
			
			<div id="coluna-1" style="width:860px;margin-top:10px;">				
				<p>
				<?=$requisicaoController->getAnexos()?>
				</p>
				<p><label for="anexos">Anexo:</label>
				<input type="file" name="anexo[]" id="anexo" size="20"/>
				<a href="#" onclick="adicionar(); return false;" style="margin-left: 20px;">Adicionar Anexo</a>
			</div>
			<input type="hidden" id="id" value="1">
			<div id="campos" style="width:880px;float: left;padding: 0px 0px 0px 20px;">
  
			</div>
			<?=$iteracaoController->printIteracoes()?>
			<input type="hidden" name="pasta" value="<? echo $updRequisicao['pasta']; ?>">				
			<div id="coluna-1">
				<p style="margin-top:25px;">
				<input name="BTNew" class="formbutton" value="<? if (!empty($_SESSION['upd_requisicao'])) echo "Atualizar"; else echo "Cadastrar"; ?>" type="submit" />
				<? if (($_SESSION['tipo_usuario_logado'] == '1') and (!empty($_SESSION['upd_requisicao']))) : ?>
				<input name="BTTarefa" class="formbutton" value="Criar Tarefa" type="submit" />
				<? endif; ?>
				<input name="BTBack" class="formbutton" value="Voltar" type="submit" /></p>
			</div>
			<input type="hidden" name="id_usuario_requisitante" value="<? echo $updRequisicao['id_usuario_cadastro']; ?>">
        </form>
    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>
