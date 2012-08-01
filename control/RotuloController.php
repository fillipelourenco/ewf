<?

	class RotuloController {
	
		function RotuloController() {}
		
		/**
		* Seleciona o Rotulo Para Atualizar
		* Retorno: Objeto
		*/
		function get(){
			$componente = new Rotulo;
			if(!empty($_SESSION['upd_rotulo'])){
				$componente->get($_SESSION['upd_rotulo']);
				$c = $componente->toArray();			
			}
			else {
				$c = $componente->toArray();
			}
			return $c;
		}
		
		/**
		* Lista Todos os Rotulos da Empresa Logada
		* Retorno: Lista
		*/
		function listAll() {
			$rotulo = new Rotulo;	
			$rotulo
				->where("id_empresa=".$_SESSION['id_empresa_logada']."")
				->order('nome')
				->find();
			
			return $rotulo;
		}
		
		/**
		* Exibe a Consulta de Rotulos ($rotulos)
		* Retorno: String
		*/
		function printConsulta($rotulos){
			$result .= '<tr>';
			$result .= '<th>Nome</th>';
			$result .= '</tr>';
			
			while($rotulos->fetch()) {
				$result .= "<tr>";
				$result .= "<td>".$rotulos->nome."</td>";
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$rotulos->id_rotulo.'"></button></td>';
				if ($_SESSION["tipo_usuario_logado"] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$rotulos->id_projeto.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Combo com os Rótulos da Empresa Logada
		* Retorno: String
		*/
		function getCombo(){
			$rotulo = new Rotulo;
			$rotulo
				->where("id_empresa=".$_SESSION['id_empresa_logada']."")
				->order('nome')
				->find();
			$rotulos = $rotulo->allToArray();
			foreach($rotulos as $item) {
				$result .= '<option value="'.$item['id_rotulo'].'"';
				if($item['id_rotulo']==$_SESSION["tipo_log"]) $result .= "selected"; 
				if(isset($_SESSION['upd_tarefa']) and ($item['id_rotulo']==$_SESSION['obj_tarefa']['id_rotulo'])) $result .= 'selected';
				$result .= '>'.$item['nome'].'</option>';
			}
			return $result;
		}
		
	}

?>