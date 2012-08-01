<?
	require_once '../../util/ConectorBD.php';
	
	class CadastroAction {
	
		function CadastroAction() {
			
			if (isset($_POST['BTCadastro'])) {

				foreach ($_POST as $campo) {
					if ($campo == '') {
						echo "<script language=Javascript>alert('Há Campos em Branco!');</script>";
						echo "<script language=Javascript>history.back(1);</script> ";
						exit;
					}
				}
				
				//valida espaços do login
				
				//verifica se login da empresa ja existe
				$validaLoginEmpresa = pg_query("select * from empresas where login='".$_POST['e_login']."'");
				$validaLoginEmpresa = pg_num_rows($validaLoginEmpresa);
				if ($validaLoginEmpresa > 0) {
					echo "<script language=Javascript>alert('Login da Empresa já existe, por favor escolha outro!');</script>";
					echo "<script language=Javascript>history.back(1);</script> ";
					exit;
				}
				
				//validar e-mail
				
				//verifica se login do usuario ja existe
				$validaLoginUsuario= pg_query("select * from usuarios where login='".$_POST['u_login']."'");
				$validaLoginUsuario = pg_num_rows($validaLoginUsuario);
				if ($validaLoginUsuario > 0) {
					echo "<script language=Javascript>alert('Login do Usuário já existe, por favor escolha outro!');</script>";
					echo "<script language=Javascript>history.back(1);</script> ";
					exit;
				}
				
				if (strlen($_POST['u_senha']) < 4) {
					echo "<script language=Javascript>alert('Senha muito curta!');</script>";
					echo "<script language=Javascript>history.back(1);</script>";
					exit;
				}
				
				$empresa = pg_query("insert into empresas (nome,cidade,uf,site,login) values ('".$_POST['e_nome']."','".$_POST['e_cidade']."','".$_POST['e_uf']."','".$_POST['e_site']."','".$_POST['e_login']."')");
				$idEmpresa = pg_query("select id_empresa from empresas where login='".$_POST['e_login']."'");
				$idEmpresa = pg_fetch_object($idEmpresa);
				$usuario = pg_query("insert into usuarios (nome,tipo,email,login,senha,id_empresa) values ('".$_POST['u_nome']."',1,'".$_POST['u_email']."','".$_POST['u_login']."','".$_POST['u_senha']."',".$idEmpresa->id_empresa.")");
				
				echo "<script language=Javascript>alert('Cadastro Realizado com Sucesso! \\n \\n Faça o Login para Acessar o Ambiente');</script>";
				
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../login.php\">";	
			}
			
		}
		
	}
	
	$controle = new CadastroAction();
	
?>