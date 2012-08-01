<?

	class FormularioController {
	
		function FormularioController() {}
		
		/**
		* Comentário Selecionado
		* Retorno: Objeto
		*/
		function get(){
			$formulario = new Formulario;
			if(!empty($_SESSION['upd_formulario'])){
				$formulario->get($_SESSION['upd_formulario']);
				$f = $formulario->toArray();	
			}
			else {
				$f = $formulario->toArray();
			}
			return $f;
		}
		
		/**
		* Todos os Formularios do Projeto Logado
		* Retorno: Lista
		*/
		function listAll() {
			$formulario = new Formulario;	
			$versao = new Versao;
			$formulario
				->select('f.id_formulario,f.chave,f.descricao,f.status,v.master_version,v.great_version,v.average_version,v.small_version')
				->alias('f')
				->join($versao,'inner','v')
				->where("f.id_projeto=".$_SESSION['id_projeto_logado']."")
				->order('f.status desc,f.id_formulario')
				->find();
			
			return $formulario;
		}
		
		/**
		* Todos os Formularios Ativos do Projeto Logado
		* Retorno: Lista
		*/
		function listAtivos() {
			$formulario = new Formulario;	
			$versao = new Versao;
			$formulario
				->select('f.id_formulario,f.chave,f.descricao,f.status,v.master_version,v.great_version,v.average_version,v.small_version')
				->alias('f')
				->join($versao,'inner','v')
				->where('f.id_projeto='.$_SESSION['id_projeto_logado'].' and f.status=true')
				->order('f.id_formulario')
				->find();
			
			return $formulario;
		}
		
		/**
		* Todos os Formularios Fechados do Projeto Logado (Limite 10)
		* Retorno: Lista
		*/
		function listFechados() {
			$formulario = new Formulario;	
			$versao = new Versao;
			$formulario
				->select('f.id_formulario,f.chave,f.descricao,f.status,v.master_version,v.great_version,v.average_version,v.small_version')
				->alias('f')
				->join($versao,'inner','v')
				->where('f.id_projeto='.$_SESSION['id_projeto_logado'].' and status=false')
				->order('f.id_formulario')
				->limit(10)
				->find();
			
			return $formulario;
		}
		
		/**
		* Imprime o Menu dos Formulários ($formularios)
		* Retorno: Lista
		*/
		function printMenu($formularios){
			while($formularios->fetch()) {
				$result .= '<li><a href="javascript:selecionaFormulario(\''.$formularios->id_formulario.'\');"><span>'.$formularios->chave.' Versão '.$formularios->master_version.'.'.$formularios->great_version.'.'.$formularios->average_version.'.'.$formularios->small_version.'</span></a></li>';
			}
			return $result;
		}
		
		/**
		* Imprime o Menu dos Formulários ($formulariosEncerrados)
		* Retorno: Lista
		*/
		function printMenuAvaliacoes($formularios){
			while($formularios->fetch()) {
				$result .= '<li><a href="javascript:visualizaResultados(\''.$formularios->id_formulario.'\');"><span>'.$formularios->chave.' Versão '.$formularios->master_version.'.'.$formularios->great_version.'.'.$formularios->average_version.'.'.$formularios->small_version.'</span></a></li>';
			}
			return $result;
		}
		
		/**
		* Exibe os Formulários da Consulta $formularios
		* Retorno: String
		*/
		function printConsulta($formularios){
			$result .= '<tr>';
			$result .= '<th>Chave</th>';
			$result .= '<th>Descrição</th>';
			$result .= '<th>Versão</th>';
			$result .= '<th>Status</th>';
			$result .= '</tr>';
			
			while($formularios->fetch()) {
				if ($formularios->status == 't') $formularios->status = 'Aberto';
				else $formularios->status = 'Fechado';
				$result .= '<tr>';
				$result .= '<td>'.$formularios->chave.'</td><td>'.$formularios->descricao.'</td><td>'.$formularios->master_version.'.'.$formularios->great_version.'.'.$formularios->average_version.'.'.$formularios->small_version.'</td><td>'.$formularios->status.'</td>';
				if ($formularios->status == 'Aberto')
					$result .= '<td><button class="responder" title="Responder Formulario" type="submit" name="BTResponder" value="'.$formularios->id_formulario.'"></button></td>';
				if ($_SESSION['tipo_usuario_logado'] == '1') {
					$result .= '<td><button class="editar" title="Editar Formulario" type="submit" name="BTEditar" value="'.$formularios->id_formulario.'"></button></td>';
					$result .= '<td><button class="pesquisar" title="Editar Perguntas" type="submit" name="BTPerguntas" value="'.$formularios->id_formulario.'"></button></td>';
					$result .= '<td><button class="grafico" title="Visualizar Resultados" type="submit" name="BTGraph" value="'.$formularios->id_formulario.'"></button></td>';
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="deletar" title="Deletar" type="submit" name="BTDel" value="'.$formularios->id_formulario.'"></button></td>';
				}
				$result .= '<tr>';
			}
			return $result;
		}
		
		function printFormulario($formulario){
			$query = pg_query('select * from versoes where id_versao='.$formulario['id_versao'].'');
			$versao = pg_fetch_object($query);
			$query = pg_query('select * from perguntas where id_formulario='.$formulario['id_formulario'].' order by id_pergunta');
			$result .= '<div>'.$formulario['descricao'].'</div>';
			$result .= '<div>Versão: '.$versao->master_version.'.'.$versao->great_version.'.'.$versao->average_version.'.'.$versao->small_version.'</div><br>';
			while($row = pg_fetch_object($query)) {
				$result .= '<p><label for="titulo" style="width:900px;">'.$row->titulo.'</label><br>';
				if ($row->tipo == '0') {
					$result .= '1<font color="#ffffff">..</font><input type="radio" value="1" name="'.$row->id_pergunta.'">
								2<font color="#ffffff">..</font><input type="radio" value="2" name="'.$row->id_pergunta.'">
								3<font color="#ffffff">..</font><input type="radio" value="3" name="'.$row->id_pergunta.'">
								4<font color="#ffffff">..</font><input type="radio" value="4" name="'.$row->id_pergunta.'">
								5<font color="#ffffff">..</font><input type="radio" value="5" name="'.$row->id_pergunta.'">';
				}
				else {
					$result .= '<p><textarea cols="90" rows="5" name="'.$row->id_pergunta.'"></textarea>';
				}
				$result .= '</p><br>';
			}
			return $result;
		}
		
	}

?>