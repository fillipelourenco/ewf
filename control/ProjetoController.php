<?

	class ProjetoController {
	
		function ProjetoController() {}
		
		/**
		* Exibe o Sub-Menu do Projeto
		* Retorno: String
		*/
		function loadFiltros(){
			$result .= '<p class="filtros">';
			if ($_SESSION['tipo_usuario_logado']=='1'){
				$result .= '[<a href="?projeto=permissao" title="Permitir Acesso de Novos Desenvolvedores/Usuários ao Projeto">Adicionar Permissão</a>] ';
				$result .= '[<a href="?projeto=altera" title="Altera Nome e/ou Descrição do Projeto">Alterar Dados</a>] ';
			}
			$result .= '[<a href="?projeto=tarefa" title="Cadastrar Nova Tarefa ao Projeto">Nova Tarefa</a>] ';
			if ($_SESSION['tipo_usuario_logado']=='1')
				$result .= '[<a onClick="if(confirm(\'Deseja Excluir Esse Projeto? \\n\\nEsta Ação é IRREVERSÍVEL!\\n\\nTodos os Dados Ligados ao Projeto Serão Excluídos!\')){return true;}else{return false;}" href="?projeto=exclui" title="Exclui Projeto">Excluir</a>]</p>';
			return $result;
		}
		
		/**
		* Seleciona o Projeto do $id_projeto
		* Retorno: Objeto
		*/
		function get($id_projeto){
			//carrega dos dados do projeto selecionado
			$projeto = new Projeto;
			$projeto->get($id_projeto);
			$p = $projeto->toArray();
						
			//registrar o login do projeto na sessao
			$_SESSION['login_projeto_logado'] = $p['login'];
			$_SESSION['nome_projeto_logado'] = $p['nome'];
			$_SESSION['descricao_projeto_logado'] = $p['descricao'];
			
			return $p;
		}
		
		/**
		* Seleciona o Projeto Para Atualizar
		* Retorno: Objeto
		*/
		function getObj(){	
			$projeto = new Projeto;
			if(!empty($_SESSION['upd_projeto'])){
				$projeto->get($_SESSION['upd_projeto']);
				$p = $projeto->toArray();
			} else {
				$p = $projeto->toArray();
			}
			return $p;
		}
		
		/**
		* Lista Todos os Projetos de Acordo com o Tipo do Usuário Logado
		* Retorno: Lista
		*/
		function listAll() {
			$permissao = new Permissao;
			$projeto = new Projeto;
			$projeto->alias('p');
			if($_SESSION['tipo_usuario_logado'] == '1'){
				$projeto
					->where('p.id_empresa='.$_SESSION['id_empresa_logada'].'');
			}
			else {
				$projeto
					->join($permissao,'INNER','pr')
					->where('pr.id_usuario='.$_SESSION['id_usuario_logado'].'');
			}
			$projeto
				->order('p.nome')
				->find();
			
			return $projeto;
		}
		
		/**
		* Exibe Lista de Projetos no Menu do Sistema
		* Retorno: String
		*/
		function listMenu($projeto) {
			while ($projeto->fetch()) {
				$result .= '<li>
							<a style="font-size:13px;" href="javascript:selecionaProjetoMenu(\''.$projeto->id_projeto.'\');"><span>'.$projeto->nome.'</span></a>
							</li>';
			}
			return $result;
		}
		
		/**
		* Exibe Lista de Projetos no Frame do Sistema
		* Retorno: String
		*/
		function listFrame($projeto){
			while ($projeto->fetch()) {
				
				$tarefa = new Tarefa;
				$tarefa
					->where('id_projeto='.$projeto->id_projeto.' and situacao = 4')
					->find();
				
				$cont_f = $tarefa->allToArray();
				$qtdF = count($cont_f);
				$tarefa->reset();
						
				$tarefa
					->where('id_projeto='.$projeto->id_projeto.'')
					->find();

				$cont_t = $tarefa->allToArray();
				$qtdT = count($cont_t);
				if($qtdT==0) $qtdT=1;
				$progresso = (100*$qtdF)/$qtdT;
				if ($progresso < 30) $cor = 'red';
				else if ($progresso > 30 and $progresso < 70 ) $cor = 'blue';
				else $cor = 'green';

				$result .= '<p style="margin-left:5px;">
							<a style="font-size:13px;" href="javascript:selecionaProjetoFrame(\''.$projeto->id_projeto.'\');">'.$projeto->nome.'</a>
							'.show_prog_bar(300, number_format($progresso,2), $cor).'</p><br/>';
			}
			return $result;
		
		}
		
		/**
		* Exibe o Progresso do Projeto Selecionado
		* Retorno: PgBar (String)
		*/
		function loadProgresso(){
			$tarefa = new Tarefa;
			$tarefa
				->where('id_projeto='.$_SESSION['id_projeto_logado'].' and situacao = 4')
				->find();
			$cont_f = $tarefa->allToArray();
			$qtdF = count($cont_f);
			$tarefa->reset();
							
			$tarefa
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->find();
			$cont_t = $tarefa->allToArray();
			$qtdT = count($cont_t);
			if($qtdT==0) $qtdT=1;	
			$progresso = (100*$qtdF)/$qtdT;
			
			if ($progresso < 30) $cor = 'red';
			else if ($progresso > 30 and $progresso < 70 ) $cor = 'blue';
			else $cor = 'green';
			
			return show_prog_bar(300, number_format($progresso,2), $cor);
		}
		
		/**
		* Exclui todos os dados do Projeto $id_projeto
		* Retorno: void
		*/
		function excluirProjeto($id_projeto){
			pg_query('delete from comentarios where id_tarefa in (select id_tarefa from tarefas where id_projeto='.$id_projeto.')');
			pg_query('delete from logs_tarefas where chave in (select chave from tarefas where id_projeto='.$id_projeto.')');
			pg_query('delete from versoes_tarefas where id_tarefa in (select id_tarefa from tarefas where id_projeto='.$id_projeto.')');
			pg_query('delete from tarefas where id_projeto='.$id_projeto.'');
			pg_query('delete from versoes where id_projeto='.$id_projeto.'');
			pg_query('delete from permissoes where id_projeto='.$id_projeto.'');
			pg_query('delete from riscos where id_projeto='.$id_projeto.'');
			pg_query('delete from componentes where id_projeto='.$id_projeto.'');
			pg_query('delete from projetos where id_projeto='.$id_projeto.'');
		}
		
	}

?>