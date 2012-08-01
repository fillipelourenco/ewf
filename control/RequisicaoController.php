<?

	class RequisicaoController {
	
		function RequisicaoController() {}
		
		/**
		* Seleciona a Requisicao Para Atualizar
		* Retorno: Objeto
		*/
		function get(){
			$requisicao = new Requisicao;
			if(!empty($_SESSION['upd_requisicao'])){
				$requisicao->get($_SESSION['upd_requisicao']);
				$r = $requisicao->toArray();
			} else {
				$r = $requisicao->toArray();
			}
			session_start;
			$_SESSION['obj_requisicao'] = $r;
			return $r;
		}
		
		/**
		* Consulta Requisições do Projeto Logado
		* Retorno: Lista
		*/
		function getRequisicoes() {
			
			$requisicao = new Requisicao;
			$usuario = new Usuario;
			
			$requisicao
				->alias('r');
			if ($_SESSION['tipo_usuario_logado'] != '3') {
				$requisicao
					->join($usuario,'inner','u','id_usuario_cadastro','id_usuario')
					->where('r.id_projeto='.$_SESSION['id_projeto_logado'].'');
			}
			else {
				$requisicao
					->join($usuario,'inner','u','id_usuario_cadastro','id_usuario')
					->where('r.id_cliente='.$_SESSION['id_cliente_logado'].'');
			}
			$requisicao
				->order('r.momento_cadastro DESC')
				->limit(5)
				->find();
				
			return $requisicao;
		}
		
		/**
		* Todos as Requisições do Projeto Logado
		* Retorno: Lista
		*/
		function listAll() {
			$requisicao = new Requisicao;
			$usuario = new Usuario;
			$requisicao
				->alias('r')
				->join($usuario,'inner','u','id_usuario_cadastro','id_usuario');
			if ($_SESSION['tipo_usuario_logado'] != '3')
				$requisicao
					->where("r.id_projeto=".$_SESSION['id_projeto_logado']."");
			else
				$requisicao
					->where("r.id_cliente=".$_SESSION['id_cliente_logado']." and r.id_projeto=".$_SESSION['id_projeto_logado']."");
			$requisicao
				->order('r.momento_cadastro')
				->find();
			
			return $requisicao;
		}
		
		/**
		* Todos as Requisições do Projeto Logado
		* Retorno: Lista
		*/
		function listRecebidas() {
			session_start();
			$requisicao = new Requisicao;
			$usuario = new Usuario;
			$cliente = new Cliente;
			$requisicao
				->alias('r')
				->join($usuario,'inner','u','id_usuario_responsavel','id_usuario')
				->join($cliente,'left','c','id_cliente','id_cliente')
				->where('r.id_projeto='.$_SESSION['id_projeto_logado'].' and (r.situacao=1 or r.situacao=2)');
				
			if ($_SESSION['ordem_log'] == null or $_SESSION['ordem_log'] == '0') {
				$requisicao
					->order('r.prioridade');
			}
			else {
				if ($_SESSION['ordem_log'] == '1') {
						$requisicao
							->order('r.momento_cadastro DESC');
				}
				else {
					if ($_SESSION['ordem_log'] == '2') {
						$requisicao
							->order('r.momento_alteracao DESC');
					}
				}
			}
			
			if (!($_SESSION['cliente_log'] == null or $_SESSION['cliente_log'] == '0')) {
				$requisicao
					->where('r.id_cliente='.$_SESSION['cliente_log'].'');
			}
			
			if (!($_SESSION['responsavel_log'] == null or $_SESSION['responsavel_log'] == '0')) {
				$requisicao
					->where('r.id_usuario_responsavel='.$_SESSION['responsavel_log'].'');
			}			
			
			$requisicao
				//->order('r.momento_cadastro DESC')
				->limit(30)
				->find();
			
			return $requisicao;
		}
		
		/**
		* Exibe as Requisições da Consulta $requisicoes
		* Retorno: String
		*/
		function printConsulta($requisicoes){
			$result .= '<tr>';
			$result .= '<th>Chave</th>';
			$result .= '<th>Título</th>';
			$result .= '<th>Situação</th>';
			$result .= '<th>Requisitante</th>';
			$result .= '<th>Cadastro</th>';
			$result .= '</tr>';
			
			while($requisicoes->fetch()) {
				if ($requisicoes->situacao == 1) $requisicoes->situacao = 'Aguardando Análise';
				if ($requisicoes->situacao == 2) $requisicoes->situacao = 'Solução em Andamento';
				if ($requisicoes->situacao == 3) $requisicoes->situacao = 'Resolvido';
				if ($requisicoes->situacao == 4) $requisicoes->situacao = 'Rejeitado';
				$result .= '<tr>';
				$result .= '<td>'.$requisicoes->chave.'</td><td>'.$requisicoes->titulo.'</td><td>'.$requisicoes->situacao.'</td><td>'.$requisicoes->nome.'</td><td>'.FormataDataHora($requisicoes->momento_cadastro).'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$requisicoes->id_requisicao.'"></button></td>';
				if ($_SESSION['tipo_usuario_logado'] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$requisicoes->id_requisicao.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Exibe as Requisições do Monitor de Feedback $requisicoes
		* Retorno: String
		*/
		function printMonitor($requisicoes){
			$result .= '<tr>';
			$result .= '<th>Prioridade</th>';
			$result .= '<th>Chave</th>';
			$result .= '<th>Título</th>';
			$result .= '<th>Situação</th>';
			$result .= '<th>Responsavel</th>';
			$result .= '<th>Cliente</th>';
			$result .= '<th>Cadastro</th>';
			$result .= '</tr>';
			
			while($requisicoes->fetch()) {
				if ($requisicoes->situacao == 1) $requisicoes->situacao = 'Aguardando Análise';
				if ($requisicoes->situacao == 2) $requisicoes->situacao = 'Solução em Andamento';
				if ($requisicoes->situacao == 3) $requisicoes->situacao = 'Resolvido';
				if ($requisicoes->situacao == 4) $requisicoes->situacao = 'Rejeitado';
				$result .= '<tr>';
				$result .= '<td>'.$requisicoes->prioridade.'</td><td>'.$requisicoes->chave.'</td><td>'.$requisicoes->titulo.'</td><td>'.$requisicoes->situacao.'</td><td>'.$requisicoes->nome.'</td><td>'.$requisicoes->nome_curto.'</td><td>'.FormataDataHora($requisicoes->momento_cadastro).'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$requisicoes->id_requisicao.'"></button></td>';
				if ($_SESSION['tipo_usuario_logado'] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$requisicoes->id_requisicao.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Exibe as Ultimas Requisicoes Solicitadas $requisicoes
		* Retorno: String
		*/
		function printUltimasRequisicoes($requisicoes){
			$count = 1;
			$data = date('d/m/Y');
			$dataAux = date('d/m/Y')+1;
			
			$result .= '<form action="../control/action/RequisicaoAction.php" method="post" name="FormUltimosFeeds" id="FormUltimosFeeds">';
			
			while ($requisicoes->fetch()) {
				if ($count > 1) {
					$dataAux = FormataDataTs($requisicoes->momento_cadastro);
				}
				if ($dataAux != $data){
					if (FormataDataTs($requisicoes->momento_cadastro) == date('d/m/Y'))
						$result .= '<div class="tarefas-data"><b>Hoje</b></div><br>';
					else
						$result .= '<div class="tarefas-data"><b>'.FormataDataTs($requisicoes->momento_cadastro).'</b></div><br>';
				}
				$data = FormataDataTs($requisicoes->momento_cadastro);
									
				$result .= '<div style="float:left;border-bottom:1px #ccc solid;margin-bottom:5px;width:400px;margin-left:7px;">';
				
				if ($requisicoes->situacao == 1) $requisicoes->situacao = 'Aguardando Análise';
				if ($requisicoes->situacao == 2) $requisicoes->situacao = 'Solução em Andamento';
				if ($requisicoes->situacao == 3) $requisicoes->situacao = 'Resolvido';
				if ($requisicoes->situacao == 4) $requisicoes->situacao = 'Rejeitado';
				
				$result .= '<div style="margin-left:5px;margin-top:15px;"><img title="" src="ci/imagens/ind_vermelho.png"></div>';
				
				$result .= '<div class="tarefas-titulo">';
				$result .= '<a href="javascript:abreRequisicao(\''.$requisicoes->id_requisicao.'\',\''.$requisicoes->id_projeto.'\');" title="'.$requisicoes->titulo.'">'.$requisicoes->titulo.'</a>';
				$result .= '</div>';
				
				$result .= '<div class="tarefas">Chave de Identificação: <b>'.$requisicoes->chave.'</b></div><br>';
				$result .= '<div class="tarefas">Situação: <b>'.$requisicoes->situacao.'</b></div><br>';
				$result .= '<div class="tarefas">Solicitado por: <b>'.$requisicoes->nome.'</b></div>';
				
				$result .= '</div>';
				$count++;
			}
			$result .= '<input type="hidden" name="upd_requisicao" value="">';
			$result .= '<input type="hidden" name="upd_projeto" value="">';
			$result .= '</form>';
			return $result;
		}
		
		/**
		* Lista Anexos da Tarefa
		* Retorno: String
		*/
		function getAnexos() {
			if (!empty($_SESSION['upd_requisicao'])) {
				$count = 1;
				$diretorio = "../anexos/".$_SESSION['obj_requisicao']['pasta'];
				$handle = opendir($diretorio);
				while ($file = readdir($handle)) {
					if (strlen($file)>2) {
						if ($count==1) {
							$result .= '<label for="descricao">Anexos:</label>';
							$count++;
						} else $result .= '<label for="descricao"><font color="#ffffff">.</font> </label>';
					//if (!$_SESSION['permissao'])
						//$result .= "<input type=\"checkbox\" checked value=\"".$file."\" name=\"anexados[]\" disabled>  <a target=\"blank_\" href=\"../anexos/".$_SESSION['obj_tarefa']['pasta']."/".$file."\">$file</a><br>";
					//else
						$result .= "<input type=\"checkbox\" checked value=\"".$file."\" name=\"anexados[]\">  <a target=\"blank_\" href=\"../anexos/".$_SESSION['obj_requisicao']['pasta']."/".$file."\">$file</a><br>";
					}
				}
				closedir($handle);
			}
			return $result;
		}
		
		/**
		* Combo da Situação da Requisição
		* Retorno: String
		*/
		function getSitucao(){
			if (!empty($_SESSION['upd_requisicao'])) {
				$result .= '<p><label for="situacao">Situação:</label>';
		                $result .= '<select size="1" name="situacao" style="width:180px;" ';
				
				$result .= $_SESSION['permissao'];
				
				$result .= '><option value="1" ';
				
				if ($_SESSION['obj_requisicao']['situacao'] == '1') $result .= "selected";
				
				$result .= '>Aguardando Análise</option>';
				$result .= '<option value="2" ';
				
				if ($_SESSION['obj_requisicao']['situacao'] == '2') $result .= "selected";
				
				$result .= '>Solução em Andamento</option>';
				$result .= '<option value="3" ';
				
				if ($_SESSION['obj_requisicao']['situacao'] == '3') $result .= "selected";
				
				$result .= '>Resolvido</option>';
				$result .= '<option value="4" ';
				
				if ($_SESSION['obj_requisicao']['situacao'] == '4') $result .= "selected"; 
				
				$result .= '>Rejeitado</option>';
				$result .= '</select></p>';
				
				return $result;
			}
		}
		
		/**
		* Exibe Chave da Requisição
		* Retorno: String
		*/
		function getChave(){
			if (!empty($_SESSION['upd_requisicao'])){
				$result .= '<p><label for="chave">Chave:</label>';
				$result .= '<label for="chave"><b>'.$_SESSION['obj_requisicao']['chave'].'</b></label>';
				$result .= '<input type="hidden" id="situacao_anterior" value="'.$_SESSION['obj_requisicao']['situacao'].'" name="situacao_anterior">';
				$result .= '<input type="hidden" id="chave" value="'.$_SESSION['obj_requisicao']['chave'].'" name="chave">';
			}
			return $result;
		}
		
	}

?>
