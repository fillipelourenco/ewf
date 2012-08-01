<?
	session_start();
	
	require_once '../../util/ChecaPermissao.php';
	require_once '../../conf/config.php';
	require_once '../../util/ChecaSessao.php';
	require_once '../../util/CriaPastas.php';
	require_once '../../util/FormataData.php';
	require_once '../../util/FormataString.php';
	require_once '../../util/Logout.php';
	require_once '../../util/MultiUpload.php';
	require_once '../../util/UtilBD.php';

	//nome do formulário para feature do calendário
	$for = 'formulario';
	
	//action
	require_once '../../control/action/TarefaAction.php';
	require_once '../../control/action/ComentarioAction.php';
	
	//controllers
	require_once '../../control/ProjetoController.php';
	require_once '../../control/TarefaController.php';
	require_once '../../control/RotuloController.php';
	require_once '../../control/ComentarioController.php';
	require_once '../../control/ComponenteController.php';
	require_once '../../control/PermissaoController.php';
	
	//instanciando controllers
	$projetoController = new ProjetoController();
	$tarefaController = new TarefaController();
	$rotuloController = new RotuloController();
	$comentarioController = new ComentarioController();
	$componenteController = new ComponenteController();
	$permissaoController = new PermissaoController();
	
	//acesso a dados a serem listados
	$projetos = $projetoController->listAll();
	$updTarefa = $tarefaController->get();

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
	<h3><?=$_SESSION['nome_projeto_logado']?> / Tarefas</h3>
    <fieldset style="width:870px;">
    <legend></legend>
	    <form name="<? echo $for; ?>" action="<? $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
			
			<div id="coluna-1" style="width:370px;margin-top:4px;">
				<?=$tarefaController->getChave()?>
				<p><label for="nome">Tipo:</label>
                <select size="1" cols="60" name="id_rotulo" style="width:180px;" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
					<option value="0">Selecione...</option>
					<?=$rotuloController->getCombo()?>
				</select></p>				
			</div>
			
			<div id="coluna-1">
				<?=$tarefaController->getSitucao()?>
				<p><label for="nome">Componente:</label>
				<select size="1" name="id_componente" style="width:180px;" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>> 
					<option value="0">Selecione...</option>
					<?=$componenteController->getCombo()?>
				</select></p>
			</div>
			
			<div id="coluna-1" style="width:860px;">
				<p></p>
				<p><label for="titulo">Titulo:</label>
                <input name="titulo" id="titulo" size="89" value="<? echo $updTarefa['titulo']; ?>" type="text" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>/></p>
				<p><label for="descricao">Descrição:</label>
				<textarea cols="92" rows="18" name="descricao" id="descricao" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>><? echo $updTarefa['descricao']; ?></textarea></p>
			</div><p><p>
			
			<div id="coluna-1" style="width:420px;margin-top:15px;">
				<p><label for="prazo">Prazo:</label>
                <input type="text" name="prazo" style="width:80px;" onclick="javascript:AbreCalendario(250,220,'<? echo $for; ?>','prazo','<? if ($prazo=='') echo time(); else FormataDataBD($prazo); ?>');" value="<? echo FormataDataNumeros($updTarefa['prazo']); ?>" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
				&nbsp;<a href="javascript:AbreCalendario(250,220,'<? echo $for; ?>','prazo','<? if ($prazo=='') echo time(); else FormataDataBD($prazo); ?>');"><img src="calendario.gif" width="16" height="16" border="0" alt="Clique aqui para selecionar uma data!"></a></p>
				
				<p><label for="requisitante">Requisitante:</label>
                <select size="1" name="id_usuario_requisitante" style="width:180px;" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
					<option value="0">Selecione...</option>
					<?=$permissaoController->getComboPermissoes()?>
				</select></p>
				<p><label for="responsavel">Responsável:</label>
                <select size="1" name="id_usuario_responsavel" style="width:180px;" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
					<option value="0">Selecione...</option>
					<?=$permissaoController->getComboDesenvolvedores()?>
				</select></p>
			</div>
			
			<div id="coluna-1" style="width:370px;margin-top:15px;">
				<p><label for="estimativa">Estimativa:</label>
                <input type="text" name="estimativa" style="width:80px;" value="<? echo $updTarefa['estimativa']; ?>" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
				&nbsp;<img src="../ci/imagens/ico_ajuda.png" border="0" title="Estimativa em Horas" alt="Estimativa em Horas"></p>
				
				<p><label for="dependencia">Depende de:</label>
                <input type="text" name="dependencia" style="width:80px;" value="<? echo $updTarefa['dependencia']; ?>" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
				&nbsp;<img src="../ci/imagens/ico_ajuda.png" border="0" title="Tarefa que Precisa ser Concluída para Esta Iniciar" alt="Tarefa que Precisa ser Concluída para Esta Iniciar"></p>
				
				<p><label for="pai">Tarefa Pai:</label>
                <input type="text" name="pai" style="width:80px;" value="<? echo $updTarefa['pai']; ?>" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>>
				&nbsp;<img src="../ci/imagens/ico_ajuda.png" border="0" title="Tarefa 'Pai' a Qual está Pertence" alt="Tarefa 'Pai' a Qual está Pertence"></p>
			</div>
			
			<div id="coluna-1" style="width:860px;">				
				<p>
				<?=$tarefaController->getAnexos()?>
				</p>
				<p><label for="anexos">Anexo:</label>
				<input type="file" name="anexo[]" id="anexo" size="20" <? if (!$_SESSION['permissao_var']) echo "disabled"; ?>/>
				<a href="#" onclick="adicionar(); return false;" style="margin-left: 20px;"><? if ($_SESSION['permissao_var']) echo "Adicionar Anexo"; ?></a>
			</div>
			<input type="hidden" id="id" value="1">
			<div id="campos" style="width:880px;float: left;padding: 0px 0px 0px 20px;">
  
			</div>
			<?=$comentarioController->printComentarios()?>
			<input type="hidden" name="pasta" value="<? echo $updTarefa['pasta']; ?>">				
			<div id="coluna-1">
				<p style="margin-top:25px;">
				<? if ($_SESSION['permissao_var']) : ?>
				<input name="BTNew" class="formbutton" value="<? if (!empty($_SESSION['upd_tarefa'])) echo "Atualizar"; else echo "Cadastrar"; ?>" type="submit" />
				<? endif; ?>
				<input name="BTBack" class="formbutton" value="Voltar" type="submit" /></p>
			</div>
        </form>
    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>