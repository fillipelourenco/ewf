<?

	class ComentarioController {
	
		function ComentarioController() {}
		
		/**
		* Comentários da Tarefa Selecionada
		* Retorno: String
		*/
		function printComentarios(){
			if (!empty($_SESSION['upd_tarefa'])){
				$comentario = new Comentario;
				$usuario = new Usuario;
				$comentario
					->alias('c')
					->join($usuario,'inner','u')
					->where("id_tarefa=".$_SESSION['upd_tarefa']."")
					->order('momento_comentario')
					->find();
				$comentarios = $comentario->allToArray();
			
				$result .= '<div id="coluna-1" style="width:860px;">';
				
				$first = true;
				foreach ($comentarios as $item) {
					if ($first) {
						$result .= '<br><p><label for="titulo">Comentarios:</label>';
						$first = false;
					} else $result .= '<p><label for="titulo"><font color="#ccc">.</font></label>';
					$result .= '<textarea cols="92" rows="3" name="com" disabled>'.$item['comentario'].'</textarea></p>';
					$result .= '<p><label for="titulo"><font color="#ccc">.</font></label>'; 
					$result .= '<p class="comentario">'.$item['nome'].' / '.FormataDataHora($item['momento_comentario']).' ';
					if ($_SESSION["id_usuario_logado"] == $item['id_usuario'])
						$result .= '<button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Apagar Comentário" type="submit" name="BTDelCom" value="'.$item['id_comentario'].'"></button>';
					$result .= '</p>';	
				}
				
				$result .= '<br>';
				$result .= '<p><label for="titulo">Novo Comentário:</label>';
                $result .= '<textarea cols="92" rows="3" name="comentario" id="comentario" ></textarea></p>';
				$result .= '<p><label for="titulo"><font color="#ffffff">.</font></label>';
				$result .= '<input name="BTCom" class="formbutton" value="Adicionar" type="submit" /></p>';
				$result .= '</div>';
			}
			
			return $result;
		}

	}

?>