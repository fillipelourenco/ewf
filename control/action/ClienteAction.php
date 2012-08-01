<?

	class ClienteAction {
	
		function ClienteAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o Novo Componente
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadCliente.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Tela Consulta de Clientes
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consCliente.php\">";
			}
			
			/**
			* Cadastro ou Atualiza��o de Clientes
			*/
			if (isset($_POST['BTNew'])) {
				$cliente = new Cliente;
				if($cliente->get($_SESSION['upd_cliente']) == 0){
					unset($_POST['id']);
				}
				$_POST['id_empresa'] = $_SESSION['id_empresa_logada'];
				$cliente->setFrom($_POST);
				$cliente->save();
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consCliente.php\">";
			}
			
			/**
			* A��o do Bot�o Atualizar Cliente
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_cliente'] = $_POST['BTUpd'];
				header("Location: ../../view/cadastro/cadCliente.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Deletar Cliente
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$cliente = new Cliente;
					$cliente->get($_POST['BTDel']);
					$cliente->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Imposs�vel deletar registro!\\n\\nEste Cliente pode est� sendo usado em alguma rela��o.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consCliente.php\">";
			}
			
		}
		
	}
	
	$controle = new ClienteAction();
	
?>