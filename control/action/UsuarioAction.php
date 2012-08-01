<?
	class UsuarioAction {
	
		function UsuarioAction() {
			
			session_start();
			
			/**
			* Ação do Botão Novo Usuário
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../cadastro/cadUsuario.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Usuário ou Tela Inicial do Sistema/Projeto
			*/
			if (isset($_POST['BTBack'])) {
				if ($_SESSION['origem'])
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consUsuario.php\">";
				else
					if ($_SESSION['id_projeto_logado'])
						echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../projeto.php\">";
					else
						echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../gerencia.php\">";
			}
			
			/**
			* Cadastro ou Atualização de Usuários
			*/
			if (isset($_POST['BTNew'])) {
				$usuario = new Usuario;
				if($usuario->get($_SESSION['upd_usuario']) == 0){
					unset($_POST['id']);
				}
				if ($_POST['tipo'] == '3'){
					$_POST['id_cliente'] = $_POST['field_id_cliente'];
				}
				$_POST['id_empresa'] = $_SESSION["id_empresa_logada"];
				$usuario->setFrom($_POST);
				$usuario->save();
				if ($_SESSION['origem'])
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consUsuario.php\">";
				else
					echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/projeto.php\">";
			}
			
			/**
			* Ação do Botão Atualizar Usuário
			*/
			if (isset($_POST['BTUpd'])) {
				$usuario = new Usuario;
				$_SESSION['upd_usuario'] = $_POST['BTUpd'];
				header("Location: ../cadastro/cadUsuario.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Usuário
			*/
			if (isset($_POST['BTDel'])) {
				$usuario = new Usuario;
				try{
					$usuario= new Usuario;
					$usuario->get($_POST['BTDel']);
					$usuario->delete();
				}
				catch(Exception $e){
					echo "<script language=Javascript>alert('Impossível deletar registro!\\n\\n".$e->getMessage()."');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER["PHP_SELF"]."\">";
			}
			
			/**
			* Ação do Botão/JavaScript Editar/Vizualizar Usuário
			*/
			if (!empty($_POST['upd_usuario'])) {
				$_SESSION['upd_usuario'] = $_POST['upd_usuario'];
				header("Location: ../../view/cadastro/cadUsuario.php");
				exit;
			}
				
			/**
			* Ação do Botão Logar no Sistema
			*/
			if (isset($_POST['BTLogin'])) {
			
				$username = $_POST["login"];
				$password = $_POST["senha"];
						
				$usuario = new Usuario;
				$usuario
					->where("login='".$username."' and senha='".$password."'")
					->find();
				$usuarios = $usuario->allToArray();
					
				if(count($usuarios) == 1) {
					session_start();
					$_SESSION["id_usuario_logado"] = $usuarios['0']['id_usuario'];
					$_SESSION["login_usuario_logado"] = $usuarios['0']['login'];
					$_SESSION["senha_usuario_logado"] = $usuarios['0']['senha'];
					$_SESSION["nome_usuario_logado"] = $usuarios['0']['nome'];
					$_SESSION["tipo_usuario_logado"] = $usuarios['0']['tipo'];
					$_SESSION["id_empresa_logada"] = $usuarios['0']['id_empresa'];
					$_SESSION["id_cliente_logado"] = $usuarios['0']['id_cliente'];
					if ($_SESSION['tipo_usuario_logado'] == '3') {
						$_SESSION['permissao'] = 'disabled';
						$_SESSION['permissao_var'] = false;
					}
					$empresa = new Empresa;
					$empresa->get($usuarios['0']['id_empresa']);
					$emp = $empresa->toArray();
					$_SESSION["nome_empresa_logada"] = $emp['nome'];
					$_SESSION["login_empresa_logada"] = $emp['login'];
					
					if (($_SESSION["tipo_usuario_logado"] == '3') or ($usuarios['0']['inicio'] == 't')) {
						$permissao = new Permissao;
						$permissao
							->where("id_usuario=".$usuarios['0']['id_usuario']."")
							->find(true);
						$_SESSION["id_projeto_logado"] = $permissao->id_projeto;
						header("Location: view/feedback.php");
					}
					else {
						header("Location: view/gerencia.php");
					}
					exit;
				}
				echo "<script language=Javascript>alert('Login ou Senha incorreto. Tente Novamente');</script>";
			}
			
			/**
			* Verificar se o Usuário Logado pode Editar Informações do Usuário Selecionado
			*/
			if (($_SESSION["id_usuario_logado"] != $_SESSION["upd_usuario"]) and ($_SESSION["tipo_usuario_logado"] != '1'))
				$_SESSION["editar"] = false;
			else
				$_SESSION["editar"] = true;
			
			
		}
		
	}
	
	$controle = new UsuarioAction();
	
?>
