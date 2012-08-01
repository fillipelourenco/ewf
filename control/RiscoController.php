<?

	class RiscoController {
	
		function RiscoController() {}
		
		/**
		* Seleciona o Risco Para Atualizar
		* Retorno: Objeto
		*/
		function get(){
			$risco = new Risco;
			if(!empty($_SESSION['upd_risco'])){
				$risco->get($_SESSION['upd_risco']);
				$r = $risco->toArray();			
			}
			else {
				$r = $risco->toArray();
			}
			return $r;
		}
		
		/**
		* Lista Todos os Riscos de Acordo com a Pesquisa
		* Retorno: Lista
		*/
		function listAll() {
			$risco = new Risco;
			$risco->where("id_projeto=".$_SESSION['id_projeto_logado']."");
									
			if (empty($_SESSION["ordem_log"]) or $_SESSION["ordem_log"] == '0') {
				$risco->order('tipo');
			} else if ($situacao == '1') {
				$risco->order('probabilidade');
			} else if ($situacao == '2') {
				$risco->order('efeito');
			} 
			$risco->find();
			
			return $risco;
		}
		
		/**
		* Exibe a Consulta de Riscos ($riscos)
		* Retorno: String
		*/
		function printConsulta($riscos){
			$result .= '<tr>';
			$result .= '<th>Tipo</th>';
			$result .= '<th>Probabilidade</th>';
			$result .= '<th>Efeito</th>';
			$result .= '<th>Risco</th>';
			$result .= '<th>Estratégia</th>';
			$result .= '</tr>';
			while ($riscos->fetch()) {
				//risco
				if (strlen($riscos->risco) > 50) {
					$riscos->risco = substr($riscos->risco,0,47)."...";
				}
										
				//estrategia
				if (strlen($riscos->estrategia) > 50) {
					$riscos->estrategia = substr($riscos->estrategia,0,47)."...";
				}
										
				$result .= '<tr>';
				$result .= '<td>'.$riscos->tipo.'</td><td>'.$riscos->probabilidade.'</td><td>'.$riscos->efeito.'</td><td>'.$riscos->risco.'</td><td>'.$riscos->estrategia.'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$riscos->id_risco.'"></button></td>';
				if ($_SESSION["tipo_usuario_logado"] == '1') {
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$riscos->id_risco.'"></button></td>';
				}
				$result .= '<tr>';
				}
									
			return $result;
		}
		
	}

?>