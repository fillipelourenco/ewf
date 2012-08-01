<?
	session_start();
	
	require_once '../../conf/config.php';
	require_once '../../util/Integra.php';
	require_once '../../util/CriaPastas.php';
	require_once '../../util/ConectorBD.php';
	require_once '../../util/FormataData.php';
	require_once '../../util/FormataString.php';
	require_once '../../util/Logout.php';
	require_once '../../util/MultiUpload.php';
	require_once '../../util/UtilBD.php';
	
	//action
	require_once '../../control/action/RequisicaoAction.php';
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<title><?=$_SESSION['nome_empresa'].' | '.$_SESSION['nome_projeto']?></title>
<link type="text/css" href="../ci/css/preload.css" rel="stylesheet" />
<link type="text/css" href="../ci/css/nucleo.css" rel="stylesheet" />
<link type="image/png" href="../ci/imagens/icone.png" rel="icon" />
<script type="text/javascript" src="../ci/js/jquery.js"></script>
<script type="text/javascript" src="../ci/js/modalTop.js"></script>
<script type="text/javascript" src="../ci/js/anexos.js"></script>
</head>
<body>
<div align="center" id="pageIsLoading">
	<img src="../ci/imagens/ajax-loader.gif" alt="Carregando..." />
</div>
<div id="conteudo" class="column" style="margin-top:10px;">
	<h3><?=$_SESSION['nome_empresa'].' / '.$_SESSION['nome_projeto']?> / Novo Feedback (Requisição)</h3>
    <fieldset style="width:870px;">
    <legend></legend>
	    <form name="<? echo $for; ?>" action="<? $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id_empresa" value="<?=$_GET['eps']?>">
			<input type="hidden" name="id_projeto" value="<?=$_GET['prj']?>">
			<input type="hidden" name="id_cliente" value="<?=$_GET['clt']?>">
			<input type="hidden" name="id_componente" value="<?=$_GET['cpn']?>">
			<input type="hidden" name="id_usuario_cadastro" value="<?=$_GET['usr']?>">
			
			<div id="coluna-1" style="width:570px;margin-top:4px;">
				<p><label for="nome" style="width:200px;">O que você deseja notificar?</label>
				<select size="1" name="tipo" style="width:180px;"> 
					<option value="0">Selecione...</option>
					<option value="1">Erro</option>
					<option value="2">Melhoria</option>
					<option value="3">Nova Funcionalidade</option>
				</select></p>
			</div>
			<br>
			
			<div id="coluna-1" style="width:860px;">
				<p></p>
				<p><label for="titulo">Titulo:</label>
                <input name="titulo" id="titulo" size="89" value="" type="text"/></p>
				<p><label for="descricao">Descrição:</label>
				<textarea cols="92" rows="18" name="descricao" id="descricao"></textarea></p>
			</div><p><p>
			
			<div id="coluna-1" style="width:860px;">				
				<p>
				</p>
				<p><label for="anexos">Anexo:</label>
				<input type="file" name="anexo[]" id="anexo" size="20"/>
				<a href="#" onclick="adicionar(); return false;" style="margin-left: 20px;">Adicionar Anexo</a>
			</div>
			<input type="hidden" id="id" value="1">
			<div id="campos" style="width:880px;float: left;padding: 0px 0px 0px 20px;">
  
			</div>
			<div id="coluna-1">
				<p style="margin-top:25px;">
				<input name="BTInte" class="formbutton" value="Cadastrar" type="submit" /></p>
			</div>
        </form>
    </fieldset>
</div>
<? include('../rodape.php'); ?>
<script type="text/javascript" src="../ci/js/modalUnder.js"></script>
</body>
</html>