<?

	class NotificacaoController {
	
		function NotificacaoController() {}
		
		/**
		* Notificacao Selecionada
		* Retorno: Objeto
		*/
		function get(){
			$notificacao = new Notificacao;
			if(!empty($_SESSION['upd_notificacao'])){
				$notificacao->get($_SESSION['upd_notificacao']);
				$n = $notificacao->toArray();			
			}
			else {
				$n = $notificacao->toArray();
			}
			return $n;
		}
		
		/**
		* Todas as Notifica��es do Usu�rio com Limite
		* Retorno: Lista
		*/
		function listAll() {
			$notificacao = new Notificacao;	
			$notificacao
				->where("id_usuario_destinatario=".$_SESSION['id_usuario_logado']."")
				->order('momento_envio')
				->find();
			
			return $notificacao;
		}
		
		/**
		* Todas as Notifica��es do Usu�rio com Limite
		* Retorno: Lista
		*/
		function listAllLimite() {
			$notificacao = new Notificacao;	
			$notificacao
				->where("id_usuario_destinatario=".$_SESSION['id_usuario_logado']." and situacao=true")
				->order('momento_envio')
				->limit(10)
				->find();
			
			return $notificacao;
		}
		
		/**
		* Exibe as Notifica��es da Consulta $notificacoes
		* Retorno: String
		*/
		function printNotificacoes($notificacoes){
			if (count($notificacoes->allToArray()) == 0)
				$result .= '<div class="tarefas" style="font-size:13px;">N�o H� Novas Notifica��es!</div><br>';
			while($notificacoes->fetch()) {
				$result .= '<div class="tarefas" style="font-size:13px;">- <a href="javascript:abreNotificacao(\''.$notificacoes->id_notificacao.'\');">'.$notificacoes->titulo.'</a></div><br>';
			}
			return $result;
		}
		
		/**
		* Exibe o Submenu das Notifica��es
		* Retorno: String
		*/
		function printFiltros() {
			$result .= '<p class="filtros">';
			$result .= '[<a href="consulta/consNotificacao.php" title="Visualiza todas as notifica��es recebidas.">Todas as Notifica��es</a>]</p>';
			return $result;
		}
		
		/**
		* Exibe as Notifica��es da Consulta $notificacoes
		* Retorno: String
		*/
		function printConsulta($notificacoes){
			$result .= '<tr>';
			$result .= '<th>Situa��o</th>';
			$result .= '<th>T�tulo</th>';
			$result .= '<th>Momento</th>';
			$result .= '</tr>';
			
			while($notificacoes->fetch()) {
				if ($notificacoes->situacao == 't') 
					$situacao = 'Nova';
				else
					$situacao = 'Lida';
				$result .= '<tr>';
				$result .= '<td>'.$situacao.'</td><td>'.$notificacoes->titulo.'</td><td>'.FormataDataHora($notificacoes->momento_envio).'</td>';
				$result .= '<td><button class="update" title="Atualizar" type="submit" name="BTUpd" value="'.$notificacoes->id_notificacao.'"></button></td>';
				if ($_SESSION['tipo_usuario_logado'] == '1')
					$result .= '<td><button onClick="if(confirm(\'Deseja excluir esse registro?\')){return true;}else{return false;}" class="delete" title="Deletar" type="submit" name="BTDel" value="'.$notificacoes->id_notificacao.'"></button></td>';
				$result .= '<tr>';
			}
			return $result;
		}
		
		/**
		* Altera o Status da Notifica��o Lida
		* Retorno: String
		*/
		function leu($id_notificacao){
			$notificacao = new Notificacao;
			$notificacao->get($id_notificacao);
			$notificacao->situacao = false;
			$notificacao->save();
		}
	}

?>