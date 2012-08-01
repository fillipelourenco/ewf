<?

	function geraChave($id_empresa){
		$query = pg_query('select sequencia_tarefa from empresas where id_empresa='.$id_empresa.'');
		$seq = pg_fetch_object($query);
		$chave = '#'.($seq->sequencia_tarefa+1);
		return $chave;
	}	
	
	function incrementaChave($id_empresa){
		$query = pg_query('select sequencia_tarefa from empresas where id_empresa='.$id_empresa.'');
		$seq = pg_fetch_object($query);
		$query = pg_query('update empresas set sequencia_tarefa='.($seq->sequencia_tarefa+1).' where id_empresa='.$id_empresa.'');
		return $chave;
	}	
	
	function geraChaveRequisicao($id_empresa){
		$query = pg_query('select sequencia_requisicao from empresas where id_empresa='.$id_empresa.'');
		$seq = pg_fetch_object($query);
		$chave = 'RM#'.($seq->sequencia_requisicao+1);
		return $chave;
	}	
	
	function incrementaChaveRequisicao($id_empresa){
		$query = pg_query('select sequencia_requisicao from empresas where id_empresa='.$id_empresa.'');
		$seq = pg_fetch_object($query);
		$query = pg_query('update empresas set sequencia_requisicao='.($seq->sequencia_requisicao+1).' where id_empresa='.$id_empresa.'');
		return $chave;
	}
	
	function geraChaveFormulario($id_empresa){
		$query = pg_query('select sequencia_formulario from empresas where id_empresa='.$id_empresa.'');
		$seq = pg_fetch_object($query);
		$chave = 'F#'.($seq->sequencia_formulario+1);
		return $chave;
	}	
	
	function incrementaChaveFormulario($id_empresa){
		$query = pg_query('select sequencia_formulario from empresas where id_empresa='.$id_empresa.'');
		$seq = pg_fetch_object($query);
		$query = pg_query('update empresas set sequencia_formulario='.($seq->sequencia_formulario+1).' where id_empresa='.$id_empresa.'');
		return $chave;
	}

?>