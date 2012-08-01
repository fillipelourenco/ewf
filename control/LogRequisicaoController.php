<?

	class LogRequisicaoController {
	
		function LogRequisicaoController() {}
		
		/**
		* Feedbacks Atualizados ou Comentados do Projeto
		* Retorno: Query
		*/
		function getAtualizacoes() {
		
			$sql .= 'select resposta, titulo, id_requisicao, id_projeto, momento_cadastro, chave, nome, tipo, nomeresp from ((
						select \'\' as resposta, r.titulo, r.id_requisicao, r.id_projeto, lr.momento_alteracao as momento_cadastro, lr.chave, u.nome, \'log\' as tipo, \'\' as nomeresp
							from logs_requisicoes lr
							join requisicoes r on (r.chave=lr.chave)
							join usuarios u on (r.id_usuario_cadastro=u.id_usuario)
							where lr.id_empresa='.$_SESSION['id_empresa_logada'].'';
			
			if ($_SESSION['tipo_usuario_logado'] == '3') 
				$sql .= ' and r.id_cliente='.$_SESSION['id_cliente_logado'].'';
			else 
				$sql .= ' and r.id_projeto='.$_SESSION['id_projeto_logado'].'';
					
			$sql .= ' order by lr.momento_alteracao DESC) union (';
			
			$sql .= 'select i.resposta, r.titulo, r.id_requisicao, r.id_projeto, i.momento_resposta as momento_cadastro, r.chave, u.nome, \'resposta\' as tipo, uc.nome as nomeresp
						from iteracoes i
						join requisicoes r on (r.id_requisicao=i.id_requisicao)
						join usuarios u on (u.id_usuario=r.id_usuario_cadastro)
						join usuarios uc on (uc.id_usuario=i.id_usuario) where ';
						
			if ($_SESSION['tipo_usuario_logado'] == '3') 
				$sql .= 'r.id_cliente='.$_SESSION['id_cliente_logado'].'';
			else 
				$sql .= 'r.id_projeto='.$_SESSION['id_projeto_logado'].'';
				
			$sql .= ' order by i.momento_resposta DESC)) as atualizacoes order by momento_cadastro desc limit 10';
			
			$atualizacoes = pg_query($sql) or die ('erro');	
				
			return $atualizacoes;
			
		}
		
		/**
		* Exibe os Feedbacks Atualizados da Consulta $atualizacoes
		* Retorno: String
		*/
		function printFeedbacksAtualizados($atualizacoes){
			$data = date('d-m-Y h:i:s');
			$dataAux = date('d-m-Y h:i:s', mktime ($hr,$mi,$si,$mo,$da,'2010'));
			
			$result .= '<form action="../control/action/RequisicaoAction.php" method="post" name="FormFeedbacksAtualizados" id="FormFeedbacksAtualizados">';
			$result .= '<input type="hidden" name="upd_requisicao" value="">';
			$result .= '<input type="hidden" name="upd_projeto" value="">';
			$count=1;
			
			while($row = pg_fetch_object($atualizacoes)) {
				if ($count > 1) {
					$dataAux = $row->momento_cadastro;
				}
				
				if (FormataDataTs($dataAux) != FormataDataTs($data)){
					if (FormataDataTs($row->momento_cadastro) == FormataDataNumeros(date('Y-m-d')))
						$result .= '<div class="tarefas-data"><b>Hoje</b></div><br>';
					else
						$result .= '<div class="tarefas-data"><b>'.FormataDataTs($row->momento_cadastro).'</b></div><br>';
				}
				$data = $row->momento_cadastro;
				
				$result .= '<div style="float:left;border-bottom:1px #ccc solid;margin-bottom:5px;width:400px;margin-left:7px;">';
				if ($row->tipo == 'log')
					$result .= '<div style="margin-left:5px;margin-top:15px;"><img title="Atualização" src="ci/imagens/ico_atualizacao.png"></div>';
				else
					$result .= '<div style="margin-left:5px;margin-top:15px;"><img title="Atualização" src="ci/imagens/ico_comentario.png"></div>';
				
				$result .= '<div class="tarefas-titulo">';
				$result .= '<a href="javascript:abreAtualizacao(\''.$row->id_requisicao.'\',\''.$row->id_projeto.'\');" name="botao" title="'.$row->titulo.'">'.$row->titulo.'</a>';
				$result .= '</div>';
				$result .= '<div class="tarefas">Chave de Identificação: <b>'.$row->chave.'</b></div><br>';
				//$result .= '<div class="tarefas">Disponível: <b>Versão 2.1</b></div><br>';
				$result .= '<div class="tarefas">Relatado por: <b>'.$row->nome.'</b></div>';
				
				if ($row->tipo == 'resposta')
				$result .= '<br><div class="tarefas">'.$row->nomeresp.': <i><font color="#4366ab">"'.$row->resposta.'"</font></i></div>';
				
				$result .= '</div>';
				$count++;
			}
			$result .= '</form>';
			
			return $result;
		}
		
	}

?>