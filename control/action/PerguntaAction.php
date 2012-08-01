<?
	require_once '../../conf/config.php';

	class PerguntaAction {
	
		function PerguntaAction() {
			
			session_start();
			
			/**
			* Ação do Botão Nova Pergunta
			*/
			if (isset($_POST['BTNova'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/cadastro/cadPergunta.php\">";
			}
			
			/**
			* Ação do Botão Adicionar Mais Perguntas
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
			* Ação do Botão Finalizar Formulário
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
				
				$titulo = 'Novo Formulário de Avaliação';
				$mensagem = 'O Formulário de Avaliação '.$_SESSION['chave_formulario'].' foi criado e gostariamos que você compartilhasse sua opinião respondendo-o. Para Responder vá até o menu Avaliações > Consultar e clique no Botão de Responder do Formulário '.$_SESSION['chave_formulario'].'';
				
				while($row = pg_fetch_object($query)) {
					pg_query('insert into notificacoes (titulo,mensagem,id_usuario_remetente,id_usuario_destinatario) values (\''.$titulo.'\',\''.$mensagem.'\','.$row->id_usuario.','.$row->id_usuario.')');
				}
				
				//redirecionar para visualização do formulário
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/auxiliares/responderAvaliacao.php\">";
			}
			
			/**
			* Ação do Botão Atualizar Pergunta
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
			* Ação do Botão Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTEditar'])) {
				session_start();
				$_SESSION['upd_pergunta'] = $_POST['BTEditar'];
				header("Location: ../../view/cadastro/cadPergunta.php");
				exit;
			}

			
			/**
			* Ação do Botão Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTBack'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consFormulario.php\">";
			}
			
			/**
			* Ação do Botão Voltar para Consulta de Formularios
			*/
			if (isset($_POST['BTBackUp'])) {
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consPergunta.php\">";
			}
			
			/**
			* Ação do Botão Deletar Pergunta
			*/
			if (isset($_POST['BTDel'])) {
				try{
					$pergunta = new Pergunta;
					$pergunta->get($_POST['BTDel']);
					$pergunta->delete();
				}
				catch(Exception $e){
					echo "<script type='text/javascript'>alert('Impossível deletar registro!\\n\\nEsta Pergunta pode está sendo usado em alguma relação.');</script>";
				}
				echo "<meta http-equiv=\"Refresh\" content=\"0;URL=../../view/consulta/consPergunta.php\">";
			}

		}
		
	}
	
	$controle = new PerguntaAction();
	
?>