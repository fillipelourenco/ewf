<?
	if ($_SESSION['tipo_usuario_logado'] != '1') {
		echo "<script language=Javascript>alert('Voc� n�o tem permiss�o para criar Projetos!');</script>";
		echo "<script language=\"javascript\">history.back(1);</script>";
		exit;
	}
?>