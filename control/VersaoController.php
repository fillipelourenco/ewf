<?

	class VersaoController {
	
		function VersaoController() {}
		
		/**
		* Seleciona a Versão Para Atualizar
		* Retorno: Objeto
		*/
		function get(){
			$versao = new Versao;
			if(!empty($_SESSION['upd_versao'])){
				$versao->get($_SESSION['upd_versao']);
				$v = $versao->toArray();			
			}
			else {
				$v = $versao->toArray();
			}
			return $v;
		}
		
		/**
		* Combo com as Versões do Projeto Logado
		* Retorno: String
		*/
		function getCombo(){
			$versao = new Versao;
			$versao
				->where("id_projeto=".$_SESSION['id_projeto_logado']."")
				->order('data_cadastro')
				->find();
			$versoes = $versao->allToArray();
			foreach($versoes as $item) {
				$result .= '<option value="'.$item['id_versao'].'"';
				if($item['id_versao']==$_SESSION["versao_log"]) $result .= "selected";
				//if(isset($_SESSION['upd_tarefa']) and ($item['id_componente']==$_SESSION['obj_tarefa']['id_componente'])) $result .= 'selected';
				$result .= '>'.$item['master_version'].'.'.$item['great_version'].'.'.$item['average_version'].'.'.$item['small_version'].'</option>';
			}
			return $result;
		}
		
		/**
		* Seleciona a Versão Atual do Sistema
		* Retorno: Objeto
		*/
		function versaoAtual(){
			$versao = new Versao;
			$versao
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->order('id_versao desc')
				->limit(1)
				->find(true);
			return $versao;
		}
		
		/**
		* Lista as Tarefas a Serem Incluidas na Versão
		* Retorno: String
		*/
		function tarefasIncluidas() {
			$query = pg_query('select * from tarefas where situacao = 4 and id_projeto = '.$_SESSION['id_projeto_logado'].' and id_tarefa not in (select id_tarefa from versoes_tarefas) order by id_tarefa');
			while ($row = pg_fetch_object($query)){
				$result .= '<a href="">'.$row->chave.'</a>, ';
			}
			return substr($result, 0, -2);
		}
		
		
		function coletasIncluidas() {

		}
		
		/**
		* Lista Todas as Versões do Projeto Logado
		* Retorno: Lista
		*/
		function listAll(){
			$versao = new Versao;
			$versao
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->order('id_versao desc')
				->find();
			return $versao;
		}
		
		/**
		* Exibe a Consulta de Versões ($versoes)
		* Retorno: String
		*/
		function printConsulta($versoes){
			$result .= '<tr>';
			$result .= '<th>Versão</th>';
			$result .= '<th>Descrição</th>';
			$result .= '<th>Data</th>';
			
			while($versoes->fetch()) {
				$result .= '<tr>';
				$result .= '<td>'.$versoes->master_version.'.'.$versoes->great_version.'.'.$versoes->average_version.'.'.$versoes->small_version.'</td><td>'.$versoes->descricao.'</td><td>'.FormataDataNumeros($versoes->data_cadastro).'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$versoes->id_versao.'"></button></td>';
				if (($_SESSION['tipo_usuario_logado'] == '1') and (date('Y-m-d')==$versoes->data_cadastro))
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$versoes->id_versao.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Lista Tarefas da Versão ($versao) Consultada
		* Retorno: String
		*/
		function tarefasVersao($versao) {
			$query = pg_query('select * from tarefas where id_tarefa in (select id_tarefa from versoes_tarefas where id_versao='.$versao.') order by id_tarefa');
			while ($row = pg_fetch_object($query)){
				$result .= '<a href="">'.$row->chave.'</a>, ';
			}
			return substr($result, 0, -2);
		}
		
		/**
		* Insere as Tarefas Consolidadas a Nova Versão
		* Retorno: void
		*/
		function insereTarefasVersao(){
			$versao = new Versao;
			$versao
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->order('id_versao desc')
				->limit(1)
				->find();
			$versao->fetch();
			
			$query = pg_query('select id_tarefa from tarefas where situacao = 4 and id_projeto = '.$_SESSION['id_projeto_logado'].' and id_tarefa not in (select id_tarefa from versoes_tarefas) order by id_tarefa');
			while ($row = pg_fetch_object($query)){
				pg_query('insert into versoes_tarefas (id_versao,id_tarefa) values ('.$versao->id_versao.','.$row->id_tarefa.')');
			}
			
		}
		
		/**
		* Verifica se a Versão Está Ligada a Alguma Coleta
		* Retorno: Boolean
		*/
		function podeExcluir(){
			//verificar se id_versao está em alguma coleta
			return true;
		}
		
		/**
		* Desnvincula as Tarefas da Versão
		* Retorno: void
		*/
		function desvinculaTarefasColetas($id_versao){
			pg_query('DELETE FROM versoes_tarefas WHERE id_versao='.$id_versao.'');
		}
		
		/**
		* Valida o Número da Nova Versão
		* Retorno: Boolean
		*/
		function validaNovaVersao($m,$g,$a,$s){
			$atual = $this->versaoAtual();
			if ($m > $atual->master_version)
				return true;
			else
				if (($m == $atual->master_version) and ($g > $atual->great_version))
					return true;
				else
					if (($m == $atual->master_version) and ($g == $atual->great_version) and ($a > $atual->average_version))
						return true;
					else
						if (($m == $atual->master_version) and ($g == $atual->great_version) and ($a == $atual->average_version) and ($s > $atual->small_version))
							return true;
						else
							return false;
		}
		
	}

?>