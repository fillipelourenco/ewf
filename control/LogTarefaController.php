<?

	class LogTarefaController {
	
		function LogTarefaController() {}
		
		/**
		* Ações Recentes da Empresa ou Projeto
		* Retorno: Query
		*/
		function getAcoesRecentes() {
			$sql = "select * from (
					(select t.id_tarefa, t.titulo, t.id_projeto, p.nome as pnome, r.nome as rnome, u.nome,t.chave,c.momento_comentario, c.comentario, -1 as situacao_anterior, -1 as situacao_atual
					from comentarios c
					join tarefas t on c.id_tarefa=t.id_tarefa
					join projetos p on p.id_projeto=t.id_projeto
					left join rotulos r on t.id_rotulo=r.id_rotulo
					join usuarios u on u.id_usuario=c.id_usuario ";
					
			if (isset($_SESSION['id_projeto_logado'])) {
				$sql .= 'where t.id_projeto = '.$_SESSION['id_projeto_logado'].'';
			}
			else {					
				if ($_SESSION['tipo_usuario_logado'] == '1') {
					$sql .= "where u.id_empresa = ".$_SESSION['id_empresa_logada']." ";
				} else {
					$sql .= "join permissoes up on up.id_projeto=t.id_projeto ";
					$sql .= "where up.id_usuario=".$_SESSION['id_usuario_logado']." ";
				}
			}
			
			if ($_SESSION['a_usuario'])
				$sql .= ' and c.id_usuario='.$_SESSION['id_usuario_logado'].' ';
			
			$sql .= " group by t.id_tarefa, p.nome, r.nome, u.nome, c.momento_comentario, c.comentario, t.titulo, t.id_projeto, t.chave order by c.momento_comentario DESC)
					union
					(select t.id_tarefa, t.titulo, t.id_projeto, p.nome as pnome, r.nome as rnome, u.nome, t.chave, lt.momento_alteracao, '' as comentario, lt.situacao_anterior, lt.situacao_atual 
					from logs_tarefas lt
					join tarefas t on lt.chave=t.chave
					join usuarios u on u.id_usuario=lt.id_usuario
					join projetos p on p.id_projeto=t.id_projeto
					left join rotulos r on t.id_rotulo=r.id_rotulo ";
					
			if (isset($_SESSION['id_projeto_logado'])) {
				$sql .= 'where t.id_projeto = '.$_SESSION['id_projeto_logado'].'';
			}
			else {			
				if ($_SESSION['tipo_usuario_logado'] == '1') {
					$sql .= "where t.id_empresa = ".$_SESSION['id_empresa_logada']." ";
				} else {
					$sql .= "join permissoes up on up.id_projeto=t.id_projeto ";
					$sql .= "where up.id_usuario=".$_SESSION['id_usuario_logado']." ";
				}
			}
			
			if ($_SESSION['a_usuario'])
				$sql .= ' and lt.id_usuario='.$_SESSION['id_usuario_logado'].' ';
			
			$sql .= " group by t.id_tarefa, p.nome, r.nome, u.nome, lt.momento_alteracao, lt.situacao_anterior, lt.situacao_atual, t.titulo, t.id_projeto, t.chave having lt.situacao_anterior!=lt.situacao_atual
					order by lt.momento_alteracao DESC)) as recentes
					order by momento_comentario desc
					limit 10";
			
			$atividades = pg_query($sql);	
				
			return $atividades;
		
		}
		
		/**
		* Exibe o Sub-Menu de Ações Recentes
		* Retorno: String
		*/
		function printMenuAcoesRecentes(){
			$result = '<p class="filtros">';
			
			if($_SESSION['a_usuario'])
				$result .= '[<a href="?acoes=todas" title="Ações de Todos os Usuários">Todas Ações</a>] ';
			else
				$result .= '[<a href="?acoes=usuario" title="Ações Realizadas Por Você">Minhas Ações</a>] ';
			
			$result .= '[<a href="?acoes=mais" title="Todas as Ações do Projeto">Mais Ações</a>]</p>';
			
			return $result;
		}
		
		/**
		* Exibe as Ações Recentes da Consulta $atividades
		* Retorno: String
		*/
		function printAcoesRecentes($atividades){
			$data = date('d-m-Y h:i:s');
			$dataAux = date('d-m-Y h:i:s', mktime ($hr,$mi,$si,$mo,$da,'2010'));
			
			$result .= '<form action="../control/action/LogTarefaAction.php" method="post" name="FormAcoes" id="FormAcoes">';
			$result .= '<input type="hidden" name="upd_tarefa" value="">';
			$result .= '<input type="hidden" name="upd_projeto" value="">';
			$count=1;
			while($row = pg_fetch_object($atividades)) {
				if ($count > 1) {
					$dataAux = $row->momento_comentario;
				}
				if (FormataDataTs($dataAux) != FormataDataTs($data)){
					if (FormataDataTs($row->momento_comentario) == FormataDataNumeros(date('Y-m-d')))
						$result .= '<div class="tarefas-data"><b>Hoje</b></div><br>';
					else
						$result .= '<div class="tarefas-data"><b>'.FormataDataTs($row->momento_comentario).'</b></div><br>';
				}
				$data = $row->momento_comentario;
				if ($row->situacao_atual == -1) $row->situacao_atual = 'Comentário';
				if ($row->situacao_atual == 1) $row->situacao_atual = 'Aberta';
				if ($row->situacao_atual == 2) $row->situacao_atual = 'Em Andamento';
				if ($row->situacao_atual == 3) $row->situacao_atual = 'Pendente';
				if ($row->situacao_atual == 4) $row->situacao_atual = 'Fechada';
				if ($row->situacao_anterior == 0) $row->situacao_atual = 'Nova Tarefa';
					
				$result .= '<div style="float:left;border-bottom:1px #ccc solid;margin-bottom:5px;width:400px;margin-left:7px;">';
				if ($row->situacao_atual != 'Comentário') {
					if ($row->situacao_atual == 'Nova Tarefa')
						$result .= '<div style="margin-left:5px;margin-top:15px;"><img title="Nova Tarefa" src="ci/imagens/ico_nova_tarefa.png"></div>';
					else
						$result .= '<div style="margin-left:5px;margin-top:15px;"><img title="Atualização" src="ci/imagens/ico_atualizacao.png"></div>';
					
					$result .= '<div class="tarefas-titulo">';
					$result .= '<a href="javascript:abreAcao(\''.$row->id_tarefa.'\',\''.$row->id_projeto.'\');" name="botao" title="'.$row->titulo.'">'.$row->titulo.'</a>';
					$result .= '</div>';
					$result .= '<div class="tarefas">'.$row->chave.' ('.$row->situacao_atual.')</div><div class="tarefas">'.$row->pnome.'</div><div class="tarefas">'.$row->nome.'</div>';
				}
				else {
					$result .= '<div style="margin-left:5px;margin-top:15px;"><img title="Comentário" src="ci/imagens/ico_comentario.png"></div>';
					$result .= '<div class="tarefas-titulo">';
					$result .= '<a href="javascript:abreAcao(\''.$row->id_tarefa.'\',\''.$row->id_projeto.'\');" name="botao" title="'.$row->titulo.'">'.$row->titulo.'</a>';
					$result .= '</div>';
					$result .= '<div class="tarefas">'.$row->chave.' ('.$row->situacao_atual.')</div><div class="tarefas">'.$row->pnome.'</div><div class="tarefas">'.$row->nome.': <i><font color="#4366ab">"'.$row->comentario.'"</font></i></div>';
				}
				$result .= '</div>';
				$count++;
			}
			$result .= '</form>';
			
			return $result;
		}
		
		/**
		* Exibe a Consulta, com Paginação, das Ações Recentes
		* Retorno: String
		*/
		function printConsultaPaginada(){
		
			$sql = "select * from (
					(select t.id_tarefa, t.id_projeto, t.titulo, u.nome, t.chave, t.situacao, c.momento_comentario, c.comentario, 0 as situacao_anterior, 0 as situacao_atual
					from comentarios c
					join tarefas t on c.id_tarefa=t.id_tarefa
					join usuarios u on u.id_usuario=c.id_usuario ";
					
			if ($_SESSION["tipo_usuario_logado"] == 1) {
				$sql .= "where u.id_empresa = ".$_SESSION["id_empresa_logada"]." ";
			} else {
				$sql .= "join permissoes up on up.id_projeto=t.id_projeto ";
				$sql .= "where up.id_usuario=".$id_usuario_logado." ";
			}
			
			if (!empty($_SESSION['id_projeto_logado'])) { 
				$sql .= " and t.id_projeto=".$_SESSION['id_projeto_logado']." ";
			}
			
			$sql .= "group by t.id_tarefa, u.nome, c.momento_comentario, c.comentario
					order by c.momento_comentario DESC)
					union
					(select t.id_tarefa, t.id_projeto, t.titulo, u.nome, t.chave, t.situacao, lt.momento_alteracao, '' as comentario, lt.situacao_anterior,lt.situacao_atual 
					from logs_tarefas lt
					join tarefas t on lt.chave=t.chave
					join usuarios u on u.id_usuario=lt.id_usuario where lt.id_empresa = ".$_SESSION["id_empresa_logada"]." ";
			
			if ($_SESSION["tipo_usuario_logado"] == 1) {
				$sql .= "and t.id_empresa = ".$_SESSION["id_empresa_logada"]." ";
			} else {
				$sql .= "join permissoes up on up.id_projeto=t.id_projeto ";
				$sql .= "and up.id_usuario=".$id_usuario_logado." ";
			}
			
			if (!empty($_SESSION['id_projeto_logado'])) { 
				$sql .= " and t.id_projeto=".$_SESSION['id_projeto_logado']." ";
			}
			
			$sql .= "group by t.id_tarefa, u.nome, lt.momento_alteracao, lt.situacao_anterior, lt.situacao_atual
					having situacao_anterior!=situacao_atual
					order by lt.momento_alteracao DESC)) as recentes
					order by momento_comentario desc";
					
			$numreg = 10; // Quantos registros por página vai ser mostrado
			if (!isset($_GET['pg'])) {
				$_GET['pg'] = 0;
			}
			$inicial = $_GET['pg'] * $numreg;
					
			$sqlLimite = $sql." limit $numreg offset $inicial";
			
			$qtd = pg_query($sql);
			
			$quantreg = pg_num_rows($qtd);
			
			$atividades = pg_query($sqlLimite);
			
			$result .= '<tr>
							<th style="background:#ffffff;"></th>
							<th>Chave</th>
							<th>Data / Hora</th>
							<th>Usuário</th>
							<th>Situação</th>
							<th>Tarefa</th>
						</tr>';
			
			$result .= '<form action="control/action/LogTarefaAction.php" method="post" name="FormAcoes" id="FormAcoes">';
			$result .= '<input type="hidden" name="upd_tarefa" value="">';
			$result .= '<input type="hidden" name="upd_projeto" value="">';
			
			while($row = pg_fetch_object($atividades)) {
				
				$result .= '<tr>';
				
				if ($row->comentario != '')
					$result .= '<td><img title="Comentário" src="../ci/imagens/ico_comentario.png"></td>';
				else 
					if ($row->situacao_anterior == 0) 
						$result .= '<td><img title="Nova Tarefa" src="../ci/imagens/ico_nova_tarefa.png"></td>';
					else 
						$result .= '<td><img title="Atualização" src="../ci/imagens/ico_atualizacao.png"></td>';
				
				$result .= '<td>'.$row->chave.'</td>';
				$result .= '<td>'.FormataDataHora($row->momento_comentario).'</td>';
											
				$result .= '<td>'.$row->nome.'</td>';
				if ($row->situacao == 1) $row->situacao = 'Aberta';
				if ($row->situacao == 2) $row->situacao = 'Em Andamento';
				if ($row->situacao == 3) $row->situacao = 'Pendente';
				if ($row->situacao == 4) $row->situacao = 'Fechada';
				$result .= '<td>'.$row->situacao.'</td>';
								
				$result .= '<td><a style="font-size:13px;" href="javascript:abreAcao(\''.$row->id_tarefa.'\',\''.$row->id_projeto.'\');">'.$row->titulo.'</a></td>';
				$result .= '</tr>';
			}
			$result .= '</form>';
			$result .= '</table>';
			$result .= '<br>';
			$result .= '<div id="coluna-1" style="width:870px;padding: 0px; height:40px;">';
			include('../../util/Paginacao.php');
			$result .= '</div>';
			
			return $result;
		}
		
	}

?>