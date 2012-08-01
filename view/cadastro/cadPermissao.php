<?
	session_start();
	
	require_once '../../util/ChecaPermissao.php';
	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/Logout.php';
	
	//action
	require_once '../../control/action/PermissaoAction.php';
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/UsuarioController.php';
	
	$projetoController = new ProjetoController();
	$usuarioController = new UsuarioController();
	
	$projetos = $projetoController->listAll();
	$usuarios = $usuarioController->listAll();
	

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
				<li><a href="../index.php"><span>Início</span></a></li>
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

				<h3><? echo $nome_projeto_logado; ?> / Permissões</h3>
                    <fieldset style="width:870px;">
                        <legend></legend>
						
                        <form action="<? $PHP_SELF; ?>" method="post">
							
							<p><label for="usuario">Usuário:</label>
							
							<select size="1" name="id_usuario">
							<option value="0">Selecione...</option>
							<? 
								while($usuarios->fetch()) {
									if ($usuarios->tipo == 1) $usuarios->tipo = 'Gerente';
									if ($usuarios->tipo == 2) $usuarios->tipo = 'Desenvolvedor';
									if ($usuarios->tipo == 3) $usuarios->tipo = 'Cliente';
									if ($usuarios->tipo == 4) $usuarios->tipo = 'Usuário Final';
									echo '<option value="'.$usuarios->id_usuario.'">'.$usuarios->tipo.' - '.$usuarios->nome.'</option>';
								}
							?>
							</select></p>
							
							<p><label for="usuario">Usuário Integrado:</label>
							<input type="checkbox" name="integra">
							</p>
							
							<p style="margin-top:25px;">
							<input name="BTNew" class="formbutton" value="Adicionar" type="submit" />
							<input name="BTBack" class="formbutton" value="Voltar" type="submit" /></p>
                        </form>
                    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>