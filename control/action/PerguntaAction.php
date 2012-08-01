<?
	require_once '../../conf/config.php';

	class PerguntaAction {
	
		function PerguntaAction() {
			
			session_start();
			
			/**
			* A��o do Bot�o Nova Pergunta
			*/
			if (isset($_POST['BTNova'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadPergunta.php\">";
			}
			
			/**
			* A��o do Bot�o Adicionar Mais Perguntas
			*/
			if (isset($_POST['BTAdd'])) {
				$pergunta = new Pergunta;
				unset($_POST['id']);
				session_start();
				$_POST['id_formulario'] = $_SESSION['upd_formulario'];
				if ($_POST['id_componente'] == '0') $_POST['id_componente'] = null;
				$_SESSION['qtd_pergunta'] += 1;
				
				$pergunta->setFrom($_POST);
				$pergunta->save();
				
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadPergunta.php\">";
			}
			
			/**
			* A��o do Bot�o Finalizar Formul�rio
			*/
			if (isset($_POST['BTEnd'])) {
				$pergunta = new Pergunta;
				unset($_POST['id']);
				session_start();
				$_POST['id_formulario'] = $_SESSION['upd_formulario'];
				if ($_POST['id_componente'] == '0') $_POST['id_componente'] = null;
				unset($_SESSION['qtd_pergunta']);
				
				$pergunta->setFrom($_POST);
				$pergunta->save();
				
				$query = pg_query('select * from permissoes p
									join usuarios u on (p.id_usuario=u.id_usuario) 
									where p.id_projeto='.$_SESSION['id_projeto_logado'].'
									and u.tipo = 3');
				
				$titulo = 'Novo Formul�rio de Avalia��o';
				$mensagem = 'O Formul�rio de Avalia��o '.$_SESSION['chave_formulario'].' foi criado e gostariamos que voc� compartilhasse sua opini�o respondendo-o. Para Responder v� at� o menu Avalia��es > Consultar e clique no Bot�o de Responder do Formul�rio '.$_SESSION['chave_formulario'].'';
				
				while($row = pg_fetch_object($query)) {
					pg_query('insert into notificacoes (titulo,mensagem,id_usuario_remetente,id_usuario_destinatario) values (\''.$titulo.'\',\''.$mensagem.'\','.$row->id_usuario.','.$row->id_usuario.')');
				}
				
				//redirecionar para visualiza��o do formul�rio
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/auxiliares/responderAvaliacao.php\">";
			}
			
			/**
			* A��o do Bot�o Atualizar Pergunta
			*/
			if (isset($_POST['BTUpd'])) {
				$pergunta = new Pergunta;
				$pergunta->get($_SESSION['upd_pergunta']);
				if ($_POST['id_componente'] == '0') $_POST['id_componente'] = null;
				$pergunta->setFrom($_POST);
				$pergunta->save();
								
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consPergunta.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTEditar'])) {
				session_start();
				$_SESSION['upd_pergunta'] = $_POST['BTEditar'];
				header("Location: ../../view/cadastro/cadPergunta.php");
				exit;
			}

			
			/**
			* A��o do Bot�o Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consFormulario.php\">";
			}
			
			/**
			* A��o do Bot�o Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTBackUp'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consPergunta.php\">";
			}
			
			/**
			* A��o do Bot�o Deletar Pergunta
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$pergunta = new Pergunta;
					$pergunta->get($_POST['BTDel']);
					$pergunta->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Imposs�vel deletar registro!\\n\\nEsta Pergunta pode est� sendo usado em alguma rela��o.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consPergunta.php\">";
			}

		}
		
	}
	
	$controle = new PerguntaAction();
	
?>