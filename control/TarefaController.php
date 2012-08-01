<?

	class TarefaController {
	
		function TarefaController() {}
		
		/**
		* Seleciona a Tarefa Para Atualizar
		* Retorno: Objeto
		*/
		function get(){
			$tarefa = new Tarefa;
			if(!empty($_SESSION['upd_tarefa'])){
				$tarefa->get($_SESSION['upd_tarefa']);
				$t = $tarefa->toArray();
			} else {
				$t = $tarefa->toArray();
			}
			session_start();
			$_SESSION['obj_tarefa'] = $t;
			return $t;
		}
		
		/**
		* Consulta de Tarefas Pendentes de Acordo com as Permissões do Usuário
		* Retorno: Lista
		*/
		function getTarefasPendentes() {
			$tarefa = new Tarefa;
			$projeto = new Projeto;
			$usuario = new Usuario;
			$permissao = new Permissao;
			$tarefa
				->alias('t')
				->join($projeto,'inner','p')
				->join($usuario,'left','u','id_usuario_responsavel','id_usuario');
				
			if (isset($_SESSION['id_projeto_logado'])) {
				$tarefa
					->where('t.id_projeto='.$_SESSION['id_projeto_logado'].' and (t.situacao=1 or t.situacao=2 or t.situacao=3)');
			}
			else {		
				if ($_SESSION['tipo_usuario_logado'] == '1')
					$tarefa
						->where('p.id_empresa='.$_SESSION['id_empresa_logada'].' and (t.situacao=1 or t.situacao=2 or t.situacao=3)');
				else
					$tarefa
						->join($permissao,'inner','up','id_projeto','id_projeto')
						->where('up.id_usuario='.$_SESSION['id_usuario_logado'].' and (t.situacao=1 or t.situacao=2 or t.situacao=3)');
			}
					
			if ($_SESSION['t_usuario'])
				$tarefa->where('t.id_usuario_responsavel='.$_SESSION['id_usuario_logado'].'');
				
			$tarefa
				->select('t.id_projeto,t.id_tarefa,t.prazo, t.titulo, p.nome, u.nome as unome, t.chave, t.situacao')
				->order('t.prazo')
				->limit(10)
				->find();	
				
			return $tarefa;
		
		}
		
		/**
		* Exibe Sub-Menu de Tarefas Pendentes
		* Retorno: String
		*/
		function printMenuTarefasPendentes(){
			$result = '<p class="filtros">';
			if($_SESSION['t_usuario'])
				$result .= '[<a href="?tarefas=todas" title="Tarefas de Todos os Usuários">Todas Tarefas</a>] ';
			else
				$result .= '[<a href="?tarefas=usuario" title="Tarefas Desiguinadas a Você">Minhas Tarefas</a>] ';
			
			if ($_SESSION['id_projeto_logado']) 
				$result .= '[<a href="?tarefas=mais" title="Todas as Tarefas do Projeto">Mais Tarefas</a>]';
			
			$result .= '</p>';
			
			return $result;
		}
		
		/**
		* Exibe as Tarefas Pendentes da Consulta $tarefas
		* Retorno: String
		*/
		function printTarefasPendentes($tarefas){
			$count = 1;
			$data = date('Y-m-d');
			$dataAux = date('Y-m-d')+1;
			
			$result .= '<form action="../control/action/TarefaAction.php" method="post" name="FormTarefas" id="FormTarefas">';
			
			while ($tarefas->fetch()) {
				if ($count > 1) {
					$dataAux = $tarefas->prazo;
				}
				if ($dataAux != $data){
					if ($tarefas->prazo == date('Y-m-d'))
						$result .= '<div class="tarefas-data"><b>Hoje</b></div><br>';
					else
						$result .= '<div class="tarefas-data"><b>'.FormataDataNumeros($tarefas->prazo).'</b></div><br>';
				}
				$data = $tarefas->prazo;
									
				$result .= '<div style="float:left;border-bottom:1px #ccc solid;margin-bottom:5px;width:400px;margin-left:7px;">';
				$vencimento = difData($tarefas->prazo);
				if ($tarefas->situacao == 1) $tarefas->situacao = 'Aberta';
				if ($tarefas->situacao == 2) $tarefas->situacao = 'Em Andamento';
				if ($tarefas->situacao == 3) $tarefas->situacao = 'Pendente';
				if ($tarefas->situacao == 4) $tarefas->situacao = 'Fechada';
				if ($vencimento < 1)
					$result .= '<div style="margin-left:5px;margin-top:10px;"><img title="" src="ci/imagens/ind_vermelho.png"></div>';
				else if ($vencimento >= 1 and $vencimento < 6)
					$result .= '<div style="margin-left:5px;margin-top:10px;"><img title="" src="ci/imagens/ind_amarelo.png"></div>';
				else
					$result .= '<div style="margin-left:5px;margin-top:10px;"><img title="" src="ci/imagens/ind_verde.png"></div>';
				
				$result .= '<div class="tarefas-titulo">';
				$result .= '<a href="javascript:abreTarefa(\''.$tarefas->id_tarefa.'\',\''.$tarefas->id_projeto.'\');" title="'.$tarefas->titulo.'">'.$tarefas->titulo.'</a>';
				$result .= '</div>';
				$result .= '<div class="tarefas">'.$tarefas->chave.' ('.$tarefas->situacao.')</div><div class="tarefas">'.$tarefas->nome.'</div><div class="tarefas">'.$tarefas->unome.'</div>';
				
				$result .= '</div>';
				$count++;
			}
			$result .= '<input type="hidden" name="upd_tarefa" value="">';
			$result .= '<input type="hidden" name="upd_projeto" value="">';
			$result .= '</form>';
			return $result;
		}
		
		/**
		* Exibe a Consulta, com Paginação, das Tarefas
		* Retorno: String
		*/
		function printConsultaPaginada(){
			$result .= '<tr>';
			$result .= '<th>Chave</th>';
			$result .= '<th>Tipo</th>';
			$result .= '<th>Situação</th>';
			$result .= '<th width="300">Título</th>';
			$result .= '<th>Prazo</th>';
			$result .= '</tr>';
			
			$numreg = 5; // Quantos registros por página vai ser mostrado
			if (!isset($_GET['pg'])) {
				$_GET['pg'] = 0;
			}
			$inicial = $_GET['pg'] * $numreg;
									
			$sql = "SELECT count(*) as qtd FROM tarefas where id_projeto=".$_SESSION['id_projeto_logado']."";
									
			$tarefa = new Tarefa;
			if (!empty($_SESSION["tipo_log"])) {
				$tarefa->where('t.id_rotulo='.$_SESSION["tipo_log"].'');
				$sql .= ' and id_rotulo='.$_SESSION["tipo_log"].'';
			}
			if (!empty($_SESSION["componente_log"])) {
				$tarefa->where('t.id_componente='.$_SESSION["componente_log"].'');
				$sql .= ' and id_componente='.$_SESSION["componente_log"].'';
			}
			if (empty($_SESSION["situacao_log"]) or $_SESSION["situacao_log"] == '0') {
				$tarefa->where("id_projeto=".$_SESSION['id_projeto_logado']." and situacao in (1,2,3)");
				$sql .= " and situacao in (1,2,3)";
			} else if ($_SESSION["situacao_log"] == '1') {
				$tarefa->where("id_projeto=".$_SESSION['id_projeto_logado']." and situacao=1");
				$sql .= " and situacao=1";
			} else if ($_SESSION["situacao_log"] == '2') {
				$tarefa->where("id_projeto=".$_SESSION['id_projeto_logado']." and situacao=2");
				$sql .= " and situacao=2";
			} else if ($_SESSION["situacao_log"] == '3') {
				$tarefa->where("id_projeto=".$_SESSION['id_projeto_logado']." and situacao=3");
				$sql .= " and situacao=3";
			} else if ($_SESSION["situacao_log"] == '4') {
				$tarefa->where("id_projeto=".$_SESSION['id_projeto_logado']." and situacao=4");
				$sql .= " and situacao=4";
			} else if ($_SESSION["situacao_log"] == '5') {
				$tarefa->where("id_projeto=".$_SESSION['id_projeto_logado']."");
			}
			$rotulo = new Rotulo;
			$tarefa
				->alias('t')
				->select('t.*, r.nome as rnome')
				->join($rotulo,'left','r')
				->order('prazo ASC')
				->limit($inicial,$numreg)
				->find();
			$tarefas = $tarefa->allToArray();

			$sql_conta = pg_query($sql);
			$contador = pg_fetch_object($sql_conta);
			$quantreg = $contador->qtd;
									
			foreach($tarefas as $item) {
				if ($item['situacao'] == 1) $item['situacao'] = 'Aberta';
				if ($item['situacao'] == 2) $item['situacao'] = 'Em Andamento';
				if ($item['situacao'] == 3) $item['situacao'] = 'Pendente';
				if ($item['situacao'] == 4) $item['situacao'] = 'Fechada';
				$result .= '<tr>';
				$result .= '<td>'.$item['chave'].'</td><td>'.$item['rnome'].'</td><td>'.$item['situacao'].'</td><td>'.$item['titulo'].'</td><td>'.FormataDataNumeros($item['prazo']).'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$item['id_tarefa'].'"></button></td>';
				if ($_SESSION["tipo_usuario_logado"] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$item['id_tarefa'].'"></button></td>';
					$result .= '<tr>';
			}
			$result .= '</table>';
			$result .= '<br>';
			$result .= '<div id="coluna-1" style="width:870px;padding: 0px; height:40px;">';
			include("../../util/Paginacao.php");
			$result .= "</div>";
			
			return $result;
		}
		
		/**
		* Combo da Situação da Tarefa
		* Retorno: String
		*/
		function getSitucao(){
			if (!empty($_SESSION['upd_tarefa'])) {
				$result .= '<p><label for="situacao">Situação:</label>';
                $result .= '<select size="1" name="situacao" style="width:180px;" ';
				
				if (!$_SESSION['permissao_var']) $result .= "disabled";
				
				$result .= '><option value="1" ';
				
				if ($_SESSION['obj_tarefa']['situacao'] == '1') $result .= "selected";
				
				$result .= '>Aberta</option>';
				$result .= '<option value="2" ';
				
				if ($_SESSION['obj_tarefa']['situacao'] == '2') $result .= "selected";
				
				$result .= '>Em Andamento</option>';
				$result .= '<option value="3" ';
				
				if ($_SESSION['obj_tarefa']['situacao'] == '3') $result .= "selected";
				
				$result .= '>Pendente</option>';
				$result .= '<option value="4" ';
				
				if ($_SESSION['obj_tarefa']['situacao'] == '4') $result .= "selected"; 
				
				$result .= '>Fechada</option>';
				$result .= '</select></p>';
				
				return $result;
			}
		}
		
		/**
		* Exibe Chave da Tarefa
		* Retorno: String
		*/
		function getChave(){
			if (!empty($_SESSION['upd_tarefa'])){
				$result .= '<p><label for="chave">Chave:</label>';
				$result .= '<label for="chave"><b>'.$_SESSION['obj_tarefa']['chave'].'</b></label>';
				$result .= '<input type="hidden" id="situacao_anterior" value="'.$_SESSION['obj_tarefa']['situacao'].'" name="situacao_anterior">';
				$result .= '<input type="hidden" id="chave" value="'.$_SESSION['obj_tarefa']['chave'].'" name="chave">';
			}
			return $result;
		}
		
		/**
		* Lista Anexos da Tarefa
		* Retorno: String
		*/
		function getAnexos() {
			if (!empty($_SESSION['upd_tarefa'])) {
				$count = 1;
				$diretorio = "../anexos/".$_SESSION['obj_tarefa']['pasta'];
				$handle = opendir($diretorio);
				while ($file = readdir($handle)) {
					if (strlen($file)>2) {
						if ($count==1) {
							$result .= '<label for="descricao">Anexos:</label>';
							$count++;
						} else $result .= '<label for="descricao"><font color="#ffffff">.</font> </label>';
					if (!$_SESSION['permissao'])
						$result .= "<input type=\"checkbox\" checked value=\"".$file."\" name=\"anexados[]\" disabled>  <a target=\"blank_\" href=\"../anexos/".$_SESSION['obj_tarefa']['pasta']."/".$file."\">$file</a><br>";
					else
						$result .= "<input type=\"checkbox\" checked value=\"".$file."\" name=\"anexados[]\">  <a target=\"blank_\" href=\"../anexos/".$_SESSION['obj_tarefa']['pasta']."/".$file."\">$file</a><br>";
					}
				}
				closedir($handle);
			}
			return $result;
		}
		
	}

?>
