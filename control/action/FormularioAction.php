<?
	require_once '../../conf/config.php';

	class FormularioAction {
	
		function FormularioAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o Novo Formul�rio
			*/
			if (isset($_POST['BTNovo'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadFormulario.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consFormulario.php\">";
			}
			
			/**
			* A��o do Bot�o Responder Formularios
			*/
			if (isset($_POST['BTResponder'])) {
				session_start();
				$_SESSION['upd_formulario'] = $_POST['BTResponder'];
				header("Location: ../../view/auxiliares/responderAvaliacao.php");
				exit;
			}
			
			/**
			* A��o do Bot�o Editar Formulario
			*/
			if (isset($_POST['BTEditar'])) {
				session_start();
				$_SESSION['upd_formulario'] = $_POST['BTEditar'];
				header("Location: ../../view/cadastro/cadFormulario.php");
				exit;
			}
			
			/**
			* Cadastro ou Atualiza��o de Formularios
			*/
			if (isset($_POST['BTAva'])) {
				$formulario = new Formulario;
				if($formulario->get($_SESSION['upd_formulario']) == 0){
					unset($_POST['id']);
					$_POST['chave'] = geraChaveFormulario($_SESSION['id_empresa_logada']);
				}
				$_POST['id_projeto'] = $_SESSION['id_projeto_logado'];
				$formulario->setFrom($_POST);
				$formulario->save();
				
				$query = pg_query('select id_formulario, chave from formularios order by id_formulario DESC limit 1');
				$formulario = pg_fetch_object($query);
				session_start();
				$_SESSION['upd_formulario'] = $formulario->id_formulario;
				$_SESSION['chave_formulario'] = $formulario->chave;
				$_SESSION['qtd_pergunta'] = 1;		

				incrementaChaveFormulario($_SESSION['id_empresa_logada']);
				
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadPergunta.php\">";
			}
			
			/**
			* Atualiza��o de Formularios
			*/
			if (isset($_POST['BTAtualizar'])) {
				$formulario = new Formulario;
				$formulario->get($_SESSION['upd_formulario']);
				if ($_POST['status'] == 'on') $_POST['status'] = true;
				$formulario->setFrom($_POST);
				$formulario->save();
								
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consFormulario.php\">";
			}
			
			/**
			* Atualiza��o de Perguntas
			*/
			if (isset($_POST['BTPerguntas'])) {
				session_start();
				$_SESSION['upd_formulario'] = $_POST['BTPerguntas'];
				$formulario = new Formulario;
				$formulario
					->where('id_formulario='.$_POST['BTPerguntas'].'')
					->find(true);
				$_SESSION['chave_formulario'] = $formulario->chave;
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consPergunta.php\">";
			}
			
			/**
			* A��o do Bot�o Deletar Formulario
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$formulario = new Formulario;
					$formulario->get($_POST['BTDel']);
					$formulario->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Imposs�vel deletar registro!\\n\\nEste Formul�rio pode est� sendo usado em alguma rela��o.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consFormulario.php\">";
			}
			
			/**
			* A��o do Bot�o Deletar Formulario
			*/
			if (isset($_POST['BTGrafico'])) {
				session_start();
				$_SESSION['perguntas'] = $_POST['perguntas'];
				$_SESSION['id_componente'] = $_POST['id_componente'];
			}
			
			/**
			* A��o do Bot�o Deletar Formulario
			*/
			if (isset($_POST['BTRelatos'])) {
				session_start();
				$_SESSION['read_tipo'] = $_POST['read_tipo'];
				$_SESSION['read_versao'] = $_POST['read_versao'];
			}
			
			/**
			* A��o do Bot�o/JavaScript Abrir Formul�rio
			*/
			if (!empty($_POST['upd_formulario'])) {
				$_SESSION['upd_formulario'] = $_POST['upd_formulario'];
				header("Location: ../../view/auxiliares/responderAvaliacao.php");
				exit;
			}
			
			/**
			* A��o do Bot�o/JavaScript Abrir Avalia��o
			*/
			if (!empty($_POST['read_formulario']) or isset($_POST['BTGraph'])) {
				$formulario = new Formulario;
				if (isset($_POST['BTGraph'])) {
					$_SESSION['read_formulario'] = $_POST['BTGraph'];
					$formulario
						->where('id_formulario='.$_POST['BTGraph'].'')
						->find(true);
				}
				else {
					$_SESSION['read_formulario'] = $_POST['read_formulario'];
					$formulario
						->where('id_formulario='.$_POST['read_formulario'].'')
						->find(true);
				}
				
				$_SESSION['chave_formulario'] = $formulario->chave;
				header("Location: ../../view/graficos/avaliacoes.php");
				exit;
			}
						
		}
		
	}
	
	$controle = new FormularioAction();
	
?>