<?

	class PermissaoController {
	
		function PermissaoController() {}
		
		/**
		* Carrega as Permisões do Projeto Selecionado
		* Retorno: Lista
		*/
		function loadPermissoes(){
		
			$usuario = new Usuario;
			$permissao = new Permissao;
			
			$usuario
				->alias('u')
				->join($permissao,'inner','per','id_usuario','id_usuario')
				->where('per.id_projeto='.$_SESSION['id_projeto_logado'].' and per.integra=false')
				->order('u.tipo, u.nome')
				->find();
				
			return $usuario;		
		}
		
		/**
		* Exibe os Usuários ($usuarios) Permitidos para o Projeto
		* Retorno: String
		*/
		function printPermissoesProjeto($usuarios){
			$g = true;
			$d = true;
			$c = true;
			$u = true;
			
			$result .= '<form action="../control/action/UsuarioAction.php" method="post" name="FormUsuarios" id="FormUsuarios">';
			$result .= '<input type="hidden" name="upd_usuario" value="">';
			
			while ($usuarios->fetch()) {
			    if ($usuarios->tipo == 1)  {
					if ($g) {
						$result .= 'Gerentes: ';
						$g = false;
					}
					else $result .= ', ';
					$result .= '<a href="javascript:visualizarPerfil(\''.$usuarios->id_usuario.'\');">'.$usuarios->nome.'</a>';
				}
				else {
					if ($usuarios->tipo == 2)  {
						if ($d) {
							$result .= '<br>Desenvolvedores: ';
							$d = false;
						}
						else $result .= ', ';
						$result .= '<a href="javascript:visualizarPerfil(\''.$usuarios->id_usuario.'\');">'.$usuarios->nome.'</a>';
					}
					else {
						if ($usuarios->tipo == 3)  {
							if ($c) {
								$result .= '<br>Usuários Finais: ';
								$c = false;
							}
							else $result .= ', ';
							$result .= '<a href="javascript:visualizarPerfil(\''.$usuarios->id_usuario.'\');">'.$usuarios->nome.'</a>';
						}
					}
				}
			}
			$result .= '</form>';
			return $result;
		}
		
		/**
		* Exibe a Consulta de Permissões ($permissoes)
		* Retorno: String
		*/
		function printConsulta($permissoes){
			$result .= '<tr>';
			$result .= '<th>Nome</th>';
			$result .= '<th>Tipo</th>';
			$result .= '<th>E-mail</th>';
			$result .= '<th>Integrado</th>';
			$result .= '</tr>';

			while($permissoes->fetch()) {
				if ($permissoes->integra == 't') $permissoes->integra = 'Sim';
				else $permissoes->integra = 'Não';
				if ($permissoes->tipo == 1) $permissoes->tipo = 'Gerente';
				if ($permissoes->tipo == 2) $permissoes->tipo = 'Desenvolvedor';
				if ($permissoes->tipo == 3) $permissoes->tipo = 'Cliente';
				if ($permissoes->tipo == 4) $permissoes->tipo = 'Usuário Final';
				$result .= "<tr>";
				$result .= "<td>".$permissoes->nome."</td><td>".$permissoes->tipo."</td><td>".$permissoes->email."</td><td>".$permissoes->integra."</td>";
				$result .= '<td><button onClick="if(confirm(\'Deseja remover essa permissão?\')){return true;}else{return false;}" class="delete" title="Remover Permissão" type="submit" name="BTDel" value="'.$permissoes->id_usuario.'"></button></td>';
				$result .= '</tr>';
			}
			return $result;
		}
		
		/**
		* Combo com os Usuários Permitidos do Projeto Logado
		* Retorno: String
		*/
		function getComboPermissoes(){
			$usuario = new Usuario;
			$permissao = new Permissao;
			$usuario
				->alias('u')
				->join($permissao,'inner','up','id_usuario','id_usuario')
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->order('u.nome')
				->find();
			$usuarios = $usuario->allToArray();
			
			foreach($usuarios as $item) {
				$result .= '<option value="'.$item['id_usuario'].'"';
				if($item['id_usuario']==$_SESSION['obj_tarefa']['id_usuario_requisitante']) $result .= ' selected';
				$result .= '>'.$item['nome'].'</option>';
			}
			return $result;
		}
		
		/**
		* Combo dos Desenvolvedores e Gerentes Permitidos no Projeto Logado
		* Retorno: String
		*/
		function getComboDesenvolvedores(){
			$usuario = new Usuario;
			$permissao = new Permissao;
			$usuario
				->alias('u')
				->join($permissao,'inner','up','id_usuario','id_usuario')
				->where('id_projeto='.$_SESSION['id_projeto_logado'].' and (tipo=1 or tipo=2)')
				->order('u.nome')
				->find();
			$usuarios = $usuario->allToArray();
			
			foreach($usuarios as $item) {
				$result .= '<option value="'.$item['id_usuario'].'"';
				if($item['id_usuario']==$_SESSION['obj_tarefa']['id_usuario_responsavel']) $result .= ' selected';
				$result .= '>'.$item['nome'].'</option>';
			}
			return $result;
		}
		
		/**
		* Combo dos Usuarios Permitidos no Projeto Logado
		* Retorno: String
		*/
		function getComboUsuarios(){
			$usuario = new Usuario;
			$permissao = new Permissao;
			$usuario
				->alias('u')
				->join($permissao,'inner','up','id_usuario','id_usuario')
				->where('id_projeto='.$_SESSION['id_projeto_logado'].'')
				->order('u.nome')
				->find();
			$usuarios = $usuario->allToArray();
			
			foreach($usuarios as $item) {
				$result .= '<option value="'.$item['id_usuario'].'"';
				if (!empty($_SESSION['upd_requisicao'])) {
					if($item['id_usuario']==$_SESSION['obj_requisicao']['id_usuario_solicitante']) $result .= ' selected';
				}
				else {
					if($item['id_usuario']==$_SESSION['id_usuario_logado']) $result .= ' selected';
				}
				if ($item['id_cliente'] != null) {
					$cliente = new Cliente;
					$cliente->get($item['id_cliente']);
					$result .= '>'.$item['nome'].' ('.$cliente->nome_curto.')</option>';
				}
				else {
					$result .= '>'.$item['nome'].'</option>';
				}
			}
			return $result;
		}
		
		/**
		* Combo dos Usuarios Permitidos no Projeto Logado
		* Retorno: String
		*/
		function getComboResponsaveis(){
			$usuario = new Usuario;
			$permissao = new Permissao;
			$usuario
				->alias('u')
				->join($permissao,'inner','up','id_usuario','id_usuario')
				->where('id_projeto='.$_SESSION['id_projeto_logado'].' and tipo <> 3')
				->order('u.nome')
				->find();
			$usuarios = $usuario->allToArray();
			
			foreach($usuarios as $item) {
				$result .= '<option value="'.$item['id_usuario'].'"';
				if (!empty($_SESSION['upd_requisicao'])) {
					if($item['id_usuario']==$_SESSION['obj_requisicao']['id_usuario_responsavel']) $result .= ' selected';
				}
				if($_SESSION['responsavel_log'] == $item['id_usuario']) $result .= ' selected';
				$result .= '>'.$item['nome'].'</option>';
			}
			return $result;
		}
		
		/**
		* Combo dos Usuarios Permitidos no Projeto Logado
		* Retorno: String
		*/
		function getComboUsuariosIntegracao(){
			$usuario = new Usuario;
			$permissao = new Permissao;
			$cliente = new Cliente;
			$usuario
				->alias('u')
				->join($permissao,'inner','p','id_usuario','id_usuario')
				->join($cliente,'left','c','id_cliente','id_cliente')
				->where('p.id_projeto='.$_SESSION['id_projeto_logado'].' and p.integra=true')
				->order('u.nome')
				->find();
			$usuarios = $usuario->allToArray();
			
			foreach($usuarios as $item) {
				$result .= '<option value="'.$item['id_usuario'].'"';
				$result .= '>'.$item['nome'].' ('.$item['nome_curto'].')</option>';
			}
			return $result;
		}
		
	}

?>
