<?

	class PerguntaController {
	
		function PerguntaController() {}
		
		/**
		* Pergunta Selecionado
		* Retorno: Objeto
		*/
		function get(){
			$pergunta = new Pergunta;
			if(!empty($_SESSION['upd_pergunta'])){
				$pergunta->get($_SESSION['upd_pergunta']);
				$p = $pergunta->toArray();	
			}
			else {
				$p = $pergunta->toArray();
			}
			session_start();
			$_SESSION['obj_pergunta'] = $p;
			return $p;
		}
		
		/**
		* Todas Perguntas do Formulário Logado
		* Retorno: Lista
		*/
		function listAll() {
			$pergunta = new Pergunta;
			$formulario = new Formulario;	
			$componente = new Componente;
			$pergunta
				->select('p.id_pergunta,p.titulo,p.tipo,p.id_componente,c.nome')
				->alias('p')
				->join($componente,'left','c')
				->join($formulario,'inner','f')
				->where("p.id_formulario=".$_SESSION['upd_formulario']."")
				->order('p.id_pergunta')
				->find();
			
			return $pergunta;
		}
		
		/**
		* Exibe as Perguntas da Consulta $perguntas
		* Retorno: String
		*/
		function printConsulta($perguntas){
			$result .= '<tr>';
			$result .= '<th>Título</th>';
			$result .= '<th>Tipo</th>';
			$result .= '<th>Componente</th>';
			$result .= '</tr>';
			
			while($perguntas->fetch()) {
				if ($perguntas->tipo == 1) $perguntas->tipo = 'Aberta';
				else $perguntas->tipo = 'Escala';
				if ($perguntas->nome == '') $perguntas->nome = 'Todos';
				$result .= '<tr>';
				$result .= '<td>'.$perguntas->titulo.'</td><td>'.$perguntas->tipo.'</td><td>'.$perguntas->nome.'</td>';
				if ($_SESSION['tipo_usuario_logado'] == '1') {
					$result .= '<td><button class="editar" title="Editar Pergunta" type="submit" name="BTEditar" value="'.$perguntas->id_pergunta.'"></button></td>';
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="deletar" title="Deletar" type="submit" name="BTDel" value="'.$perguntas->id_pergunta.'"></button></td>';
				}
				$result .= '<tr>';
			}
			return $result;
		}
		
	}

?>