<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Evolution | Login</title>
<link rel="stylesheet" href="ci/css/nucleo.css" type="text/css"/>
<link rel="icon" href="ci/imagens/favicon.png" type="image/png"/>
</head>
<body>
<div id="login" style="background:#fff;width:690px;">
	<div class="clear" style="margin-top:20px; float:center;width:690px;">
		<div class="column" style="text-align:left;width:690px;">
			<h3>Cadastro | Evolution With Feedback</h3>
                <fieldset style="border-top:1px solid #e0e0e0;width:650px;">
				<p class="cadastro">Dados da Empresa  </p>
				<fieldset style="border-top:1px solid #000;width:650px;">
                <legend></legend>
                <form action="../control/action/CadastroAction.php" method="post">
					<p><label for="e_nome">Nome:</label>
                    <input name="e_nome" id="e_nome" size="30" value="" type="text" /></p>
					
					<p><label for="e_cidade">Cidade - UF:</label>
					<input name="e_cidade" id="e_cidade" size="45"  type="text" /> - <input name="e_uf" id="e_uf" size="2" type="text" /></p>
					
					<p><label for="e_site">Site:</label>
					<input name="e_site" id="site" size="56"  type="text" /></p>
									
					<p><label for="e_login">Login:</label>
					<input name="e_login" id="e_login" size="30" type="text" /></p>
					
					<p class="cadastro">Dados do Usuário</p>
					<fieldset style="border-top:1px solid #000;width:650px;">
					
					<p style="margin-top:20px;"><label for="u_nome">Nome:</label>
                    <input name="u_nome" id="u_nome" size="45" type="text"/></p>
					
					<p><label for="u_email">E-mail:</label>
                    <input name="u_email" id="u_email" size="56" type="text"/></p>
							
					<p><label for="u_login">Login:</label>
                    <input name="u_login" id="u_login" size="56" type="text"/></p>
							
					<p><label for="u_senha">Senha:</label>
                    <input name="u_senha" id="u_senha" size="56" type="password"/></p>
					<br>	
					<input name="BTCadastro" class="formbutton" value="Cadastrar" type="submit" />
                </form>
                </fieldset>
		</div>
	</div>
</div>
</body>
</html>
