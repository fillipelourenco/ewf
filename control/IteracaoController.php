<?

	class IteracaoController {
	
		function IteracaoController() {}
		
		/**
		* Iterações da Requisição Selecionada
		* Retorno: String
		*/
		function printIteracoes(){
			if (!empty($_SESSION['upd_requisicao'])){
				
				$iteracao = new Iteracao;
				$usuario = new Usuario;
				$iteracao
					->alias('i')
					->join($usuario,'inner','u','id_usuario','id_usuario')
					->where("i.id_requisicao=".$_SESSION['upd_requisicao']."")
					->order('i.momento_resposta')
					->find();
				$iteracoes = $iteracao->allToArray();
				
				$result .= '<div id="coluna-1" style="width:860px;">';
				
				$first = true;
				foreach ($iteracoes as $item) {
					if ($first) {
						$result .= '<br><p><label for="titulo">Respostas:</label>';
						$first = false;
					} else $result .= '<p><label for="titulo"><font color="#ccc">.</font></label>';
					$result .= '<textarea cols="92" rows="3" name="com" disabled>'.$item['resposta'].'</textarea></p>';
					$result .= '<p><label for="titulo"><font color="#ccc">.</font></label>'; 
					$result .= '<p class="comentario">'.$item['nome'].' / '.FormataDataHora($item['momento_resposta']).' ';
					if ($_SESSION["id_usuario_logado"] == $item['id_usuario'])
						$result .= '<button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Apagar Comentário" type="submit" name="BTDelRes" value="'.$item['id_iteracao'].'"></button>';
					$result .= '</p>';	
				}
				
				$result .= '<br>';
				$result .= '<p><label for="titulo">Nova Resposta:</label>';
                $result .= '<textarea cols="92" rows="3" name="resposta" id="resposta" ></textarea></p>';
				$result .= '<p><label for="titulo"><font color="#ffffff">.</font></label>';
				$result .= '<input name="BTRes" class="formbutton" value="Adicionar" type="submit" /></p>';
				$result .= '</div>';
			}
			
			return $result;
		}

	}

?>