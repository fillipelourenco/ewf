<?

	class UsuarioController {
	
		function UsuarioController() {}
		
		/**
		* Seleciona o Usuario Para Atualizar
		* Retorno: Objeto
		*/
		function get(){
			$usuario = new Usuario;
			if(!empty($_SESSION['upd_usuario'])){
				$usuario->get($_SESSION['upd_usuario']);
				$u = $usuario->toArray();			
			}
			else {
				$u = $usuario->toArray();
			}
			return $u;
		}
		
		/**
		* Lista Todos os Usuarios de Acordo com a Permissão do Usuário Logado
		* Retorno: Lista
		*/
		function listAll() {
			$usuario = new Usuario;
			if ($_SESSION["tipo_usuario_logado"] == '1') {
				$usuario
					->where("id_empresa=".$_SESSION["id_empresa_logada"]."")
					->order('nome')
					->find();
			}
			else {
				$usuario
					->where("id_usuario=".$_SESSION["id_usuario_logado"]."")
					->find();
			}
			return $usuario;
		}
		
		/**
		* Exibe a Consulta de Usuários ($usuarios)
		* Retorno: String
		*/
		function printConsulta($usuarios){
			$result .= '<tr>';
			$result .= '<th>Nome</th>';
			$result .= '<th>E-mail</th>';
			$result .= '<th>Tipo</th>';
			$result .= '</tr>';
			
			while($usuarios->fetch()) {
				if ($usuarios->tipo == 1) $usuarios->tipo = 'Gerente';
				if ($usuarios->tipo == 2) $usuarios->tipo = 'Desenvolvedor';
				if ($usuarios->tipo == 3) $usuarios->tipo = 'Usuário Final';
				$result .= "<tr>";
				$result .= "<td>".$usuarios->nome."</td><td>".$usuarios->email."</td><td>".$usuarios->tipo."</td>";
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$usuarios->id_usuario.'"></button></td>';
				if ($_SESSION["tipo_usuario_logado"] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$usuarios->id_usuario.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
	}

?>