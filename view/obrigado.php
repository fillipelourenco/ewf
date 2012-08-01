<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$_SESSION['nome_empresa'].' | '.$_SESSION['nome_projeto']?></title>
<link rel="stylesheet" href="ci/css/nucleo.css" type="text/css"/>
<link rel="icon" href="ci/imagens/favicon.png" type="image/png"/>
</head>
<body>
<div id="login" style="background:#fff;width:430px;">
	<div class="clear" style="margin-top:20px; float:center;">
		<div class="column column-700" style="text-align:center;">
			<h3>Obrigado</h3>
                <fieldset style="border-top:1px solid #e0e0e0;">
                <legend></legend>
					<br><p><label for="login" style="width:400px;">Seu Feedback será analisado e você pode acompanhar o andamento dele através do EwF.</label></p><br><br>
                   
				<div style="text-align:right;margin-bottom:10px;">
				<p class="filtros">
					[<a href="../login.php" title="Cadastro no EwF">Fazer Login no EwF</a>]</p>
				</div>
                </fieldset>
		</div>
	</div>
</div>
</body>
</html>
