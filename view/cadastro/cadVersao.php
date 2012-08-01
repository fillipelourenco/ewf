<?
	session_start();
	
	require_once '../../util/ChecaPermissao.php';
	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/Logout.php';
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/VersaoController.php';
	
	//action
	require_once '../../control/action/VersaoAction.php';
	
	$projetoController = new ProjetoController();
	$versaoController = new VersaoController();
	
	$projetos = $projetoController->listAll();
	$updVersao = $versaoController->get();
	$lastVersao = $versaoController->versaoAtual();
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
						<li><a href="cadEmpresa.php"><span>Empresa</span></a></li>
						<li><a href="../consulta/consRotulo.php"><span>Rótulos</span></a></li>
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
					<form action="../../control/action/ProjetoAction.php" name="FormProjetosMenu" id="FormProjetosMenu" method="POST" >
						<input type="hidden" name="opcao" value="selecionaProjeto" />
						<input type="hidden" name="upd_projeto" value="" />
						<? echo($projetoController->listMenu($projetos));	?>
					</form>
					<li><a href="cadastro/cadProjeto.php"><span>Novo Projeto...</span></a></li>
					</ul></div>
				</li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="../consulta/consCliente.php"><span>Clientes</span></a></li>
				<li><a href="../consulta/consUsuario.php"><span>Usuários</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<? if ($_SESSION['id_projeto_logado']) : ?>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span><?=$_SESSION['nome_projeto_logado']?></span></a>
			<div><ul>
				<li><a href="../consulta/consComponente.php"><span>Componentes</span></a></li>
				<? if ($_SESSION['tipo_usuario_logado'] == '1') : ?>
				<li><a href="../consulta/consPermissoes.php"><span>Permissões</span></a></li>
				<? endif; ?>
				<li><a href="../consulta/consRisco.php"><span>Riscos</span></a></li>
				<li><a class="parent"><span>Tarefas</span></a>
					<div><ul>
						<li><a href="cadTarefa.php"><span>Nova Tarefa</span></a></li>
						<li><a href="../consulta/consTarefa.php"><span>Listar Tarefas</span></a></li>
					</ul></div>
				</li>
				<li><a class="parent"><span>Versões</span></a>
					<div><ul>
						<li><a href="cadVersao.php"><span>Nova Versão</span></a></li>
						<li><a href="../consulta/consVersao.php"><span>Listar Versões</span></a></li>
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
	<h3><?=$_SESSION['nome_projeto_logado']?> / Versões</h3>
		<fieldset style="width:870px;">
			<legend></legend>
			<form action="<? $PHP_SELF; ?>" method="post">
				<? if (empty($_SESSION['upd_versao'])) : ?>
				<p><label for="versao_atual">Versão Atual:</label>
				<input size="1" value="<? echo $lastVersao->master_version;?>" type="text" disabled/>
				<input size="1" value="<? echo $lastVersao->great_version;?>" type="text" disabled/>
				<input size="1" value="<? echo $lastVersao->average_version;?>" type="text" disabled/>
				<input size="1" value="<? echo $lastVersao->small_version;?>" type="text" disabled/>
				</p>
				<? endif; ?>
				<input type="hidden" name="id_projeto" value="<?=$_SESSION['id_projeto_logado']?>">
				
				<p><label for="nova_versao">Nova Versão:</label>
				<input name="master_version" id="master_version" size="1" value="<? echo $updVersao['master_version']; ?>" type="text" <? if (!(($_SESSION['tipo_usuario_logado'] == '1') and ((date('Y-m-d')==$updVersao['data_cadastro']) or (empty($_SESSION['upd_versao']))))) echo "disabled"; ?>/>
				<input name="great_version" id="great_version" size="1" value="<? echo $updVersao['great_version']; ?>" type="text" <? if (!(($_SESSION['tipo_usuario_logado'] == '1') and ((date('Y-m-d')==$updVersao['data_cadastro']) or (empty($_SESSION['upd_versao']))))) echo "disabled"; ?>/>
				<input name="average_version" id="average_version" size="1" value="<? echo $updVersao['average_version']; ?>" type="text" <? if (!(($_SESSION['tipo_usuario_logado'] == '1') and ((date('Y-m-d')==$updVersao['data_cadastro']) or (empty($_SESSION['upd_versao']))))) echo "disabled"; ?>/>
				<input name="small_version" id="small_version" size="1" value="<? echo $updVersao['small_version']; ?>" type="text" <? if (!(($_SESSION['tipo_usuario_logado'] == '1') and ((date('Y-m-d')==$updVersao['data_cadastro']) or (empty($_SESSION['upd_versao']))))) echo "disabled"; ?>/></p>
							
				<p><label for="descricao">Descrição:</label>
				<textarea cols="60" rows="10" name="descricao" id="descricao" <? if (!(($_SESSION['tipo_usuario_logado'] == '1') and ((date('Y-m-d')==$updVersao['data_cadastro']) or (empty($_SESSION['upd_versao']))))) echo "disabled"; ?>><? echo $updVersao['descricao']; ?></textarea></p>
				
				<p><label for="nova_versao">Tarefas Incluidas:</label>
				<? 	if (empty($_SESSION['upd_versao'])) 
						echo $versaoController->tarefasIncluidas();
					else
						echo $versaoController->tarefasVersao($_SESSION['upd_versao']);
				?></p>
							
				<p style="margin-top:25px;">
				<? if (($_SESSION['tipo_usuario_logado'] == '1') and ((date('Y-m-d')==$updVersao['data_cadastro']) or (empty($_SESSION['upd_versao'])))) :?>
					<input name="BTNew" class="formbutton" value="<? if (!empty($_SESSION['upd_versao'])) echo "Atualizar"; else echo "Cadastrar"; ?>" type="submit" />
				<? endif; ?>
				<input name="BTBack" class="formbutton" value="Voltar" type="submit" /></p>
				
            </form>
        </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>