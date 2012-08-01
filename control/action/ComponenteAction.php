<?
	require_once '../../conf/config.php';

	class ComponenteAction {
	
		function ComponenteAction() {
			
			session_start();
			
			/**
			* Ação do Botão Novo Componente
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadComponente.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Componente
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consComponente.php\">";
			}
			
			/**
			* Cadastro ou Atualização de Componentes
			*/
			if (isset($_POST['BTNew'])) {
				$componente = new Componente;
				if($componente->get($_SESSION['upd_componente']) == 0){
					unset($_POST['id']);
				}
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				$componente->setFrom($_POST);
				$componente->save();
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consComponente.php\">";
			}
			
			/**
			* Ação do Botão Atualizar Componente
			*/
			if (isset($_POST['BTUpd'])) {
				session_start();
				$_SESSION['upd_componente'] = $_POST['BTUpd'];
				header("Location: ../../view/cadastro/cadComponente.php");
				exit;
			}
			
			/**
			* Ação do Botão Deletar Componente
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$componente = new Componente;
					$componente->get($_POST['BTDel']);
					$componente->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Impossível deletar registro!\\n\\nEste Componente pode está sendo usado em alguma relação.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consComponente.php\">";
			}
			
		}
		
	}
	
	$controle = new ComponenteAction();
	
?>