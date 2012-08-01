<?

	class ClienteController {
	
		function ClienteController() {}
		
		/**
		* Cliente Selecionado
		* Retorno: Objeto
		*/
		function get(){
			$cliente = new Cliente;
			if(!empty($_SESSION['upd_cliente'])){
				$cliente->get($_SESSION['upd_cliente']);
				$c = $cliente->toArray();			
			}
			else {
				$c = $cliente->toArray();
			}
			return $c;
		}
		
		/**
		* Todos os Clientes da Empresa Logado
		* Retorno: Lista
		*/
		function listAll() {
			$cliente = new Cliente;	
			$cliente
				->where("id_empresa=".$_SESSION['id_empresa_logada']."")
				->order('razao_social')
				->find();
			
			return $cliente;
		}
		
		/**
		* Exibe os Clientes da Consulta $clientes
		* Retorno: String
		*/
		function printConsulta($clientes){
			$result .= '<tr>';
			$result .= '<th>Razão Social</th>';
			$result .= '<th>Nome Curto</th>';
			$result .= '<th>Responsável</th>';
			$result .= '</tr>';
			
			while($clientes->fetch()) {
				$result .= '<tr>';
				$result .= '<td>'.$clientes->razao_social.'</td><td>'.$clientes->nome_curto.'</td><td>'.$clientes->responsavel.'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$clientes->id_cliente.'"></button></td>';
				if ($_SESSION['tipo_usuario_logado'] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$clientes->id_cliente.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Combo com os Cliente da Empresa
		* Retorno: String
		*/
		function getCombo($id_cliente){
			$cliente = new Cliente;
			$cliente
				->where("id_empresa=".$_SESSION['id_empresa_logada']."")
				->order('nome_curto')
				->find();
			$clientes = $cliente->allToArray();
			foreach($clientes as $item) {
				$result .= '<option value="'.$item['id_cliente'].'"';
				if(isset($_SESSION['upd_usuario']) and ($item['id_cliente']==$id_cliente)) $result .= 'selected';
				$result .= '>'.$item['nome_curto'].'</option>';
			}
			return $result;
		}
		
		/**
		* Combo com os Cliente da Empresa
		* Retorno: String
		*/
		function getComboTodos(){
			$cliente = new Cliente;
			$cliente
				->where("id_empresa=".$_SESSION['id_empresa_logada']."")
				->order('nome_curto')
				->find();
			$clientes = $cliente->allToArray();
			foreach($clientes as $item) {
				$result .= '<option value="'.$item['id_cliente'].'"';
				if($_SESSION['cliente_log'] == $item['id_cliente']) $result .= ' selected ';
				$result .= '>'.$item['nome_curto'].'</option>';
			}
			return $result;
		}
		
	}

?>