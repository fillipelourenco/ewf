<?
	//require_once '../conf/config.php';
	
	if (isset($_GET['eps']) and isset($_GET['prj']) and isset($_GET['cpn']) and isset($_GET['usr']) and isset($_GET['clt'])) {
		$usuario = new Usuario;
		$permissao = new Permissao;
		$usuario
			->alias('u')
			->join($permissao,'inner','p')
			->where('p.id_projeto='.$_GET['prj'].' and p.id_usuario='.$_GET['usr'].'')
			->find(true);
		if ($usuario->integra == 't') {
			//session_start();
			$empresa = new Empresa;
			$empresa->get($_GET['eps']);
			$_SESSION['nome_empresa'] =  $empresa->nome;
			$projeto = new Projeto;
			$projeto->get($_GET['prj']);
			$_SESSION['nome_projeto'] =  $projeto->nome;
		}
		else
			echo 'link errado';
	}
	else
		echo 'link errado';

?>
	