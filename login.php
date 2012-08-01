<?
	require_once 'conf/config.php';
	require_once 'control/action/UsuarioAction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Evolution | Login</title>
<link rel="stylesheet" href="view/ci/css/nucleo.css" type="text/css"/>
<link rel="icon" href="view/ci/imagens/favicon.png" type="image/png"/>
</head>
<body>
<div id="login" style="background:#fff;width:430px;">
	<div class="clear" style="margin-top:20px; float:center;">
		<div class="column column-700" style="text-align:center;">
			<h3>Evolution | Login</h3>
                <fieldset style="border-top:1px solid #e0e0e0;">
                <legend></legend>
                <form action="<? $PHP_SELF; ?>" method="post">
					<input type="hidden" name="opcao" value="login">
					<p><label for="login">Login:</label>
                    <input name="login" id="login" value="" type="text" /></p>
					<p><label for="senha">Senha:</label>
                    <input name="senha" id="senha" value="" type="password" /></p>                						
					<input name="BTLogin" class="formbutton" value="Entrar" type="submit" />
                </form>
				<div style="text-align:right;margin-bottom:10px;">
				<p class="filtros">
					[<a href="view/cadastro.php" title="Cadastro no EwF">Cadastre-se</a>]</p>
				</div>
                </fieldset>
		</div>
	</div>
</div>
</body>
</html>
