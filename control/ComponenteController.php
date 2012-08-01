<?

	class ComponenteController {
	
		function ComponenteController() {}
		
		/**
		* Comentário Selecionado
		* Retorno: Objeto
		*/
		function get(){
			$componente = new Componente;
			if(!empty($_SESSION['upd_componente'])){
				$componente->get($_SESSION['upd_componente']);
				$c = $componente->toArray();			
			}
			else {
				$c = $componente->toArray();
			}
			return $c;
		}
		
		/**
		* Todos os Componentes do Projeto Logado
		* Retorno: Lista
		*/
		function listAll() {
			$componente = new Componente;	
			$componente
				->where("id_projeto=".$_SESSION['id_projeto_logado']."")
				->order('nome')
				->find();
			
			return $componente;
		}
		
		/**
		* Exibe os Componentes da Consulta $componentes
		* Retorno: String
		*/
		function printConsulta($componentes){
			$result .= '<tr>';
			$result .= '<th>Nome</th>';
			$result .= '<th>Descrição</th>';
			$result .= '</tr>';
			
			while($componentes->fetch()) {
				$result .= '<tr>';
				$result .= '<td>'.$componentes->nome.'</td><td>'.$componentes->descricao.'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$componentes->id_componente.'"></button></td>';
				if ($_SESSION['tipo_usuario_logado'] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$componentes->id_componente.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Combo com os Componentes do Projeto Logado
		* Retorno: String
		*/
		function getCombo(){
			//carrega componentes do projeto
			$componente = new Componente;
			$componente
				->where("id_projeto=".$_SESSION['id_projeto_logado']."")
				->order('nome')
				->find();
			$componentes = $componente->allToArray();
			foreach($componentes as $item) {
				$result .= '<option value="'.$item['id_componente'].'"';
				if($item['id_componente']==$_SESSION["componente_log"]) $result .= "selected";
				if(isset($_SESSION['upd_tarefa']) and ($item['id_componente']==$_SESSION['obj_tarefa']['id_componente'])) $result .= 'selected';
				if(isset($_SESSION['upd_pergunta']) and ($item['id_componente']==$_SESSION['obj_pergunta']['id_componente'])) $result .= 'selected';
				$result .= '>'.$item['nome'].'</option>';
			}
			return $result;
		}
		
		/**
		* Combo com os Componentes do Projeto Logado (Feedback)
		* Retorno: String
		*/
		function getComboFeedback(){
			$componente = new Componente;
			$componente
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->order('nome')
				->find();
			$componentes = $componente->allToArray();
			foreach($componentes as $item) {
				$result .= '<option value="'.$item['id_componente'].'"';
				if(isset($_SESSION['upd_requisicao']) and ($item['id_componente']==$_SESSION['obj_requisicao']['id_componente'])) $result .= 'selected';
				$result .= '>'.$item['nome'].'</option>';
			}
			return $result;
		}
		
	}

?>