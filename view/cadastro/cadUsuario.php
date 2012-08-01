<?
	session_start();

	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/Logout.php';
	
	//action
	require_once '../../control/action/UsuarioAction.php';
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/UsuarioController.php';
	require_once '../../control/ClienteController.php';
	require_once '../../control/FormularioController.php';
	
	//instanciando controllers
	$projetoController = new ProjetoController();
	$usuarioController = new UsuarioController();
	$clienteController = new ClienteController();
	$formularioController = new FormularioController();
	
	//acesso a dados a serem listados
	$projetos = $projetoController->listAll();
	$updUsuario = $usuarioController->get();
	$formularios = $formularioController->listAtivos();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<title>Evolution | <?=$_SESSION["nome_empresa_logada"]?></title>
<link type="text/css" href="../ci/css/menu.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/nucleo.css" rel="stylesheet" />
<link type="image/png" href="../ci/imagens/icone.png" rel="icon" />
<script type="text/javascript" src="../ci/js/jquery.js"></script>
<script type="text/javascript" src="../ci/js/menu.js"></script>
<script type="text/javascript" src="../ci/js/acoes.js"></script>
</head>
<script type="text/javascript" src="../ci/js/modalTop.js"></script>
<body>
<div align="center" id="pageIsLoading">
	<img src="../ci/imagens/ajax-loader.gif" alt="Carregando..." />
</div>
<? if ($_SESSION['tipo_usuario_logado'] == '3') : ?>
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
		<li><a href="#" class="parent"><span>Feedback</span></a>
			<div><ul>
				<li><a href="cadRequisicao.php"><span>Enviar</span></a></li>
				<li><a href="../consulta/consRequisicao.php"><span>Consultar</span></a></li>
				<? if($_SESSION['tipo_usuario_logado'] != '3') : ?>
				<li><a href="#"><span>Integrar</span></a></li>
				<li><a href="#"><span>Monitor</span></a></li>
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
				<li><a class="parent"><span>Feedback</span></a>
					<div><ul>
						<li><a href="#"><span>Erros Relatados</span></a></li>
						<li><a href="#"><span>Melhoria Relatadas</span></a></li>
						<li><a href="#"><span>Sugestão Relatadas</span></a></li>
					</ul></div>
				</li>
				<li><a class="parent"><span>Avaliações</span></a>
					<div><ul>
						<li><a href="#"><span>F#6 Versão 5.0.0.0</span></a></li>
						<li><a href="#"><span>F#4 Versão 5.0.0.0</span></a></li>
						<li><a href="#"><span>Mais...</span></a></li>
					</ul></div>
				</li>
			</ul></div>
		</li>
		<? endif; ?>
	</ul>
</div>
<? else : ?>
<div id="menu">
	<ul class="menu" style="margin-top: -15px;" >
		<li><a class="parent"><img style="margin-top:28px;margin-left:-25px;" src="../ci/imagens/logo_snet.png" /></a>
		</li>
		<li><a class="parent"><span>Menu</span></a>
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
					<form action="../../control/ProjetoActionController.php" name="FormProjetosMenu" id="FormProjetosMenu" method="POST" >
						<input type="hidden" name="upd_projeto" value="" />
						<? echo($projetoController->listMenu($projetos));	?>
					</form>
					<li><a href="cadProjeto.php"><span>Novo Projeto...</span></a></li>
					</ul></div>
				</li>
				<? if ($_SESSION["tipo_usuario_logado"] == '1') : ?>
				<li><a href="../consulta/consCliente.php"><span>Clientes</span></a></li>
				<li><a href="../consulta/consUsuario.php"><span>Usuários</span></a></li>
				<? endif; ?>
			</ul></div>
		</li>
		<? if ($id_projeto_logado) : ?>
		<li style="margin-left:-20px;"><a href="#" class="parent"><span><?=$_SESSION['nome_projeto_logado']?></span></a>
			<div><ul>
				<li><a href="../consulta/consComponente.php"><span>Componentes</span></a></li>
				<? if ($_SESSION["tipo_usuario_logado"] == '1') : ?>
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
						<? if ($id_projeto_logado) : ?>
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
<? endif; ?>
<div class="cabecalho">
	<h4 class="cabecalho"><br>
		<form action="../../control/action/UsuarioAction.php" method="post" name="UpdPerfil" id="UpdPerfil">
		<?=$_SESSION['nome_usuario_logado']?>  [<a href="javascript:atualizarPerfil();" title="Atualizar Perfil">Perfil</a>]
		<input type="hidden" name="upd_usuario" value="<?=$_SESSION['id_usuario_logado']?>">
		</form>
	</h4>
</div>
<div id="conteudo" class="column">
	<h3>Usuários</h3>
   <fieldset style="width:870px;">
        <legend></legend>
						
        <form id="cadUsuario" name="cadUsuario" action="<? $PHP_SELF; ?>" method="post">
							
		<p><label for="nome">Nome:</label>
        <input name="nome" id="nome" size="45" value="<? echo $updUsuario['nome']; ?>" type="text" <? if (!$_SESSION["editar"]) echo "disabled"; ?>/></p>
							
		<p><label for="tipo">Tipo:</label>
							
		<select onChange="renderCliente()" id="userTipo" size="1" name="tipo" <? if ((!$_SESSION["editar"]) or ($_SESSION["tipo_usuario_logado"] != '1')) echo "disabled"; ?>>
			<option value="0">Selecione...</option>
			<option value="1" <? if ($updUsuario['tipo'] == '1') echo "selected"; ?>>Gerente</option>
			<option value="2" <? if ($updUsuario['tipo'] == '2') echo "selected"; ?>>Desenvolvedor</option>
			<option value="3" <? if ($updUsuario['tipo'] == '3') echo "selected"; ?>>Usuário Final</option>
		</select></p>
		
		<p><label for="cliente">Cliente:</label>
							
		<select size="1" id="field_id_cliente" name="field_id_cliente" <? if ((!$_SESSION["editar"]) or ($_SESSION["tipo_usuario_logado"] != '1')) echo "disabled"; ?>>
			<option value="0">Selecione...</option>
			<?=$clienteController->getCombo($updUsuario['id_cliente'])?>
		</select></p>
							
		<p><label for="email">E-mail:</label>
        <input name="email" id="email" size="56" value="<? echo $updUsuario['email']; ?>" type="text" <? if (!$_SESSION["editar"]) echo "disabled"; ?>/></p>
							
		<p><label for="login">Login:</label>
        <input name="login" id="login" size="56" value="<? echo $updUsuario['login']; ?>" type="text" <? if (!$_SESSION["editar"]) echo "disabled"; ?>/></p>
							
		<p><label for="senha">Senha:</label>
        <input name="senha" id="senha" size="56" value="<? echo $updUsuario['senha']; ?>" type="password" <? if (!$_SESSION["editar"]) echo "disabled"; ?>/></p>
							
		<p style="margin-top:25px;">
		<? if ($_SESSION["editar"]) : ?>
			<input name="BTNew" class="formbutton" value="<? if (!empty($_SESSION["upd_usuario"])) echo "Atualizar"; else echo "Cadastrar"; ?>" type="submit" />
		<? endif; ?>
		<input name="BTBack" class="formbutton" value="Voltar" type="submit" /></p>
        </form>
    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>
