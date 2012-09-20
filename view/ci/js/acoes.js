	function selecionaProjetoFrame( aux )
	{
		document.getElementById('FormProjetosFrame').upd_projeto.value = aux;
		document.getElementById('FormProjetosFrame').submit();
	}
	function selecionaProjetoMenu( aux )
	{
		document.getElementById('FormProjetosMenu').upd_projeto.value = aux;
		document.getElementById('FormProjetosMenu').submit();
	}
	
	function abreTarefa( aux, aux2 )
	{
		document.getElementById('FormTarefas').upd_tarefa.value = aux;
		document.getElementById('FormTarefas').upd_projeto.value = aux2;
		document.getElementById('FormTarefas').submit();
	}
	
	function abreRequisicao( aux, aux2 )
	{
		document.getElementById('FormUltimosFeeds').upd_requisicao.value = aux;
		document.getElementById('FormUltimosFeeds').upd_projeto.value = aux2;
		document.getElementById('FormUltimosFeeds').submit();
	}
	
	function abreAtualizacao( aux, aux2 )
	{
		document.getElementById('FormFeedbacksAtualizados').upd_requisicao.value = aux;
		document.getElementById('FormFeedbacksAtualizados').upd_projeto.value = aux2;
		document.getElementById('FormFeedbacksAtualizados').submit();
	}
	
	function abreAcao( aux, aux2 )
	{
		document.getElementById('FormAcoes').upd_tarefa.value = aux;
		document.getElementById('FormAcoes').upd_projeto.value = aux2;
		document.getElementById('FormAcoes').submit();
	}
	
	function abreNotificacao( aux )
	{
		document.getElementById('FormNotificacoes').upd_notificacao.value = aux;
		document.getElementById('FormNotificacoes').submit();
	}
	
	function atualizarPerfil()
	{
		document.getElementById('UpdPerfil').submit();
	}
	
	function visualizarPerfil( aux )
	{
		document.getElementById('FormUsuarios').upd_usuario.value = aux;
		document.getElementById('FormUsuarios').submit();
	}
	
	function selecionaFormulario( aux )
	{
		document.getElementById('FormFormularios').upd_formulario.value = aux;
		document.getElementById('FormFormularios').submit();
	}
	
	function visualizaResultados( aux )
	{
		document.getElementById('FormAvaliacoes').read_formulario.value = aux;
		document.getElementById('FormAvaliacoes').submit();
	}
	
	function renderCliente()
	{
		if (document.cadUsuario.userTipo.value == '1' || document.cadUsuario.userTipo.value == '2'){
			document.cadUsuario.field_id_cliente.disabled = 1;
		}
		else {
			document.cadUsuario.field_id_cliente.disabled = 0;
		}
	}