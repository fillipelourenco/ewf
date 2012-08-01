<?

	class ClienteAction {
	
		function ClienteAction() {
			
			session_start();
			
			/**
			* Ação do Botão Novo Componente
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadCliente.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Tela Consulta de Clientes
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consCliente.php\">";
			}
			
			/**
			* Cadastro ou Atualização de Clientes
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
			* Ação do Botão Atualizar Cliente
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_cliente'] = $_POST['BTUpd'];
				header("Location: ../../view/cadastro/cadCliente.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Cliente
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$cliente = new Cliente;
					$cliente->get($_POST['BTDel']);
					$cliente->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Impossível deletar registro!\\n\\nEste Cliente pode está sendo usado em alguma relação.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consCliente.php\">";
			}
			
		}
		
	}
	
	$controle = new ClienteAction();
	
?>